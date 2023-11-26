<?php
namespace App\Service;

use Twilio\Rest\Client;
class TwilioSMSService
{
    private $accountSid;
    private $authToken;
    private $fromNumber;

    public function __construct(string $accountSid, string $authToken, string $fromNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->fromNumber = $fromNumber;
    }

    public function sendSMS(string $to, string $message): void
    {
        $twilio = new Client($this->accountSid, $this->authToken);

        // Use $this->fromNumber as the sender number
        $twilio->messages->create(
            $to,
            [
                'from' => $this->fromNumber,
                'body' => $message,
            ]
        );
    }
}