<?php

class Chamado extends QueryBuilder {
    protected $table = 'chamados';

    public function query() {
        return new QueryBuilder();
    }
}
