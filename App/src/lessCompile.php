<?php 
    require_once __DIR__.'/../../vendor/autoload.php';

    use Jubilant\Lang;

    function getLessFiles($directory = __DIR__.'/../styles/') {
        if (!is_dir($directory)) {
            throw new InvalidArgumentException(Lang::trans('pathDoesntExists').$directory);
        }

        $lessFiles = [];
        $files = glob($directory . '/*.less');
        foreach ($files as $file) {
            $lessFiles[] = $file;
        }

        return $lessFiles;
    }

    function compileLess($inputFile, $outputFile) {
        $parser = new Less_Parser;
        $parser->parseFile($inputFile);
        $css = $parser->getCss();
        file_put_contents($outputFile, $css);
    }

    $lessDirectory = __DIR__.'/../styles/';
    $cssDirectory = __DIR__.'/../styles/css/';

    if (!is_dir($cssDirectory)) {
        mkdir($cssDirectory, 0777, true);
    }

    $lessFiles = getLessFiles($lessDirectory);

    foreach ($lessFiles as $lessFile) {
        $filename = basename($lessFile, '.less');
        $cssFile = $cssDirectory.'/'.$filename.'.css';

        if (!file_exists($cssFile) || filemtime($lessFile) > filemtime($cssFile)) {
            compileLess($lessFile, $cssFile);
        }
    }
?>
