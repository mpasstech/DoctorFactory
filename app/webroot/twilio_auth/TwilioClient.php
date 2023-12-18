<?php

namespace App\Services\Twilio;

use \Twilio\Rest\Client;

/**
 * Class TwilioClient
 */
class TwilioClient
{
    /**
     * @var string
     */
    protected $accountSid = 'ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

    /**
     * @var string
     */
    protected $authToken = 'your_auth_token';

    /**
     * @var \Twilio\Rest\Client
     */
    protected $client;

    /**
     * TwilioClient constructor.
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct()
    {
        $this->client = new Client($this->accountSid, $this->authToken);
    }

    /**
     * @see https://www.twilio.com/docs/iam/api/account?code-sample=code-create-account&code-language=PHP&code-sdk-version=5.x#fetch-an-account-resource
     * @return \Twilio\Rest\Api\V2010\AccountInstance
     */
    public function getAccountDetails()
    {
        return $this->client->api->v2010->accounts($this->accountSid)
            ->fetch();
    }

    /**
     * @return float
     * @throws \Exception
     */
    public function getBalance()
    {
        $accountDetails = $this->getAccountDetails();

        if (empty($accountDetails->subresourceUris['balance'])) {
            throw new \Exception('Cannot get account balance subsource URI');
        }

        $balanceUrl = 'https://api.twilio.com' . $accountDetails->subresourceUris['balance'];

        $balanceResponse = $this->client->request('GET', $balanceUrl);
        $responseContent = $balanceResponse->getContent();

        if (!isset($responseContent['balance'])) {
            throw new \Exception('Cannot get account balance details');
        }

        return round($responseContent['balance'], 2);
    }
}