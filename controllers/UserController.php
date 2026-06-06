<?php

namespace controllers;

use finfo;
use services\UserService;

class UserController
{

    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function updateProfile(){
        if(!isset($_SESSION['user_id'])){
            header('Location: /kim/login?error=You have to be logged in to update your profile');
            exit;
        }
        $userId = $_SESSION['user_id'];

        $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $lastName  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email     = isset($_POST['email']) ? trim($_POST['email']) : '';

        $newAvatarName = null;

        $statusType = '';
        $message = '';

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
                $destinationPath = __DIR__ . '/../public/images/avatars/' . $newAvatarName;

                if(!move_uploaded_file($fileTmpPath, $destinationPath)){
                    throw new \Exception("Unable to upload file");
                }
            }

            $this->userService->updateUserProfile($userId, $firstName, $lastName, $email, $newAvatarName);

            $_SESSION['user_name'] = $firstName . ' ' . $lastName;

            if($newAvatarName != null){
                $_SESSION['user_profile-picture'] = $newAvatarName;
            }

            $statusType = 'success';
            $message = 'Your profile was successfully updated';
        } catch(\Exception $e){
            $statusType = 'error';
            $message = $e->getMessage();
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kim/dashboard';

        //daca in link avem deja un parametru sau nu
        $separator = (strpos($referer, '?') !== false) ? '&' : '?';

        // ?success=Mesaj+aici)
        $queryString = ($statusType !== '') ? $separator . $statusType . '=' . urlencode($message) : '';

        header('Location: ' . $referer . $queryString);
        exit;
    }

    public function changePassword(){
        if (!isset($_SESSION['user_id'])) {
            session_destroy();
            header('Location: /kim/login?error=' . urlencode("You must be logged in to change your password."));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $user = $this->userService->getUserById($userId);
        if(!$user){
            session_destroy();
            header('Location: /kim/login?error=' . urlencode("User not found."));
            exit;
        }
        $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            header('Location: /kim/profile/settings?error=' . urlencode("All fields are required."));
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            header('Location: /kim/profile/settings?error=' . urlencode("New passwords do not match."));
            exit;
        }

        try{
            $this->userService->updateUserPassword($userId, $currentPassword, $newPassword);
            header('Location: /kim/profile/settings?success=' . urlencode("Your password has been updated."));
            exit;
        } catch(\Exception $ex) {
            $error_message = $ex->getMessage();
            header('Location: /kim/profile/settings?error=' . urlencode($error_message));
        }
    }

    public function deleteUser(){
        $statusType = '';
        $message = '';

        try{
            if (!isset($_SESSION['user_id'])) {
                throw new \Exception("You are unauthorized.");
            }

            $userIdToDelete = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
            if (empty($userIdToDelete)) {
                throw new \Exception('User ID cannot be empty.');
            }

            if($userIdToDelete == $_SESSION['user_id'] && $_SESSION['user_role'] === 'admin'){
                throw new \Exception('An admin cannot delete their own account.');
            }

            if($_SESSION['user_role'] !== 'admin'){
                if($userIdToDelete != $_SESSION['user_id']){
                    throw new \Exception('You are not allowed to delete this account.');
                }
            }

            $this->userService->deleteUser($userIdToDelete);
            $message = 'Your account was successfully deleted';
            session_destroy();

            header('Location: /kim/register?success=' . urlencode($message));
            exit;
        } catch(\Exception $ex) {
            $statusType = 'error';
            $message = $ex->getMessage();
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