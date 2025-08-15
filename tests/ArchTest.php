<?php

arch('does not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('classes are properly namespaced')
    ->expect('Iotron\LaravelRazorpay')
    ->toHaveFiles()
    ->ignoring('Iotron\LaravelRazorpay\Tests');

arch('actions are simple classes')
    ->expect('Iotron\LaravelRazorpay\Actions')
    ->toBeClasses()
    ->toHaveSuffix('Action');
