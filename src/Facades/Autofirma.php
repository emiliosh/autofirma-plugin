<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Facades;

use Emiliosh\AutofirmaPlugin\AutofirmaService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string prepareData(string $data)
 * @method static string decodeSignature(string $signatureB64)
 * @method static bool   verifySignature(string $signatureB64, string $originalData)
 * @method static string buildParams(array $options = [])
 * @method static mixed  getConfig(?string $key = null, mixed $default = null)
 *
 * @see AutofirmaService
 */
class Autofirma extends Facade
{
    #[\Override]
    protected static function getFacadeAccessor(): string
    {
        return 'autofirma';
    }
}
