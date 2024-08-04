<?php 

    /* 
    *
    *   @JubilantPHP -- Jarkó Richárd production
    *
    */

    use Jubilant\Template;
    
    use App\Controllers\IndexController;
    use App\Controllers\AboutController;
    use App\Controllers\HomeController;
    use App\Middlewares\Auth;

    //use the router (for groups)
    //$router = $Application->router;

    //with auth middleware:
    //$Application->router->get('/', [IndexController::class, 'index'], [Auth::class]);
    $Application->router->get('/', [IndexController::class, 'index']);
    $Application->router->get('/about/{id:\w+}', [AboutController::class, 'index']);

    //redirect the user from 'alma' to the main page
    //$Application->router->redirect('/alma','/');

    //groups:
    /* $Application->router->group('/user', [Auth::class], function ($router) {
        $router->get('/login', function () {
            echo "Login page";
        }, [LoginStatus::class]);
        $router->get('/register', [RegisterController::class, 'index']);
    }); */

    $Application->router->redirect('/', '/home');

    $Application->router->add404Handler(function () {
        $Template = new Template(__DIR__.'/MVC/views/404.blade.php');
        $Template->var([
            'error'=>"404"
        ]);
        echo $Template->render();
    });
