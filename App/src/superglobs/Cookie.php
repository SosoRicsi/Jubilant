<?php 
    namespace Jubilant\Superglobals;

    class Cookie {

        public static function set($name, $value, $expire = 3600, $path = '/', $domain = "", $secure = false, $httponly = false) {
            $expireTime = time() + $expire;

            setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        }

        public static function exists($name) {
            return isset($_COOKIE[$name]);
        }

        public static function get($name) {
            if(!empty(self::exists($name))) {
                return $_COOKIE[$name];
            } else {
                return false;
            }
        }

        public static function getAll() {
            return $_COOKIE;
        }

        public static function remove($name, $path = '/', $domain = "") {
            if(self::exists($name)) {
                setcookie($name, '', time()-42000, $path, $domain);
                unset($_COOKIE[$name]);
            }
        }

        public static function destroy() {
            foreach ($_COOKIE as $name => $value) {
                setcookie($name, '', time()-42000, '/');
                unset($_COOKIE[$name]);
            }
        }

    }
?>