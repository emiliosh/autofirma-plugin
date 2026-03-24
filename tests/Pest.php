<?php

declare(strict_types=1);

use Emiliosh\AutofirmaPlugin\Tests\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

expect()->extend('toBeOneOf', function (mixed ...$values): Pest\Expectation {
    return $this->toBeIn($values);
});
