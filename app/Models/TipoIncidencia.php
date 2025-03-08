<?php

class TipoIncidencia extends QueryBuilder {
    protected $table = 'tipos_incidencia';

    public function query() {
        return new QueryBuilder();
    }
}
