<?php
/**
 * user repository
 */
class UserRepository
{

    /**
     * @param $email
     * @param $password
     * @param $branchIds - array of branchIds to which user needs to be allocated
     * @return bool|User
     */


    public function createAdmin($email, $mobile, $password, $schoolCode)
    {
        $roles = array(Role::USER_ROLE_ADMIN, Role::USER_ROLE_EDITOR);
        return $this->createUser($email, $mobile, $password, $schoolCode, $roles);
    }

    public function createUser($email, $mobile, $password, $schoolCode = null, array $userRoles)
    {
        if ($schoolCode == null) {
            throw new InvalidArgumentException("Empty School ID");
        }
        $school = School::where_code($schoolCode)->get();
        if (empty($school))
            throw new InvalidArgumentException("Invalid School ID $schoolCode");

        $user = new User();
//        if ($this->validateEmail($email))
//            throw new InvalidArgumentException("Email Exists Already");

        $user->email = $email;
        $user->mobile = $mobile;
        $user->password = Hash::make($password);
        $user->emailVerificationCode = Str::random(64, 'alpha');
        $user->mobileVerificationCode = mt_rand(100000, 999999);
        $user->schoolId = $school[0]->id;
        $rolesIds = role::where_in('name', $userRoles)->get('id');
        $idS = array();
        foreach ($rolesIds as $roleId) {
            $idS[] = $roleId->id;
        }
        try {
            DB::connection()->pdo->beginTransaction();
            $user->save();
            $user->roles()->sync($idS);
            DB::connection()->pdo->commit();
        } catch (PDOException $e) {
            //rollback if any error while bulk insertion
            DB::connection()->pdo->rollBack();
            Log::exception($e);
            return false;
        }
        catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $user;
    }

    public function activate($code = NULL)
    {
        if ($code == NULL) {
            throw new InvalidArgumentException("Empty ID");
        }
        $user = User::where_emailVerificationCode($code)->get();

        if (empty($user))
            throw new InvalidArgumentException("User Not Found");

        if (count($user) == 1) {
            $updateData = array('emailVerificationCode' => NULL,
            );
            try {
                User::update($user[0]->id, $updateData);
            } catch (Exception $e) {
                Log::exception($e);
                return false;
            }
            return true;
        }
        return false;
    }

    public function validateEmail($email)
    {

        $user = User::where_email($email)->get();
        if (!empty($user))
            return false;
        return true;
    }


    public function deactivate($id = NULL)
    {
        $data = array(
            'isDeactivated' => 1,
            'reactivateCode' => Str::random(64, 'alpha')
        );

        try {
            User::update($id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return true;

    }

    public function deleted($id)
    {
        $data = array(
            'isDeleted' => 1
        );
        try {
            User::update($id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return true;
    }

    public function login($email)
    {
        $user = User::where_Email($email)->get();
        if (empty($user)) {
            throw new InvalidArgumentException("No user found");
        }

        if ($user[0]->isverified == 1) {
            if ($user[0]->isdeleted == 0 && $user[0]->isdeactivated == 0) {
                return true;
            } else if ($user[0]->isdeleted == 1)

                return false;
            else if ($user[0]->isdeactivated == 1)

                return false;
        }

        return false;
    }

    public function setForgotActivationCode($email)
    {
        $user = User::where_email($email)->first();
        if ($user == NULL) {
            throw new InvalidArgumentException("No user with email id $email");
        }

        $forgotten_password_code = Str::random(64, 'alpha');
        $data = array(
            'forgottenPasswordCode' => $forgotten_password_code
        );
        try {
            User::update($user->id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return User::find($user->id);
    }

    public function forgotten_password_complete($code = NULL)
    {
        if ($code == NULL) {
            throw new InvalidArgumentException("Empty Forgotten Password Code");
        }

        $user = User::where_forgottenPasswordCode($code)->get();

        if (empty($user))
            throw new InvalidArgumentException("User Not Found");

        if (count($user) == 1) {
            $updateData = array('forgottenPasswordCode' => NULL
            );
            try {
                User::update($user[0]->id, $updateData);
            } catch (Exception $e) {
                Log::exception($e);
                return false;
            }
            return $user;
        }
        return false;
    }

    public function setNewPassword($email, $id, $newPassword)
    {
        $user = User::where_id_and_email($id, $email)->first();

        if ($user == NULL)
            throw new InvalidArgumentException("User Not Found");

        $data = array(
            'password' => Hash::make($newPassword)
        );
        try {
            User::update($user->id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return User::find($user->id);
    }

    public function change_password($id, $old_password, $new_password)
    {
        if (empty($old_password) || empty($new_password)) {
            throw new InvalidArgumentException("Empty id or Old Password or New Password");
        }
        $user = User::where_id($id)->get();
        if (empty($user))
            return false;
        if (Hash::check($old_password, $user[0]->password)) {
            $user = User::find($id);
            $user->password = Hash::make($new_password);
            $user->save();
            return $user;
        }
        return false;
    }

    public function restoreAccount($reactivateCode)
    {
        if ($reactivateCode == NULL) {
            throw new InvalidArgumentException("Empty Reactivate Code");
        }

        $user = User::where_reactivateCode($reactivateCode)->first();

        if (empty($user))
            throw new InvalidArgumentException("User Not Found");

        if (count($user) == 1) {

            $updateData = array('reactivateCode' => NULL,
                'isDeactivated' => 0
            );
            try {
                User::update($user->id, $updateData);
            } catch (Exception $e) {
                Log::exception($e);
                return false;
            }
            return User::find($user->id);
        }
        return false;

    }

    public function send_new_password_to_mobile($email, $mobile)
    {
        $user = User::where_email($email)->where_mobile($mobile)->first();

        if ($user == NULL)
            throw new InvalidArgumentException("User Not Found");

        $password = mt_rand(100000, 999999);
        $updateData = array(
            'password' => Hash::make($password)
        );
        try {
            User::update($user->id, $updateData);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return array('user' => User::find($user->id), 'password' => $password);
    }

    public function verifyMobile($id, $mobileVerificationCode)
    {
        if ($mobileVerificationCode == NULL) {
            throw new InvalidArgumentException("Empty Mobile Verification Code");
        }

        $user = User::where_id($id)->where_mobileVerificationCode($mobileVerificationCode)->first();

        if ($user == NULL)
            throw new InvalidArgumentException("User Not Found");

        $updateData = array('mobileVerificationCode' => NULL,
            'isVerified' => true
        );
        try {
            User::update($user->id, $updateData);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return true;
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function updateMobile($id, $mobile)
    {
        $user = User::where_id($id)->get();
        if (empty($user))
            throw new InvalidArgumentException("User Not Found");
        $user = User::find($id);
        $user->mobile = $mobile;
        $user->mobileVerificationCode = mt_rand(100000, 999999);
        $user->isVerified = false;
        try {
            $user->save();

        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $user;

    }

    public function updateEmail($id, $email)
    {
        $user = User::where_id($id)->get();
        $user = User::find($id);
        $user->email = $email;
        $user->emailVerificationCode = Str::random(64, 'alpha');
        try {
            $user->save();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $user;
    }
}

