# Ordenação manual

Algoritmo para manter uma tabela de BD ordenada por um campo personalizado chamado ordem, que inicia em 1 e cresce conforme necessário.

Isto é apenas uma prova de conceito.

Ao inserir um novo registro, ele é colocado na posição informada reposicionando os demais registros. Se a posição não for informada ou for igual a zero, ele será posicionado ao final. A posição não pode ser negativa.

Ao remover um registro, ele reposiciona os demais registros para ocupar o espaço vazio.

Ao alterar a posição de um registro deve-se informar a posição de destino, uma vez que a posição de orígem já está no registro.

Para realizar as operações no BD usamos transaction para garantir a integridade dos dados em caso de concorrência, que não seria necessário neste exemplo.

Estamos usando sqlite/ramdrive pela simplicidade e desempenho.

## Instalação, configuração e teste

```bash
git clone https://github.com:masakik/ordenacao-manual
composer install
php teste.php
```

Enjoy!