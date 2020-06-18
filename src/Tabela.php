<?php

namespace Ordenacao;

// Redbean é um ORM que constroi as tabelas conforme necessário
use \RedBeanPHP\R as R;

class Tabela
{
    public function __construct()
    {
        // vamos usar ramdrive para ficar rápido
        R::setup('sqlite::memory:');
    }

    public function adicionar($obj, $ordem)
    {
        if ($ordem < 0) {
            return false;
        }

        R::begin();
        try {
            $obj = R::dispense('tabela');
            $obj->ordem = $this->atualizarOrdem($obj, $ordem);
            R::store($obj);
            R::commit();
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
        return true;
    }

    public function excluir($bean)
    {
        R::begin();
        try {
            $bean->ordem = $this->atualizarOrdem($bean, -1);
            R::trash($bean);
            R::commit();
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
        return true;
    }

    public function alterar($bean, $ordem)
    {
        R::begin();
        try {
            $bean->ordem = $this->atualizarOrdem($bean, $ordem);
            R::store($bean);
            R::commit();
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
        return true;
    }

    public function listar()
    {
        return R::findAll('tabela', 'order by ordem');
    }

    public function obterAleatorio()
    {
        $list = R::findAll('tabela');
        return $list[array_rand($list)];
    }

    // se $obj não possuir ordem, é novo registro
    // se $ordem_nova for < 0, vai apagar registro
    // se $ordem_nova for == 0, vai para o final
    // se $ordem_nova for > 0, vai para a posição indicada
    protected function atualizarOrdem($obj, $ordem_nova = 0)
    {
        $max = R::getCell('SELECT MAX(ordem) FROM tabela');

        if (empty($max)) {
            $max = 0;
            $novo = true;
        }

        if (empty($obj->ordem)) {
            $novo = true;
            $ordem_atual = $max + 1;
        } else {
            $novo = false;
            $ordem_atual = $obj->ordem;
        }

        // verifica se vai para o final
        if ($ordem_nova == 0 || $ordem_nova > $max) {
            $ordem_nova = ($novo) ? $max + 1 : $max;
        }

        // se for negativo é delete
        if ($ordem_nova < 0) {
            R::exec('UPDATE tabela SET ordem = ordem-1 WHERE ordem > ?', [$ordem_atual]);
            return $ordem_atual;
        }

        // se for igual não faz nada
        if ($ordem_nova == $ordem_atual) {
            return $ordem_nova;
        }

        // atualizar para um valor: descendo
        if ($ordem_nova > $ordem_atual) {
            R::exec(
                'UPDATE tabela SET ordem = ordem-1 WHERE ordem > ? AND ordem <= ?',
                [$ordem_atual, $ordem_nova]
            );
            return $ordem_nova;
        }

        // atualizar para um valor: subindo
        if ($ordem_nova < $ordem_atual) {
            R::exec(
                'UPDATE tabela SET ordem = ordem+1 WHERE ordem < ? AND ordem >= ?',
                [$ordem_atual, $ordem_nova]
            );
            return $ordem_nova;
        }
    }
}
