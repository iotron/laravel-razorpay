# Laravel Razorpay

A minimal, zero-opinion Laravel wrapper for the Razorpay PHP SDK.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/iotron/laravel-razorpay.svg?style=flat-square)](https://packagist.org/packages/iotron/laravel-razorpay)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/iotron/laravel-razorpay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/iotron/laravel-razorpay/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/iotron/laravel-razorpay.svg?style=flat-square)](https://packagist.org/packages/iotron/laravel-razorpay)

## Why This Package?

Unlike other Laravel payment packages that force specific database schemas, response formats, or business logic, this package provides a **clean, minimal wrapper** around the Razorpay PHP SDK that:

- Returns **raw Razorpay responses** (no formatting overhead)
- Works with **any database schema** (via flexible traits)
- Adds **zero opinions** about your application architecture
- Provides **essential helpers** not available in the base SDK

## Features

- 🚀 **Simple API** - Direct access to all Razorpay functionality
- 🔧 **Optional traits** - Model integration without forced contracts
- 🛡️ **Webhook verification** - Built-in signature validation
- 📦 **Minimal dependencies** - Only what you need
- 🎯 **Zero opinions** - No forced architecture or response formatting
- ⚡ **Laravel native** - Service container, not singleton pattern

## Installation

```bash
composer require iotron/laravel-razorpay
```

## Quick Start

### 1. Configuration

Add your Razorpay credentials to `.env`:

```env
RAZORPAY_KEY=your_key_here
RAZORPAY_SECRET=your_secret_here
RAZORPAY_WEBHOOK_SECRET=your_webhook_secret_here
```

### 2. Basic Usage

```php
use Iotron\LaravelRazorpay\RazorpayManager;

class PaymentController extends Controller
{
    public function __construct(
        private RazorpayManager $razorpay
    ) {}
    
    public function createOrder()
    {
        // Returns raw Razorpay response - no formatting
        $order = $this->razorpay->orders()->create([
            'amount' => 50000, // Amount in paise
            'currency' => 'INR',
            'receipt' => 'order_receipt_123',
        ]);
        
        return response()->json($order->toArray());
    }
}
```

## API Reference

### Orders
```php
$razorpay->orders()->create($data);        // Create order
$razorpay->orders()->fetch($orderId);      // Fetch order
$razorpay->orders()->all($options);        // List orders
```

### Payments
```php
$razorpay->payments()->fetch($paymentId);           // Fetch payment
$razorpay->payments()->all($options);               // List payments
$razorpay->payments()->capture($paymentId, $data);  // Capture payment
$razorpay->payments()->refund($paymentId, $data);   // Refund payment
```

### Refunds
```php
$razorpay->refunds()->create($paymentId, $data);  // Create refund
$razorpay->refunds()->fetch($refundId);           // Fetch refund
$razorpay->refunds()->all($options);              // List refunds
```

### QR Codes
```php
$razorpay->qr()->create($data);             // Create QR code
$razorpay->qr()->fetch($qrId);              // Fetch QR code
$razorpay->qr()->all($options);             // List QR codes
$razorpay->qr()->close($qrId);              // Close QR code
$razorpay->qr()->fetchAllPayments($qrId);   // Get QR payments
```

### Direct SDK Access
```php
// Access the underlying Razorpay SDK directly
$api = $razorpay->getApi();
$customer = $api->customer->create([
    'name' => 'John Doe', 
    'email' => 'john@example.com'
]);
```

## Model Integration (Optional)

### Flexible Traits

The package provides optional traits that work with **your existing database schema**:

```php
use Illuminate\Database\Eloquent\Model;
use Iotron\LaravelRazorpay\Traits\HasRazorpayPayments;

class Payment extends Model
{
    use HasRazorpayPayments;
    
    // Works with common field names automatically:
    // - provider_gen_id, razorpay_payment_id, razorpay_order_id
    // - provider_transaction_id, razorpay_transaction_id
    // - provider_generated_sign, provider_gen_sign, razorpay_signature
    // - verified, is_verified
    // - type, payment_type
}

// Usage
$payment = Payment::find(1);
$paymentId = $payment->getRazorpayPaymentId();
$isVerified = $payment->isPaymentVerified();
$type = $payment->getPaymentType();
```

### Custom Field Mapping

If your fields are different, override the attribute methods:

```php
class Payment extends Model
{
    use HasRazorpayPayments;
    
    // Map to your custom fields
    protected function getRazorpayPaymentIdAttribute(): ?string
    {
        return $this->my_custom_payment_id_field;
    }
    
    protected function getRazorpayTransactionIdAttribute(): ?string
    {
        return $this->my_custom_transaction_field;
    }
}
```

### Refund Model

```php
use Iotron\LaravelRazorpay\Traits\HasRazorpayRefunds;

class Refund extends Model
{
    use HasRazorpayRefunds;
    
    // Works with: refund_id, razorpay_refund_id, provider_refund_id
    // Status: status, refund_status
    // Amount: amount, refund_amount
}

// Usage
$refund = Refund::find(1);
$refundId = $refund->getRazorpayRefundId();
$status = $refund->getRefundStatus();
$amount = $refund->getRefundAmount();
```

## Webhook Verification

### Complete Webhook Handler

```php
use Iotron\LaravelRazorpay\Support\WebhookVerifier;
use Iotron\LaravelRazorpay\RazorpayManager;

class WebhookController extends Controller
{
    public function handle(Request $request, RazorpayManager $razorpay)
    {
        $verifier = new WebhookVerifier(
            config('razorpay.webhook_secret'),
            $razorpay->getApi() // Optional: for enhanced verification
        );
        
        // Verify webhook signature
        if (!$verifier->verifyWebhookSignature(
            $request->getContent(),
            $request->header('X-Razorpay-Signature')
        )) {
            return response('Invalid signature', 401);
        }
        
        // Parse and process webhook
        $payload = $verifier->parsePayload($request->getContent());
        $event = $verifier->getEventType($payload);
        $entity = $verifier->getEntity($payload);
        
        match($event) {
            'payment.captured' => $this->handlePaymentCaptured($entity),
            'payment.failed' => $this->handlePaymentFailed($entity),
            'refund.processed' => $this->handleRefundProcessed($entity),
            default => logger("Unhandled webhook event: {$event}")
        };
        
        return response('OK');
    }
    
    private function handlePaymentCaptured(array $payment)
    {
        // Update your payment record
        Payment::where('provider_gen_id', $payment['order_id'])
               ->update(['status' => 'completed']);
    }
}
```

### Payment Callback Verification

```php
public function verifyPayment(Request $request, RazorpayManager $razorpay)
{
    $verifier = new WebhookVerifier(
        config('razorpay.webhook_secret'),
        $razorpay->getApi()
    );
    
    // Verify payment signature from frontend callback
    $isValid = $verifier->verifyPaymentSignature([
        'razorpay_order_id' => $request->input('razorpay_order_id'),
        'razorpay_payment_id' => $request->input('razorpay_payment_id'),
        'razorpay_signature' => $request->input('razorpay_signature'),
    ]);
    
    if ($isValid) {
        // Payment verified - update your records
        $this->updatePaymentStatus($request->input('razorpay_order_id'), 'verified');
        return response()->json(['status' => 'success']);
    }
    
    return response()->json(['status' => 'invalid'], 400);
}
```

## Configuration

Publish the config file for customization:

```bash
php artisan vendor:publish --tag="laravel-razorpay-config"
```

```php
// config/razorpay.php
return [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
];
```

## Testing

The package includes comprehensive tests:

```bash
composer test
```

Test categories:
- **RazorpayManager**: Service instantiation and API access
- **Actions**: All CRUD operations for orders, payments, refunds, QR
- **Traits**: Model integration with various field configurations
- **WebhookVerifier**: Signature validation and payload parsing
- **Architecture**: Code quality and structure validation

## Why Not Other Packages?

Most Laravel payment packages:
- ❌ Force specific database schemas
- ❌ Return formatted/wrapped responses
- ❌ Include unnecessary features (admin panels, etc.)
- ❌ Make architectural decisions for you
- ❌ Have heavy dependencies

This package:
- ✅ Works with **your** database schema
- ✅ Returns **raw** Razorpay responses
- ✅ Includes **only** essential features
- ✅ Makes **zero** architectural assumptions
- ✅ Has **minimal** dependencies

## Requirements

- PHP 8.1+
- Laravel 10.0+ or 11.0+
- Razorpay PHP SDK 2.9+

## Contributing

Contributions are welcome! Please ensure:
- Tests pass: `composer test`
- Code follows package architecture (minimal, zero-opinion)
- New features include tests and documentation

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Iotron](https://github.com/iotron)
- [All Contributors](../../contributors)

## Support

- 📖 [Razorpay API Documentation](https://razorpay.com/docs/api/)
- 🐛 [Issue Tracker](https://github.com/iotron/laravel-razorpay/issues)
- 💬 [Discussions](https://github.com/iotron/laravel-razorpay/discussions)