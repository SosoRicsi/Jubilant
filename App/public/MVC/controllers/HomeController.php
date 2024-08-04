<?php 

    namespace App\Controllers;

    use Jubilant\Template;
    use App\Models\Home;

    class HomeController {

        private $model;

        public function __construct() {
            $this->model = new Home();
        }

        public function index() {
            $Template = new Template(__DIR__.'/../views/home.blade.php');
            $Template->var([
                'title' => "home view"
            ]);
            echo $Template->render();
        }

    }