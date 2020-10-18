<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Services;

use phpDocumentor\Reflection\Types\Boolean;
use ReCaptcha\ReCaptcha;

class CaptchaValidator
{
    private string $key;
    private string $secret;

    public function __construct( string $key, string $secret)
    {
        $this->key    = $key;
        $this->secret = $secret;
    }

    public function validateCaptcha( string $gRecaptchaResponse) :bool
    {
        $recaptcha = new ReCaptcha($this->secret);
        $resp      = $recaptcha->verify($gRecaptchaResponse);

        return $resp->isSuccess();
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
