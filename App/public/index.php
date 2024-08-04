<?php
    /* 
    *
    *   @JubilantPHP -- Jarkó Richárd production
    *
    */

    require __DIR__.'/../settings.php';

    use Jubilant\Application;
    use Jubilant\Router;

    /* Set up the router */
    $router = new Router();

    /* Set the application */
    $Application = new Application($router);

    /* Set the paths */
    require __DIR__.'/routes.php';

    /* Run the application */
    $Application->start();
