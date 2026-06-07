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

        if ($userIdToDelete == $currentUserId && $currentUserRole === 'admin') {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "An admin cannot delete their own account."]);
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
}