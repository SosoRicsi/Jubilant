<?php 

    namespace App\Controllers;

    use Jubilant\Template;

    use App\Models\Index;
    class IndexController {
        private $model;

        public function __construct() {
            $this->model = new Index();
        }

        public function index() {
            $Template = new Template(__DIR__.'/../views/index.blade.php');
            $name = $this->model->randomName('Banános Joe');
            if($name != null) {
                $Template->var([
                    'appName'   => "JubilantPHP",
                    'title'     => "Index",
                    'language'  => "hu",
                    'name'      => $name
                ]);

                echo $Template->render();
                return;
            } else {
                echo "View not found!";
            }
        }
        
    }