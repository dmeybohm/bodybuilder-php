'ImportDefaultSpecifier' not implemented. { type: 'ImportDefaultSpecifier',
  local: 
   { type: 'Identifier',
     name: 'test',
     range: [ 7, 11 ],
     loc: { start: [Object], end: [Object] } },
  range: [ 7, 11 ],
  loc: { start: { line: 1, column: 7 }, end: { line: 1, column: 11 } },
  parent: 
   { type: 'ImportDeclaration',
     specifiers: [ [Circular] ],
     source: 
      { type: 'Literal',
        value: 'tape',
        raw: '\'tape\'',
        range: [Array],
        loc: [Object] },
     range: [ 0, 24 ],
     loc: { start: [Object], end: [Object] },
     parent: 
      { type: 'Program',
        body: [Array],
        sourceType: 'module',
        range: [Array],
        loc: [Object],
        comments: [],
        tokens: [Array],
        scope: [Object] } } }
'ImportDefaultSpecifier' not implemented. { type: 'ImportDefaultSpecifier',
  local: 
   { type: 'Identifier',
     name: 'filterBuilder',
     range: [ 31, 44 ],
     loc: { start: [Object], end: [Object] } },
  range: [ 31, 44 ],
  loc: { start: { line: 2, column: 7 }, end: { line: 2, column: 20 } },
  parent: 
   { type: 'ImportDeclaration',
     specifiers: [ [Circular] ],
     source: 
      { type: 'Literal',
        value: '../src/filter-builder',
        raw: '\'../src/filter-builder\'',
        range: [Array],
        loc: [Object] },
     range: [ 24, 75 ],
     loc: { start: [Object], end: [Object] },
     parent: 
      { type: 'Program',
        body: [Array],
        sourceType: 'module',
        range: [Array],
        loc: [Object],
        comments: [],
        tokens: [Array],
        scope: [Object] } } }
<?php
test('filterBuilder | filter term', function ($t) {
$t->plan(1);
$result = filterBuilder()->filter('term', 'field', 'value');
$t->deepEqual($result->getFilter(), array("term" => array("field" => 'value')));
}
);
test('filterBuilder | filter nested', function ($t) {
$t->plan(1);
$result = filterBuilder()->filter('constant_score', function ($f) {
$f->filter('term', 'field', 'value')}
);
$t->deepEqual($result->getFilter(), array("constant_score" => array("filter" => array("term" => array("field" => 'value')))));
}
);

