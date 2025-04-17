<?php
namespace App\Services;

use Twilio\Rest\Client;
use GuzzleHttp\Client as GuzzleClient;
use Twilio\Http\GuzzleClient as TwilioGuzzleClient;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendOtp($phoneNumber, $otp)
    {
        $message = "Your OTP code is: $otp";

        // Guzzle Client ko SSL verification disable karne ke liye configure karo
        $guzzle = new GuzzleClient(['verify' => false]);
        $httpClient = new TwilioGuzzleClient($guzzle);
        
        // Twilio Client initialize without passing HTTP client in constructor
        $twilioClient = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        
        // Manually HTTP client set karo (correct method)
        $twilioClient->setHttpClient($httpClient);

        return $twilioClient->messages->create($phoneNumber, [
            'from' => env('TWILIO_PHONE'),
            'body' => $message
        ]);
    }
}
