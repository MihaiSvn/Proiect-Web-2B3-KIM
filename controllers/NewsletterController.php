<?php

namespace controllers;

class NewsletterController
{
    public function subscribe()
    {
        $email = isset($_POST['email'])
            ? trim($_POST['email'])
            : '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            header(
                'Location: /kim/home?error=' .
                urlencode('Please enter a valid email address')
            );

            exit;
        }

        header(
            'Location: /kim/home?success=' .
            urlencode('Thank you for subscribing!:)')
        );

        exit;
    }
}