<?php
namespace Jubilant;

class Template {
    private $templatePath;
    private $cssPath;
    private $variables = [];

    public function __construct($templatePath) {
        $this->templatePath = $templatePath;
    }

    public function css($cssPath = '') {
        $this->cssPath = $cssPath;
    }

    public function var(array $variables) {
        $this->variables = $variables;
        return true;
    }

    public function render() {
        $content = file_get_contents($this->templatePath);

        foreach ($this->variables as $key => $value) {
            $content = str_replace("{{".$key."}}", htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $content);
        }

        $content = $this->processTemplates($content);

        // Markdown címek kezelése
        $content = preg_replace('/(|\n)# (.+)/', '$1<h1>$2</h1>', $content);
        $content = preg_replace('/(|\n)## (.+)/', '$1<h2>$2</h2>', $content);
        $content = preg_replace('/(|\n)### (.+)/', '$1<h3>$2</h3>', $content);
        $content = preg_replace('/(|\n)#### (.+)/', '$1<h4>$2</h4>', $content);
        $content = preg_replace('/(|\n)##### (.+)/', '$1<h5>$2</h5>', $content);
        $content = preg_replace('/(|\n)###### (.+)/', '$1<h6>$2</h6>', $content);

        // Félkövér és dőlt szöveg
        $content = preg_replace('/(\*\*|__)(.*?)\1/', '<strong>$2</strong>', $content); // félkövér
        $content = preg_replace('/(\*|_)(.*?)\1/', '<em>$2</em>', $content); // dőlt

        // Link (inline)
        $content = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $content);

        // Kép
        $content = preg_replace('/!\[([^\]]+)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $content);

        // Unordered list
        $content = preg_replace('/\* (.+)/m', '<li>$1</li>', $content);

        // Ordered list
        $content = preg_replace('/\d+\. (.+)/m', '<li>$1</li>', $content);

        // Kódblokk
        $content = preg_replace('/```(.+?)```/s', '<pre><code>$1</code></pre>', $content);

        // Blockquote
        $content = preg_replace('/\> (.+)/m', '<blockquote>$1</blockquote>', $content);


        /* blade elements */
        $content = preg_replace('/@if\(\s*(.+?)\s*\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@if \(\s*(.+?)\s*\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@elseif\(\s*(.+?)\s*\)/', '<?php elseif($1): ?>', $content);
        $content = preg_replace('/@elseif \(\s*(.+?)\s*\)/', '<?php elseif($1): ?>', $content);
        $content = str_replace('@else', '<?php else: ?>', $content);
        $content = str_replace('@endif', '<?php endif; ?>', $content);    

        $content = preg_replace('/@for\(\s*(.+?)\s*\)/', '<?php for ($1): ?>', $content);
        $content = str_replace('@endfor', '<?php endfor; ?>', $content);

        $content = str_replace('@hw','Hello, World!',$content);
        if(file_exists(__DIR__.'/../styles/css/main.css')) {
            $mainCss = file_get_contents(__DIR__.'/../styles/css/main.css');
            $content = str_replace('@mainCss', '<?php echo "<style>".$mainCss."</style>" ?>', $content);
        } else {
            $content = str_replace('@mainCss', 'Nem hívható meg a css fájl, mert nem létezik!', $content);
        }
        
        ob_start();
        eval('?>'.$content);
        $final = ob_get_clean();

        return $final;
    }

    private function processTemplates($content) {
        $templateCounter = 0;

        $content = preg_replace_callback('/@template\(\s*[\'"](.+?)[\'"]\s*(?:,\s*array\((.*?)\)\s*)?\)/', function($matches) use (&$templateCounter) {
            $templateCounter++;
            $templateVar = '$Template' . $templateCounter;

            $variables = isset($matches[2]) ? 'array('.$matches[2].')' : 'array()';

            $filePath = str_replace('\\', '/', dirname(__DIR__).'/public/MVC/views/'.$matches[1].'.blade.php');

            return '<?php '.$templateVar.' = new \Jubilant\Template("'.$filePath.'");'.$templateVar.'->var('.$variables.'); echo '.$templateVar.'->render(); ?>';
        }, $content);
        return $content;
    }
}
