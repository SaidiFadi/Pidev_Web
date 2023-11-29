<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwilioSMSService;

class TestSmsController extends AbstractController
{

    #[Route('/test-sms', name: 'test_sms')]
    public function testSMS(TwilioSMSService $twilioSMSService): Response
    {
        // Replace 'YourPhoneNumber' with the actual phone number you want to send the SMS to
        $phoneNumber = '+21650227451'; 

        $message = 'Hello from Twilio! This is a test SMS message.';

        // Send SMS
        $twilioSMSService->sendSMS($phoneNumber, $message);

        
        return new Response('Test SMS sent successfully!');
    }
}
