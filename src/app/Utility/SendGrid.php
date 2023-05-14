<?php

namespace App\Utility;

use SendGrid\Mail\Mail;
use SendGrid\Response;

class SendGrid {

    public static function send(string $to, string $subject, string $message): string
    {
        $email = new Mail();
        $email->setFrom(config('mail.from.address'), config('mail.from.name'));
        $email->addTo($to);
        $email->setSubject($subject);
        $email->addContent('text/plain', $message);
        $sendgrid = new \SendGrid(config('services.sendgrid.key'));
        $response = $sendgrid->send($email);
        return $response->statusCode() . ": " . ($response->body() ?? 'ok');
    }

}
