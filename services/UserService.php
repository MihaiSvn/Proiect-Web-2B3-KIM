<?php

namespace services;

use models\User;
class UserService
{
    public function getUserByEmail($email){
        return User::findByEmail($email);
    }

    public function getUserById($id){
        return User::findById($id);
    }

    public function getAllMembers(){
        return User::findAllMembers();
    }

    public function createUser($first_name, $last_name, $email, $password, $confirm_password, $role){

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
            throw new \Exception("All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email address is not valid");
        }

        if($this->getUserByEmail($email)){
            throw new \Exception("User with email $email already exists");
        }

        if(strlen($password) < 6){
            throw new \Exception("Password must be at least 6 characters");
        }

        if($password != $confirm_password){
            throw new \Exception("Passwords do not match");
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $success = User::create($first_name, $last_name, $email, $password_hash, $role);

        if(!$success){
            throw new \Exception("Unable to create user");
        }

        $user = User::findByEmail($email);
        $notificationService = new NotificationService();
        $notificationService->createNotification(
            $user->id,
            '🎉 Welcome to KIM Fitness!',
            "Hi, {$first_name}! Your account has been created successfully. We're excited to start this wellness journey with you."
        );

        $env = parse_ini_file(__DIR__ . "/../.env");
        $baseUrl = $env['APP_URL'] ?? 'http://localhost/kim';

        $subject = 'Welcome to KIM Fitness!';
        $title   = 'Welcome to the Family!';
        $text    = "Hi, <b>{$first_name}</b>,<br/><br/>
                We are absolutely thrilled to have you on board! Your account has been successfully created.<br/><br/>
                Get ready to transform your body and mind with our elite training sessions and expert trainers.";

        $btnText = 'Log In to Your Account';
        $btnUrl  = $baseUrl . '/login';

        \services\MailService::sendEmail($email, $subject, $title, $text, $btnText, $btnUrl);

        return true;
    }

    public function authenticate($email, $password){
        if (empty($email) || empty($password)) {
            throw new \Exception("Please enter all fields");
        }

        $user = User::findByEmailWithPassword($email);
        
        if (!$user || !password_verify($password, $user->password_hash)) {
            throw new \Exception("Invalid email or password");
        }

        return $user;
    }


    public function getActiveMembersTrend(){
        $currentPeriodCount = User::getNewMembersCountCurrentPeriod();
        $previousPeriodCount = User::getNewMembersCountPreviousPeriod();


        if ($previousPeriodCount === 0) {
            return $currentPeriodCount > 0 ? 100 : 0;
        }

        return round((($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100);
    }

    public function getAllUsersCount(){
        return User::getAllUsersCount();
    }

    public function getActiveMembersStats(){
        $trend = $this->getActiveMembersTrend();
        $displayValue = $this->getAllUsersCount();

        return [
            'displayValue' => $displayValue,
            'trend' => $trend
        ];
    }

    public function getActiveSubscriptionsByUserId($user_id){

        $activeSubscriptions = User::findActiveSubscriptionsByUserId($user_id);

        $result = [
            'can_book_anything' => false, //devine true daca are o sesiune la all
            'sessions_by_type' => [
                'fitness' => 0,
                'physiotherapy' => 0,
                'strength' => 0,
                'all' => 0
            ]
        ];

        if (empty($activeSubscriptions)) {
            return $result;
        }

        foreach ($activeSubscriptions as $sub) {
            $result['sessions_by_type'][$sub->type] += $sub->sessions_left;

            if($sub->sessions_left > 0 && $sub->type == 'all'){
                $result['can_book_anything'] = true;
            }
        }

        return $result;
    }

    public function updateUserProfile($userId, $first_name, $last_name, $email, $newAvatarName = null){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception("Email address is not valid");
        }

        //trb sa verificam daca exista deja utilizator cu acest mail
        $existingUser = $this->getUserByEmail($email);

        if($existingUser && $existingUser->id != $userId){
            throw new \Exception("User with email $email already exists");
        }

        $success = User::updateProfile($userId, $first_name, $last_name, $email, $newAvatarName);

        if(!$success){
            throw new \Exception("Unable to update user");
        }
        return true;
    }

    public function getWithPasswordById($user_id){
        return User::findWithPasswordById($user_id);
    }
    public function updateUserPassword($userId, $oldPassword, $newPassword){
        $user = $this->getWithPasswordById($userId);
        if(!$user || !password_verify($oldPassword, $user->password_hash)){
            throw new \Exception("Old password is incorrect");
        }

        if(strlen($newPassword) < 6){
            throw new \Exception("Password must be at least 6 characters");
        }

        $new_password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        if(password_verify($newPassword, $user->password_hash)){
            throw new \Exception("New password is the same as the old password");
        }
        $success = User::updatePassword($userId, $new_password_hash);
        if(!$success){
            throw new \Exception("Unable to update user");
        }

        $env = parse_ini_file(__DIR__ . "/../.env");
        $baseUrl = $env['APP_URL'] ?? 'http://localhost/kim';

        $subject = 'Your Password Has Been Changed - KIM Fitness';
        $title   = 'Password Updated Successfully';

        $text    = "Hi <b>{$user->first_name}</b>,<br/><br/>
            This is a quick confirmation that the password for your KIM Fitness account has just been successfully changed.<br/><br/>
            If you made this change, you're all set! <br/><br/>
            <span style='color: #d9534f; font-size: 14px;'><i>If you did <b>not</b> change your password, please contact our administrator immediately at <b>kimadmin123@gmail.com</b>.</i></span>";

        $btnText = 'Log In to Your Account';
        $btnUrl  = $baseUrl . '/login';

        MailService::sendEmail($user->email, $subject, $title, $text, $btnText, $btnUrl);

        return true;
    }

    public function deleteUser($userId){
        $user = $this->getUserById($userId);
        if(!$user){
            throw new \Exception("User not found");
        }

        $success = User::deleteUser($userId);
        if(!$success){
            throw new \Exception("Unable to delete user");
        }

        $env = parse_ini_file(__DIR__ . "/../.env");
        $baseUrl = $env['APP_URL'] ?? 'http://localhost/kim';

        $subject = 'Your Account Has Been Deleted - KIM Fitness';
        $title   = 'Account Deleted';

        $text    = "Hi <b>{$user->first_name}</b>,<br/><br/>
        This is a confirmation that your KIM Fitness account has been successfully deleted. We're very sorry to see you go!<br/><br/>
        If you made this choice, no further action is needed and we wish you all the best on your fitness journey.<br/><br/>
        <span style='color: #d9534f; font-size: 14px;'><i>If you did <b>not</b> delete your account, please contact our administrator immediately at <b>kimadmin123@gmail.com</b>.</i></span>";


        MailService::sendEmail($user->email, $subject, $title, $text);

        return true;
    }
}