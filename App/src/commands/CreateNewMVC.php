<?php 

namespace Jubilant\commands;

use Jubilant\Superglobals\Dotenv;

class CreateNewMVC {
    
    public function run($filename) {
        echo "Creating MVC structure for '$filename'...\n";

        $view = __DIR__.'/../../public/MVC/views/'.$filename.'.blade.php';
        $controller = __DIR__.'/../../public/MVC/controllers/'.ucfirst($filename).'Controller.php';
        $model = __DIR__.'/../../public/MVC/models/'.ucfirst($filename).'.php';

        if (file_exists($view) || file_exists($model) || file_exists($controller)) {
            echo "Some of the files already exist!.\n";
            return;
        }

        $modelFile = fopen($model, 'w');
        $controllerFile = fopen($controller, 'w');
        $viewFile = fopen($view, 'w');

        if ($modelFile && $controllerFile && $viewFile) {
            echo "MVC files for '$filename' created successfully.\n";

            // Generate the controller content
            $controllerContent = $this->generateControllerContent($filename);
            $modelContent = $this->generateModelContent($filename);
            $viewContent = $this->generateViewContent($filename);
            fwrite($controllerFile, $controllerContent);
            fwrite($modelFile, $modelContent);
            fwrite($viewFile, $viewContent);

            fclose($modelFile);
            fclose($controllerFile);
            fclose($viewFile);
        } else {
            echo "Failed to create MVC files for '$filename'.\n";
        }
    }

    private function generateControllerContent($filename) {
        $controllerName = ucfirst($filename) . 'Controller';
        $modelName = ucfirst($filename);
        
        return <<<PHP
<?php 

    namespace App\Controllers;

    use Jubilant\Template;
    use App\Models\\$modelName;

    class $controllerName {

        private \$model;

        public function __construct() {
            \$this->model = new $modelName();
        }

        public function index() {
            \$Template = new Template(__DIR__.'/../views/$filename.blade.php');
            \$Template->var([
                'title' => "$filename view"
            ]);
            echo \$Template->render();
        }

    }
PHP;
        }

        private function generateModelContent($filename) {
            $modelName = ucfirst($filename);

            return <<<PHP
<?php

    namespace App\Models;

    class $modelName {

        public function index() {

        }

    }
PHP;

        }

        private function generateViewContent($filename) {
            return <<<PHP
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    @mainCss
    <body>
        <h1>{{title}}</h1>
    </body>
</html>
PHP;
        }

    }
