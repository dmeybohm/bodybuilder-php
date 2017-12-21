<?php

namespace Best\ElasticSearch\BodyBuilder\AstImport;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Doctrine\Common\Inflector\Inflector;

function addFunctionCallsAsMethodsTo(Class_ $class, ClassMethod $method, array $functionCalls) {
    $class->stmts = [];
    foreach ($functionCalls as $functionCall) {
        /** @var Node\Expr\FuncCall $functionCall */
        list($string, $closure) = $functionCall->args;
        $string = $string->value;
        $closure = $closure->value;
        /** @var Node\Expr\Closure $closure */
        /** @var Node\Scalar\String_ $string */
        $testName = replaceName($string->value);
        $newMethod = clone $method;
        $newMethod->name = 'test' . ucfirst($testName);
        $newMethod->stmts = $closure->stmts;
        $class->stmts[] = $newMethod;
    }
}

function replaceName( $name) {
    return Inflector::camelize(preg_replace("/[^\w]+/", "_", $name));
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

function extractTestFunctionCalls($ast)
{
    $visitor = new class extends NodeVisitorAbstract{
        public $functions = [];

        public function enterNode(Node $node){
            if ($node instanceof Node\Expr\FuncCall &&
                $node->name instanceof Node\Name &&
                count($node->name->parts) === 1 &&
                $node->name->parts[0] === 'test') {
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
            return null;
        }
    });
}


