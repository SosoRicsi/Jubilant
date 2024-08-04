<?php

    namespace Jubilant;

    use Jubilant\Router;

    class Application {
        public Router $router;

        public function __construct(Router $router) {
            $this->router = $router;
        }

        public function start() {
           $this->router->run();
        }
    }
