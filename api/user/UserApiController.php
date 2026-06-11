<?php

namespace api\user;

use services\UserService;

class UserApiController
{
    public function updateProfile(){

        $userId = $_SESSION['user_id'];

        $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $lastName  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email     = isset($_POST['email']) ? trim($_POST['email']) : '';

        $newAvatarName = null;

        try{
            //e ok sa fie err no file in caz de nu si a dorit sa updateze poza
            if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE){
                //daca apare alta eroare dam throw
                if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
                    throw new \Exception("Error at uploading file");
                }

                $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
                $fileName    = $_FILES['profile_picture']['name'];
                $fileSize    = $_FILES['profile_picture']['size'];

                //validam marimea
                if ($fileSize > 5242880) {
                    throw new \Exception("File too large. Maximum allowed size: 5MB");
                }

                //validare extensie
                $allowedExtensions = ["jpeg", "jpg", "png"];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if(!in_array($fileExtension, $allowedExtensions)){
                    throw new \Exception("Invalid file extension. Only JPG and PNG files are allowed");
                }


                //VERIFICAM SIGUR DACA FISIERUL TRIMIS E O POZA

                //deschid resura finfo
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                //citim mime type ul
                $realMimeType = $finfo->file($fileTmpPath);

                $allowedMimeTypes = ['image/jpeg', 'image/png'];

                if(!in_array($realMimeType, $allowedMimeTypes)){
                    throw new \Exception("Invalid file type. Only JPG and PNG files are allowed");
                }

                //user_1_ewq1.jpg
                $newAvatarName = 'user_' . $userId .  '_' . uniqid() .  '.' . $fileExtension;
                // public/images/avatars/user_1_ewq1.jpg
                $destinationPath = __DIR__ . '/../../public/images/avatars/' . $newAvatarName;

                if(!move_uploaded_file($fileTmpPath, $destinationPath)){
                    throw new \Exception("Unable to upload file");
                }
            }

            $userService = new UserService();

            $userService->updateUserProfile($userId, $firstName, $lastName, $email, $newAvatarName);

            $_SESSION['user_name'] = $firstName . ' ' . $lastName;

            if($newAvatarName != null){
                $_SESSION['user_profile-picture'] = $newAvatarName;
            }

