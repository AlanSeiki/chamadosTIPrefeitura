<?php

class User extends QueryBuilder {
    protected $table = 'usuarios';

    public function query() {
        return new QueryBuilder();
    }
}
