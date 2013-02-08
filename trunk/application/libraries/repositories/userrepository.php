<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 23/1/13
 * Time: 7:23 AM
 * To change this template use File | Settings | File Templates.
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

        return $this->createUser($email, $mobile, $password, $schoolCode);
    }

    public function addAdminUserRole($userId)
    {
        $role = Role::USER_ROLE_ADMIN;
        return $this->addUserToRole($userId, $role);

    }

    public function createUser($email, $mobile, $password, $schoolCode = null)
    {
        if ($schoolCode == null) {
            throw new InvalidArgumentException("Empty School ID");
        }

        $school = School::where_code($schoolCode)->get();


        if (empty($school))
            throw new InvalidArgumentException("Invalid School ID $schoolCode");

        $user = new User();
        if ($this->validateEmail($email))
            throw new InvalidArgumentException("Email Exists Already");

        $user->email = $email;
        $user->mobile = $mobile;
        $user->password = Hash::make($password);
        $user->emailVerificationCode = $this->getUniqueemailVerificationCode();
        $user->mobileVerificationCode = $this->getUniqueMobileCode();
        $user->schoolId = $school[0]->id;

        try {
            $user->save();
        } catch (Exception $e) {
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
            return true;

        return false;
    }


    public function deactivate($id = NULL)
    {
        if ($id == NULL) {
            throw new InvalidArgumentException("Empty ID");
        }


        $data = array(

            'isDeactivated' => 1,
            'reactivateCode'=>$this->getUniqueReactivationCode()
        );
        try {
            User::update($id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return User::find($id);

    }

    public function deleted($id = NULL)
    {
        if ($id == NULL) {
            throw new InvalidArgumentException("Empty ID");
        }

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

    public function forgotten_password($email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException("Empty ID");
        }

        $user = User::where_email($email)->get();
        $forgotten_password_code = Str::random(64, 'alpha');
        $data = array(
            'forgottenPasswordCode' => $forgotten_password_code
        );
        try {
            User::update($user[0]->id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return User::find($user[0]->id);

    }

    public function forgotten_password_complete($code = NULL)
    {
        if ($code == NULL) {
            throw new InvalidArgumentException("Empty Forgotten Password Code");
        }

        $user = User::where_emailVerificationCode($code)->get();

        if (empty($user))
            throw new InvalidArgumentException("User Not Found");

        if (count($user) == 1) {
            $password = mt_rand(100000, 999999);
            $updateData = array('forgottenPasswordCode' => NULL,
                'password' => Hash::make($password)
            );
            try {
                User::update($user[0]->id, $updateData);
            } catch (Exception $e) {
                Log::exception($e);
                return false;
            }
            return User::find($user[0]->id);
        }
        return false;
    }

    public function change_password($id, $old_password, $new_password)
    {

        if (empty($id) || empty($old_password) || empty($new_password)) {
            throw new InvalidArgumentException("Empty id or Old Password or New Password");
        }

        $id = Crypter::decrypt($id);
        $user = User::where_id($id)->get();
        if (Hash::check($old_password, $user->{'password'})) {
            $user = User::find($id);
            $user->password = Hash::make($new_password);
            $user->save();
            return $user;
        }

        return false;

    }

    public function verifyMobile($mobileVerificationCode)
    {
        if ($mobileVerificationCode == NULL) {
            throw new InvalidArgumentException("Empty Mobile Verification Code");
        }

        $user = User::where_mobileVerificationCode($mobileVerificationCode)->first();

        if (empty($user))
            throw new InvalidArgumentException("User Not Found");

        if (count($user) == 1) {
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
        return false;

    }


    public function checkUniqueMobileCode($mobile_code)
    {
        $user = User::where_mobileVerificationCode($mobile_code)->first();
        if ($user == NULL)
            return true;

        return false;

    }

    public function getUniqueMobileCode()
    {
        $mobileCode = mt_rand(100000, 999999);
        if ($this->checkUniqueMobileCode($mobileCode))
            return $mobileCode;
        else
            $this->getUniqueMobileCode();
    }

    public function addUserToRole($userId, $role)
    {
        $user = User::find($userId);
        $role = Role::where_name($role)->first();
        $role = $user->roles()->attach($role->id);
        if ($role)
            return true;
        else
            return false;
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user;

    }

    public function checkUniqueVerificationCode($verificationCode)
    {
        $user = User::where_emailVerificationCode($verificationCode)->first();
        if ($user == NULL)
            return true;

        return false;

    }

    public function getUniqueemailVerificationCode()
    {
        $emailVerificationCode = Str::random(64, 'alpha');
        if ($this->checkUniqueVerificationCode($emailVerificationCode))
            return $emailVerificationCode;
        else
            $this->getUniqueemailVerificationCode();
    }

    public function checkUniqueReactivationCode($reactivationCode)
    {
        $user = User::where_reactivateCode($reactivationCode)->first();
        if ($user == NULL)
            return true;

        return false;

    }

    public function getUniqueReactivationCode()
    {
        $reactivationCode = Str::random(64, 'alpha');
        if ($this->checkUniqueReactivationCode($reactivationCode))
            return $reactivationCode;
        else
            $this->getUniqueReactivationCode();
    }
  }

