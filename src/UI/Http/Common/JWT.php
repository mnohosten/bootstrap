<?php
declare(strict_types=1);

namespace App\UI\Http\Common;

use App\Config;
use App\UI\Http\Common\Session\UserSession;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class JWT
{

    /** @var UserSession */
    private UserSession $userSession;
    /** @var Config */
    private Config $config;

    public function __construct(
        UserSession $userSession,
        Config $config
    )
    {
        $this->userSession = $userSession;
        $this->config = $config;
    }

    public function getToken(): string
    {
        if (!$this->userSession->isLoggedIn()) {
            throw new \LogicException("User is not authorized to receive Bearer token.");
        }

        $signer = new Sha256();
        $time = time();

        $token = (new Builder())
            ->issuedBy($this->config->get('app.host')) // Configures the issuer (iss claim)
            ->permittedFor($this->config->get('app.host')) // Configures the audience (aud claim)
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $this->userSession->getUser()->id) // Configures a new claim, called "uid"
            ->getToken($signer, new Key($this->config->get('app.key'))); // Retrieves the generated token

        return (string)$token;
    }

    public function parseToken(string $jwt): Token
    {
        $signer = new Sha256();
        $token = (new Parser())->parse((string)$jwt);

        $data = new ValidationData();
        $data->setIssuer($this->config->get('app.host'));
        $data->setAudience($this->config->get('app.host'));
//        $data->setCurrentTime(time() + 4000); // Pro testovaci ucely

        if ($this->isTokenValid($token, $data, $signer)) {
            throw new \LogicException('Token is invalid or expired.');
        }
        return $token;
    }

    /**
     * @param Token $token
     * @param ValidationData $data
     * @param Sha256 $signer
     * @return bool
     */
    private function isTokenValid(Token $token, ValidationData $data, Sha256 $signer): bool
    {
        return !$token->validate($data) || !$token->verify($signer, $this->config->get('app.key'));
    }

}