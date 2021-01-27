<?php

namespace Best\ElasticSearch\BodyBuilder\AstImport;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/ast-funcs.php';

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Doctrine\Common\Inflector\Inflector;

$code = file_get_contents($_SERVER['argv'][1]);
if ($code === false) {
	echo "Failed to read contents\n";
	return;
}

$testName = ucfirst(Inflector::camelize(pathinfo($_SERVER['argv'][1], PATHINFO_FILENAME)));
$testTemplate = <<< PHP
<?php
    class {$testName}Test extends \PHPUnit_Framework_TestCase {
        public function testMethodBody() {
        }
    }    
PHP;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $astToConvert = $parser->parse($code);
    $templateAst  = $parser->parse($testTemplate);
    //dumpAst($templateAst);
    list($class, $methodTemplate) = extractClassAndMethod($templateAst);
    $astToConvert = removeInlineHtml($astToConvert);
    $functions = extractTestFunctionCalls($astToConvert);
    addFunctionCallsAsMethodsTo($class, $methodTemplate, $functions);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$prettyPrinter = new PrettyPrinter\Standard;
echo $prettyPrinter->prettyPrintFile($templateAst);

