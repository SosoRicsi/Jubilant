<?php 
    namespace Jubilant;

    class PasswordHash {
        private $hashedPassword;
        
        public function passwordHash($password, $hashType = PASSWORD_DEFAULT) {
            $this->hashedPassword = password_hash($password, $hashType);

            return $this->hashedPassword;
        }

        public function passwordVerify($password, $hashedPassword) {
            return password_verify($password, $hashedPassword);
        }

        public function isPasswordStrong($password, $minLenght = 8) {
            require_once __DIR__.'/../settings.php';
            
            if(strlen($password) < $minLenght) {
                echo Lang::trans('passwordMinimumCharError').$minLenght;
                return false;
            }

            if(!preg_match('/[A-Z]/', $password)) {
                echo Lang::trans('passwordLeastOneUppercase');
                return false;
            }

            if(!preg_match('/[a-z]/', $password)) {
                echo Lang::trans('passwordLeastOneLowercase');
                return false;
            }

            if(!preg_match('/[0-9]/', $password)) {
                echo Lang::trans('passwordLeastOneNumber');
                return false;
            }

            if(!preg_match('/[!@$&_]/', $password)) {
                echo Lang::trans('passwordLeastOneSpecialChar').' (!@$&_)';
                return false;
            }

            return true;
        }

    }
?>