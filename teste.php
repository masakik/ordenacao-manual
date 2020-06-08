<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ordenacao\Tabela;

$t = new Tabela;

$obj = new stdClass;

echo 'Adicionando 10 elementos ..', PHP_EOL;
for ($i = 0; $i < 10; $i++) {
    $t->adicionar($obj, rand(0, 30));
}

foreach ($t->listar() as $obj) {
    echo json_encode($obj), PHP_EOL;
}

echo 'Alterando ordem, adicionado e removendo 1000X..', PHP_EOL;
for ($i = 0; $i < 1000; $i++) {
    $t->alterar($t->obterAleatorio(), rand(0, 30));
    $t->alterar($t->obterAleatorio(), rand(0, 30));
    $t->alterar($t->obterAleatorio(), rand(0, 30));
    $t->excluir($t->obterAleatorio());
    $t->adicionar(new stdClass, rand(0, 30));
    $t->alterar($t->obterAleatorio(), rand(0, 30));
    $t->alterar($t->obterAleatorio(), rand(0, 30));
}
echo PHP_EOL;

foreach ($t->listar() as $obj) {
    echo json_encode($obj), PHP_EOL;
}