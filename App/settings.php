<?php 
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/src/lessCompile.php';

    use Jubilant\Superglobals\Dotenv;

    $EmailServer        = [Dotenv::get('EMAIL_HOST'),Dotenv::get('EMAIL_USER'),Dotenv::get('EMAIL_PASSWORD'),Dotenv::get('EMAIL_PORT'),Dotenv::get('EMAIL_SENDER')];
    $DatabaseConnection = [Dotenv::get('DATABASE_HOST'),Dotenv::get('DATABASE_USER'),Dotenv::get('DATABASE_PASSWORD'),Dotenv::get('DATABASE')];

    $Appname = Dotenv::get('APP_NAME');
    $language = Dotenv::get('APP_LANGUAGE');