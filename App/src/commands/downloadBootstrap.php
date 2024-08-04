<?php

    namespace Jubilant\commands;

    class downloadBootstrap {

        public function run() {
            return shell_exec('composer require twbs/bootstrap');
        }

    }