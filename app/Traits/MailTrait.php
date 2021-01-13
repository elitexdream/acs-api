<?php

namespace App\Traits;

use SendGrid\Mail\Mail;

trait MailTrait
{
    public function sendRegistrationMail($user, $password)
	{
        $email = new Mail();
        $email->setFrom(config('services.sendgrid.from_email'), "MachineCDN");
        $email->setSubject("Welcome to MachineCDN ACS User");
        $email->addTo(
            $user->email,
            $user->name,
            [
                "subject" => "Subject 1",
                "name" => "Example User 1",
                "city" => "Denver",
                "password" => $password
            ],
            0
        );
        $email->setTemplateId(config('services.sendgrid.template_id'));
        $sendgrid = new \SendGrid(config('services.sendgrid.api_key'));
        try {
            $response = $sendgrid->send($email);
            return $response;
        } catch (Exception $e) {
            echo 'Caught exception: '.  $e->getMessage(). "\n";
        }
	}
}
