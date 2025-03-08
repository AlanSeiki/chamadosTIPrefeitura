<?php
require_once __DIR__ . '/../../config/database.php';

class QueryBuilder {
    protected $pdo;
    protected $query;
    protected $bindings;
    
    private $allowedDirections = ['ASC', 'DESC'];

    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->query = "";
        $this->bindings = [];
    }

    public function select($table, $columns = "*") {
        $this->query = "SELECT $columns FROM $table";
        return $this;
    }

    public function where($column, $operator, $value) {
        $placeholder = ":" . str_replace('.', '_', $column);
        $this->query .= (strpos($this->query, "WHERE") === false ? " WHERE" : " AND") .
                        " $column $operator $placeholder";
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    public function whereBetween($column, $start, $end) {
        $placeholderStart = ":{$column}_start";
        $placeholderEnd = ":{$column}_end";
        $this->query .= (strpos($this->query, "WHERE") === false ? " WHERE" : " AND") .
                        " $column BETWEEN $placeholderStart AND $placeholderEnd";
        $this->bindings[$placeholderStart] = $start;
        $this->bindings[$placeholderEnd] = $end;
        return $this;
    }

    public function whereIn($column, array $values) {
        $placeholders = [];
        foreach ($values as $index => $value) {
            $placeholder = ":{$column}_in_$index";
            $placeholders[] = $placeholder;
            $this->bindings[$placeholder] = $value;
        }
        $this->query .= (strpos($this->query, "WHERE") === false ? " WHERE" : " AND") .
                        " $column IN (" . implode(", ", $placeholders) . ")";
        return $this;
    }

    public function whereILike($column, $value) {
        $placeholder = ":" . str_replace('.', '_', $column);
        $this->query .= (strpos($this->query, "WHERE") === false ? " WHERE" : " AND") .
                        " LOWER($column) LIKE LOWER($placeholder)";
        $this->bindings[$placeholder] = "%$value%";
        return $this;
    }

    public function whereIsNull($column) {
        $this->query .= (strpos($this->query, "WHERE") === false ? " WHERE" : " AND") .
                        " $column IS NULL";
        return $this;
    }

    public function join($table, $on) {
        $this->query .= " JOIN $table ON $on";
        return $this;
    }

    public function leftJoin($table, $on) {
        $this->query .= " LEFT JOIN $table ON $on";
        return $this;
    }

    public function rightJoin($table, $on) {
        $this->query .= " RIGHT JOIN $table ON $on";
        return $this;
    }

    public function limit($limit) {
        $this->query .= " LIMIT :limit";
        $this->bindings[':limit'] = (int) $limit;
        return $this;
    }

    public function get() {
        $stmt = $this->pdo->prepare($this->query);
        foreach ($this->bindings as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function paginate($limit, $offset, $campo, $dir) {
        if (!in_array(strtoupper($dir), $this->allowedDirections)) {
            throw new Exception("Parâmetro inválido.");
        }

        $this->query .= " ORDER BY $campo $dir LIMIT :limit OFFSET :offset";
        $this->bindings[':limit'] = (int) $limit;
        $this->bindings[':offset'] = (int) $offset;

        $stmt = $this->pdo->prepare($this->query);
        foreach ($this->bindings as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function first() {
        $stmt = $this->pdo->prepare($this->query);
        foreach ($this->bindings as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetch();
    }

    public function last($campo) {
     
        $this->query .= " ORDER BY $campo DESC LIMIT 1";
        $stmt = $this->pdo->prepare($this->query);
        foreach ($this->bindings as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetch();
    }

    public function count($table, $column = "*") {
        $this->query = "SELECT COUNT($column) AS total FROM $table";
        return $this;
    }  
     
    public function groupBy($columns) {
        $this->query .= " GROUP BY $columns";
        return $this;
    }
    
    public function update($table, $data, $where) {
        $setPart = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $wherePart = implode(" AND ", array_map(fn($key) => "$key = :where_$key", array_keys($where)));

        $sql = "UPDATE $table SET $setPart WHERE $wherePart";
        $stmt = $this->pdo->prepare($sql);

        foreach ($where as $key => $value) {
            $data["where_$key"] = $value;
        }

        return $stmt->execute($data);
    }

    public function delete($table, $where) {
        $wherePart = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));

        $sql = "DELETE FROM $table WHERE $wherePart";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($where);
    }

    public function create($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function leftJoinWithCondition($table, callable $condition) {
        $joinCondition = "1=1";
        $bindings = [];
    
        // Chama o callback para gerar a condição do join
        $condition($joinCondition, $bindings);
    
        $this->query .= " LEFT JOIN $table ON $joinCondition";
    
        // Mescla os bindings do join com os existentes
        $this->bindings = array_merge($this->bindings, $bindings);
        return $this;
    }
    
}
