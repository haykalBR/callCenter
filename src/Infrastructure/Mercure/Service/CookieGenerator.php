<?php
namespace App\Infrastructure\Mercure\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\Cookie;
class CookieGenerator
{
    /**
     * @var string
     */
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }
    public function generate( ): Cookie
    {
        $token = (new Builder())
            ->withClaim('mercure', [
                'subscribe' => [
                    'http://www.crm.local.com/'
                ],
            ])
            ->getToken(new Sha256(), new Key($this->secret));

        return Cookie::create('mercureAuthorization', $token, 0, 'http://www.crm.local.com//.well-known/mercure');
    }
}