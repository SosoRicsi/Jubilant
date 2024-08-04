<?php 
    namespace Jubilant\Superglobals;

    class Session {
        
        public static function init() {
            if(session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }

        public static function set($key, $value) {
            $_SESSION[$key] = $value;
            return true;
        }

        public static function get($key) {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }

        public static function getAll(bool $all = true) {
            if($all == true) {
                return $_SESSION;
            } else {
                return false;
            }
        }

        public static function exists($name) {
            return isset($_SESSION[$name]);
        }

        public static function remove($key) {
            if(isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
            return true;
        }

        public static function destroy() {
            $_SESSION = [];
            if(session_id() != '' || isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-42000,'/');
            }
            session_unset();
            session_destroy();
            return true;
        }

        public static function regenerate() {
            session_regenerate_id(true);
            return true;
        }

        public static function getSessionID() {
            return session_id();
        }

    }
?>