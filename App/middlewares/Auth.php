<?php

    namespace App\Middlewares;

    use App\Interfaces\AuthMiddleware;
    use Jubilant\Superglobals\Session;

    class Auth implements AuthMiddleware {

        public function __construct() {
            Session::init();
        }

        public function handle() {
            $loggedin = Session::get('loggedin') ?: null;
            if($loggedin != null) {
                return true;
            } else {
                $this->iferror();
            }
        }

        public function iferror() {
            echo "A middleware false ága futtot le, mert a felhasználó nincs bejelentkezve!";
            return false;
        }

    }