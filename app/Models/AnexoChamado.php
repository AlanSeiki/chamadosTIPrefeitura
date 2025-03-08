<?php

class AnexoChamado extends QueryBuilder {
    protected $table = 'anexos_chamado';

    public function query() {
        return new QueryBuilder();
    }
}