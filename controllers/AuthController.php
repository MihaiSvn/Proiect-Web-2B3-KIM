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
        $role = isset($_POST['role']) ? $_POST['role'] : null;

        try {
            $this->userService->createUser($first_name, $last_name, $email, $password, $role);

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