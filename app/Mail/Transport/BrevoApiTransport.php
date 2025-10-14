<?php

declare(strict_types=1);

namespace App\Mail\Transport;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

final class BrevoApiTransport extends AbstractTransport
{
    public function __construct(
        private readonly string $apiKey
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey);
        $apiInstance = new TransactionalEmailsApi(new Client, $config);

        $sendSmtpEmail = new SendSmtpEmail;

        // Set sender
        $from = $email->getFrom();
        if (count($from) > 0) {
            $fromAddress = $from[0];
            $sendSmtpEmail->setSender([
                'email' => $fromAddress->getAddress(),
                'name' => $fromAddress->getName() ?? config('mail.from.name'),
            ]);
        }

        // Set recipients
        $to = array_map(function (Address $address) {
            return [
                'email' => $address->getAddress(),
                'name' => $address->getName(),
            ];
        }, $email->getTo());
        $sendSmtpEmail->setTo($to);

        // Set CC
        if (count($email->getCc()) > 0) {
            $cc = array_map(function (Address $address) {
                return [
                    'email' => $address->getAddress(),
                    'name' => $address->getName(),
                ];
            }, $email->getCc());
            $sendSmtpEmail->setCc($cc);
        }

        // Set BCC
        if (count($email->getBcc()) > 0) {
            $bcc = array_map(function (Address $address) {
                return [
                    'email' => $address->getAddress(),
                    'name' => $address->getName(),
                ];
            }, $email->getBcc());
            $sendSmtpEmail->setBcc($bcc);
        }

        // Set subject
        $sendSmtpEmail->setSubject($email->getSubject());

        // Set reply-to
        $replyTo = $email->getReplyTo();
        if (count($replyTo) > 0) {
            $replyToAddress = $replyTo[0];
            $sendSmtpEmail->setReplyTo([
                'email' => $replyToAddress->getAddress(),
                'name' => $replyToAddress->getName(),
            ]);
        }

        // Set HTML and text content
        if ($email->getHtmlBody()) {
            $sendSmtpEmail->setHtmlContent($email->getHtmlBody());
        }

        if ($email->getTextBody()) {
            $sendSmtpEmail->setTextContent($email->getTextBody());
        }

        // Send email via Brevo API
        $apiInstance->sendTransacEmail($sendSmtpEmail);
    }

    public function __toString(): string
    {
        return 'brevo+api';
    }
}
