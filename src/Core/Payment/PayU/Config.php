<?php
declare(strict_types=1);

namespace App\Core\Payment\PayU;


class Config
{
    /** @var string */
    private string $environment;
    /** @var string */
    private string $merchantPosId;
    /** @var string */
    private string $signatureKey;
    /** @var string */
    private string $oAuthClientId;
    /** @var string */
    private string $oAuthClientSecret;
    /** @var string */
    private string $currencyCode;

    public function __construct(
        string $environment,
        string $merchantPosId,
        string $signatureKey,
        string $oAuthClientId,
        string $oAuthClientSecret,
        string $currencyCode
    )
    {
        $this->environment = $environment;
        $this->merchantPosId = $merchantPosId;
        $this->signatureKey = $signatureKey;
        $this->oAuthClientId = $oAuthClientId;
        $this->oAuthClientSecret = $oAuthClientSecret;
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getMerchantPosId(): string
    {
        return $this->merchantPosId;
    }

    /**
     * @return string
     */
    public function getSignatureKey(): string
    {
        return $this->signatureKey;
    }

    /**
     * @return string
     */
    public function getOAuthClientId(): string
    {
        return $this->oAuthClientId;
    }

    /**
     * @return string
     */
    public function getOAuthClientSecret(): string
    {
        return $this->oAuthClientSecret;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

}