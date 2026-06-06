<?php

namespace controllers;

use services\UserService;
use services\TrainerService;

class AuthController
{
    private $userService;
    private $trainerService;

    public function __construct(UserService $userService, TrainerService $trainerService)
    {
        $this->userService = $userService;
        $this->trainerService = $trainerService;
    }

    public function register()
    {
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : null;

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirmPassword)) {
            header('Location: /kim/register?error=' . urlencode("All fields are required"));
            exit;
        }

        try {
            $this->userService->createUser($first_name, $last_name, $email, $password, $confirmPassword, $role);

            $user = $this->userService->getUserByEmail($email);

            $this->setSessionVariables($user);

            header('Location: /kim/profile/personal-info');
            exit;

        } catch (\Exception $ex) {
            $error_message = $ex->getMessage();

            header('Location: /kim/register?error=' . urlencode($error_message));
            exit;
        }
    }

    public function login()
    {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($email) || empty($password)) {
            header('Location: /kim/login?error=' . urlencode("Please enter both email and password"));
            exit;
        }

        try{
            $user = $this->userService->authenticate($email, $password); //authenticate va returna un obiect cu user daca e bun
            $this->setSessionVariables($user);
            header('Location: /kim/dashboard');
            exit;
        } catch(\Exception $ex) {
            $error_message = $ex->getMessage();
            header('Location: /kim/login?error=' . urlencode($error_message));
            exit;
        }
    }

    public function logout()
    {
        $_SESSION = [];

        // stergem cookies
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header('Location: /kim/login');
        exit;
    }

    /**
     * @param $user
     * @return void
     */
    private function setSessionVariables($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name . " " . $user->last_name;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_profile-picture'] = $user->profile_picture;

        if ($user->role == 'trainer') {
            $trainer = $this->trainerService->getTrainerByUserId($user->id);

            if ($trainer) {
                $_SESSION['trainer_id'] = $trainer->id;
            }
        }
    }
}