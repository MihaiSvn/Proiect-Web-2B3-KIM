<?php

namespace api\auth;

use services\TrainerService;
use services\UserService;

class AuthApiController
{
    public function login(){

        $data = json_decode(file_get_contents("php://input"),true);
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';

        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'All fields are required.']);
            exit;
        }

        $userService = new UserService();
        try{
            $user = $userService->authenticate($email, $password); //authenticate va returna un obiect cu user daca e bun
            $this->setSessionVariables($user);

            http_response_code(200);
            echo json_encode(['status'=>'success', 'redirect' => '/kim/dashboard']);
            exit;
        } catch(\Exception $ex) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }

    public function logout(){
        session_destroy();
        http_response_code(200);
        echo json_encode(['status' => 'success', 'redirect' => '/kim/login']);
    }

    public function register(){
        $data = json_decode(file_get_contents("php://input"),true);
        $first_name = isset($data['first_name']) ? $data['first_name'] : '';
        $last_name = isset($data['last_name']) ? $data['last_name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : '';
        $role = isset($data['role']) ? $data['role'] : null;

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'All fields are required.']);
            exit;
        }

        $userService = new UserService();
        try {
            $userService->createUser($first_name, $last_name, $email, $password, $confirmPassword, $role);

            $user = $userService->getUserByEmail($email);

            $this->setSessionVariables($user);

            http_response_code(200);
            echo json_encode(['status'=>'success', 'redirect' => '/kim/profile']);
            exit;

        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }

    private function setSessionVariables($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name . " " . $user->last_name;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_profile-picture'] = $user->profile_picture;

        if ($user->role == 'trainer') {
            $trainerService = new TrainerService();
            $trainer = $trainerService->getTrainerByUserId($user->id);

            if ($trainer) {
                $_SESSION['trainer_id'] = $trainer->id;
            }
        }
    }
}