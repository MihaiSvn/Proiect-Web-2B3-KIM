<?php

namespace controllers;

use services\UserService;

class AuthController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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

            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->first_name . " " . $user->last_name;
            $_SESSION['user_role'] = $user->role;
            $_SESSION['user_profile-picture'] = $user->profile_picture;

            header('Location: /kim/home');
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
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->first_name . " " . $user->last_name;
            $_SESSION['user_role'] = $user->role;

            header('Location: /kim/home');
            exit;
        } catch(\Exception $ex) {
            $error_message = $ex->getMessage();
            header('Location: /kim/login?error=' . urlencode($error_message));
            exit;
        }
    }
}