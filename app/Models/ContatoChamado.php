<?php

class ContatoChamado extends QueryBuilder {
    protected $table = 'contatos_chamado';

    public function query() {
        return new QueryBuilder();
    }
}
