<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpParser\Error;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
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
    $functions = extractFunctionCalls($astToConvert);
    addFunctionCallsAsMethodsTo($class, $methodTemplate, $functions);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$prettyPrinter = new PrettyPrinter\Standard;
echo $prettyPrinter->prettyPrintFile($templateAst);

function addFunctionCallsAsMethodsTo(Class_ $class, ClassMethod $method, array $functionCalls) {
    $class->stmts = [];
    foreach ($functionCalls as $functionCall) {
        /** @var Node\Expr\FuncCall $functionCall */
        list($string, $closure) = $functionCall->args;
        $string = $string->value;
        $closure = $closure->value;
        /** @var Node\Expr\Closure $closure */
        /** @var Node\Scalar\String_ $string */
        $testName = Inflector\Inflector::camelize(str_replace("|", "", $string->value));
        $newMethod = clone $method;
        $newMethod->name = 'test' . ucfirst($testName);
        $newMethod->stmts = $closure->stmts;
        $class->stmts[] = $newMethod;
        if (count($class->stmts) === 1) {
            break;
        }
    }
}

function extractClassAndMethod($templateAst) {
    $visitor = new class extends NodeVisitorAbstract {
        public $class;
        public $method;
        public function enterNode(Node $node) {
            if ($node instanceof Class_) {
                $this->class = $node;
            } elseif ($node instanceof ClassMethod) {
                $this->method = $node;
            }
        }
    };
    traverse($templateAst, $visitor);
    // clear out class method:
    $visitor->class->stmts = [];
    return [$visitor->class, $visitor->method];
}

function extractFunctionCalls($ast)
{
    $visitor = new class extends NodeVisitorAbstract{
        public $functions = [];

        public function enterNode(Node $node){
            if ($node instanceof Node\Expr\FuncCall) {
                $this->functions[] = $node;
            }
        }
    };

    traverse($ast, $visitor);
    return $visitor->functions;
}

function traverse($ast, NodeVisitor $visitor) {
    $traverser = new NodeTraverser();
    $traverser->addVisitor($visitor);
    return $traverser->traverse($ast);
}

function dumpAst($ast) {
    traverse($ast, new class extends NodeVisitorAbstract {
        public function enterNode(Node $node) {
            echo get_class($node);
            var_dump($node);
        }
    });
}
function removeInlineHtml($ast) {
    return traverse($ast, new class extends NodeVisitorAbstract {
        public function leaveNode(Node $node) {
            if ($node instanceof Node\Stmt\InlineHTML) {
                return NodeTraverser::REMOVE_NODE;
            }
        }
    });
}


