<?php

    namespace App\Interfaces;

    Interface AuthMiddleware {

        public function __construct();
        public function handle();
        public function iferror();

    }