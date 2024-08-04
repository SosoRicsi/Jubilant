<?php
    namespace Jubilant\commands;

    use Jubilant\Superglobals\Dotenv;

    class RunDevServer {

        public function run($port = null) {
            $port = $port ?? Dotenv::get('DEV_SERVER_PORT', '8000');
            $command = sprintf("php -S localhost:%s -t App/public App/public/index.php", $port);
            echo "Starting developer server at http://localhost:{$port}\n";
            shell_exec($command);
        }
    }