<?php

namespace controllers;

use services\SessionService;

class SessionController
{
    private $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function cancel()
    {

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: /kim/login?error=Unauthorized');
            exit;
        }

        $userRole = $_SESSION['user_role'];

        if ($userRole !== 'trainer' && $userRole !== 'admin') {
            header('Location: /kim/dashboard?error=You do not have permission.');
            exit;
        }

        if (!isset($_SESSION['trainer_id'])) {
            header('Location: /kim/login?error=You must be a trainer to access this');
            exit;
        }

        $statusType = '';
        $message = '';

        if (isset($_POST['session_id'])) {
            $trainerId = isset($_SESSION['trainer_id']) ? $_SESSION['trainer_id'] : null;

            $sessionId = $_POST['session_id'];
            try {
                $this->sessionService->cancelSession($sessionId, $userRole, $trainerId);
                $statusType = 'success';
                $message = 'Session cancelled';

            } catch (\Exception $ex) {
                $statusType = 'error';
                $message = $ex->getMessage();
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