            http_response_code(200);
            echo json_encode(["status" => "success", "message" => 'Your profile has been updated!']);
        } catch(\Exception $e){
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function changePassword(){
        //verificarea se va face din middleware
        $userId = $_SESSION['user_id'];

        $data = json_decode(file_get_contents('php://input'), true); //luam body ul de post

        $userService = new \services\UserService();

        $user = $userService->getUserById($userId);

        if(!$user){
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "User not found."]);
            exit;
        }
        $currentPassword = isset($data['current_password']) ? $data['current_password'] : '';
        $newPassword = isset($data['new_password']) ? $data['new_password'] : '';
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "All fields are required."]);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "New passwords do not match."]);
            exit;
        }

        try{
            $userService->updateUserPassword($userId, $currentPassword, $newPassword);

            http_response_code(200);
            echo json_encode(["status" => "success", 'message' => "Password successfully changed."]);
        } catch(\Exception $ex) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $ex->getMessage()]);
        }
    }

    public function deleteUser(){
        $statusType = '';
        $message = '';

        $currentUserId = $_SESSION['user_id'];
        $currentUserRole = $_SESSION['user_role'];

        $data = json_decode(file_get_contents('php://input'), true);
        $userIdToDelete = isset($data['user_id']) ? trim($data['user_id']) : '';

        if (empty($userIdToDelete)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "User ID cannot be empty."]);
            exit;
        }

        if ($userIdToDelete == $currentUserId && $currentUserRole === 'trainer') {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "A trainer cannot delete their own account."]);
            exit;
        }

        if ($currentUserRole !== 'admin' && $userIdToDelete != $currentUserId) {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "You are not allowed to delete this account."]);
            exit;
        }

        if ($currentUserRole !== 'admin' && $userIdToDelete != $currentUserId) {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "You are not allowed to delete this account."]);
            exit;
        }

        try {
            $userService = new \services\UserService();
            $userService->deleteUser($userIdToDelete);


            if ($userIdToDelete == $currentUserId) {
                session_destroy();
            }

            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Account successfully deleted."]);

        } catch (\Exception $ex) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $ex->getMessage()]);
        }

    }

    public function getUser(){
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

        if ($_SESSION['user_role'] !== 'admin' && $userId != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized access.']);
            exit;
        }
        $userService = new UserService();
        $user= $userService->getUserById($userId);
        if(!$user){
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            exit;
        }

        http_response_code(200);
        echo json_encode(['user' => $user]);
    }

    /*
     *
     *
     *
     * EXEMPLU RASPUNS LA GET ALL USERS
     *
                 * {
                "status": "success",
                "admin": [
                    {
                        "id": 1,
                        "first_name": "Kim",
                        "last_name": "Admin",
                        "email": "kimadmin123@gmail.com",
                        "role": "admin",
                        "joined_at": "2023-01-10 08:30:00",
                        "profile_picture": "default_admin.png",
                        "overall_status": "active",
                        "memberships": []
                    }
                ],
                "trainer": [
                    {
                        "id": 2,
                        "first_name": "Marcus",
                        "last_name": "Chen",
                        "email": "marcus.chen@serenity.com",
                        "role": "trainer",
                        "joined_at": "2023-10-10 14:15:00",
                        "profile_picture": "marcus_avatar.jpg",
                        "overall_status": "active",
                        "memberships": []
                    }
                ],
                "member": [
                    {
                        "id": 3,
                        "first_name": "Elena",
                        "last_name": "Martinez",
                        "email": "elena.martinez@email.com",
                        "role": "member",
                        "joined_at": "2024-01-15 09:20:00",
                        "profile_picture": "elena_avatar.jpg",
                        "overall_status": "active",
                        "memberships": [
                            {
                                "name": "Fitness Premium",
                                "type": "fitness",
                                "status": "active",
                                "sessions_left": 12,
                                "suspending_days_left": 14
                            },
                            {
                                "name": "Strength Elite",
                                "type": "strength",
                                "status": "active",
                                "sessions_left": 8,
                                "suspending_days_left": 7
                            }
                        ]
                    },
                    {
                        "id": 4,
                        "first_name": "Sarah",
                        "last_name": "Williams",
                        "email": "sarah.williams@email.com",
                        "role": "member",
                        "joined_at": "2024-03-05 11:45:00",
                        "profile_picture": null,
                        "overall_status": "suspended",
                        "memberships": [
                            {
                                "name": "Full Access",
                                "type": "all",
                                "status": "suspended",
                                "sessions_left": 20,
                                "suspending_days_left": 0
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "first_name": "Michael",
                        "last_name": "Scott",
                        "email": "michael.scott@email.com",
                        "role": "member",
                        "joined_at": "2024-06-10 10:00:00",
                        "profile_picture": null,
                        "overall_status": "active",
                        "memberships": []
                    }
                ]
            }
     */

    public function getAllUsers()
    {
        $userService = new UserService();

        try{
            $categorizedUsers = $userService->getAllUsersCategorized();

            $response = array_merge(['status' => 'success'], $categorizedUsers);

            http_response_code(200);
            echo json_encode($response);
            exit;
        } catch (\Exception $e){
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'An error occurred while fetching users: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public function create(){
        $data = json_decode(file_get_contents("php://input"),true);
        $first_name = isset($data['first_name']) ? $data['first_name'] : '';
        $last_name = isset($data['last_name']) ? $data['last_name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : '';
        $role = isset($data['role']) ? $data['role'] : null;

        $specialization = isset($data['specialization']) ? $data['specialization'] : null;



        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'All fields are required.']);
            exit;
        }

        $userService = new UserService();
        try {
            $userService->createUser($first_name, $last_name, $email, $password, $confirmPassword, $role, $specialization);

            $user = $userService->getUserByEmail($email);

            http_response_code(200);
            echo json_encode(['status'=>'success', 'message' => 'User successfully created']);
            exit;

        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }


    public function update() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = isset($data['user_id']) ? $data['user_id'] : null;
        $first_name = isset($data['first_name']) ? $data['first_name'] : '';
        $last_name = isset($data['last_name']) ? $data['last_name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $role = isset($data['role']) ? $data['role'] : '';
        $specialization = isset($data['specialization']) ? $data['specialization'] : null;

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'User ID is missing.']);
            exit;
        }

        $userService = new UserService();

        try {
            $userService->updateUser($id, $first_name, $last_name, $email, $role, $specialization);

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'User successfully updated.']);
            exit;

        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }

}
