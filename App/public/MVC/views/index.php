<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta name="description" content="JubilantPHP: az egyszeű PHP kódokért!">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="App\styles\css\main.css">
        <script src="App\src\jQuery\jquery.js"></script>
        <title>SPA Example</title>
    </head>
    <body>
        <nav>
            <a style="color: white !important" href="/">Home</a>
            <a style="color: white !important" href="/about/12">About</a>
            <a style="color: white !important" href="/contact">Contact</a>
        </nav>
        <div id="content">
            <!-- A tartalom dinamikusan változik az URL alapján -->
        </div>
        <script>
            function a() {
                alert("Üdv!")
            }
        </script>
    </body>
</html>