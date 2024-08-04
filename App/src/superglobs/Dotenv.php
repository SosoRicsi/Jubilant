<?php 

    namespace Jubilant\Superglobals;

    class Dotenv {
        public static function get($data) {
            $env = parse_ini_file(__DIR__.'/../../../.env');

            return $env[$data];
        }
    }