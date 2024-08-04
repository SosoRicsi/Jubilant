<?php 

    namespace App\Controllers;

    use Jubilant\Template;
    use App\Models\About;

    class AboutController {

        private $model;

        public function __construct() {
            $this->model = new About();
        }

        public function index($id) {
            $Template = new Template(__DIR__.'/../views/about.blade.php');
            $Template->var([
                'title' => $id
            ]);
            echo $Template->render();
        }

    }