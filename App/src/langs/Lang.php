<?php

    namespace Jubilant;

    use Jubilant\Superglobals\Dotenv;

    class Lang {

        public static function trans(string $variable) {

            require __DIR__.'/../../settings.php';
            $language = Dotenv::get('APP_LANGUAGE');
            $filePath = __DIR__.'/'.strtoupper($language).'.php';

            if(file_exists($filePath)) {
                require $filePath;
                if(isset($$variable)) {
                    return $$variable;
                } else {
                    return "Translation not found for $variable in language: ".$language;
                }
            } else {
                return "Translation file not found for language: ".$language;
            }


        }

    }