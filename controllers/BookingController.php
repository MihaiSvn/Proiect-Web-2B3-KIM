<?php

namespace controllers;

class BookingController
{
    private $bookingService;

    public function __construct($bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function cancel()
    {
        if (!isset($_POST['user_id'])) {
            header('Location: /kim/login?error=You must be logged in');
            exit;
        }

        $statusType = '';
        $message = '';

        if (isset($_POST['session_id'])) {
            $sessionId = (int)$_POST['session_id'];
            $userId = $_SESSION['user_id'];

            try {
                $this->bookingService->cancelUserBooking($userId, $sessionId);

                $statusType = 'success';
                $message = 'Your booking was successfully canceled.';

            } catch (\Exception $e) {
                $statusType = 'error';
                $message = $e->getMessage();
            }
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kim/dashboard';

        //daca in link avem deja un parametru sau nu
        $separator = (strpos($referer, '?') !== false) ? '&' : '?';

        // ?success=Mesaj+aici)
        $queryString = ($statusType !== '') ? $separator . $statusType . '=' . urlencode($message) : '';

        header('Location: ' . $referer . $queryString);
        exit;
    }
}