
<?php
use Endroid\QrCode\Builder\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class YourController extends AbstractController
{
    public function generateQRCodeAction(): Response
    {
        // Use the default builder to generate a QR code
        $result = Builder::createDefault()->build();

        // Get the data URI of the QR code image
        $qrCodeDataUri = $result->getDataUri();

        // Render a template with the QR code data URI
        return $this->render('your_template.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
    }
}
