<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoTransport extends AbstractTransport
{
    protected $key;

    public function __construct($key)
    {
        parent::__construct();
        $this->key = $key;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $to = [];
        foreach ($email->getTo() as $address) {
            $to[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: null
            ];
        }

        $senderName = config('mail.from.name');
        $senderEmail = config('mail.from.address');
        
        if ($email->getFrom()) {
            $senderEmail = $email->getFrom()[0]->getAddress();
            $senderName = $email->getFrom()[0]->getName() ?: $senderName;
        }

        $payload = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail,
            ],
            'to' => $to,
            'subject' => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody(),
        ];

        if ($email->getTextBody()) {
            $payload['textContent'] = $email->getTextBody();
        }

        // Bypassing SSL verification for local testing, standard for HTTPS requests in local dev
        $response = Http::withoutVerifying()
            ->withHeaders([
                'api-key' => $this->key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->failed()) {
            Log::error('Brevo API Mail sending failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Brevo API Mail send failed: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
