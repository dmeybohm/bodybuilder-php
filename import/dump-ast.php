<?php

namespace Best\ElasticSearch\BodyBuilder\AstImport;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/ast-funcs.php';

use PhpParser\Error;
use PhpParser\ParserFactory;

$code = file_get_contents($_SERVER['argv'][1]);
if ($code === false) {
    echo "Failed to read contents\n";
    return;
}

try {
    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    $ast = $parser->parse($code);
    dumpAst($ast);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}
