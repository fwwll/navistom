<?

class Select {
    private $queries = array();

    private $query = null;

    public function __construct($columns) {
        if (is_array($columns)) {
            $columns = implode(', ', $columns);
        }
        else {
            $columns = ($columns == '*' ? '*' : $columns);
        }

        $this->queries['select'] = $columns;

        return $this;
    }

    public function from($table) {
        if (is_array($table)) {

        }
        else {
            $this->queries['from'] = $table;
        }

        return $this;
    }

    public function where($where, $limiter = '=') {
        if ($where != null and is_array($where)) {
            foreach ($where as $key => $val) {
                if ($val !== '' and $val !== null) {
                    $tmp[] = $key . ' ' . $limiter . ' ' . $val;
                }
            }
            $this->queries['where'] = @implode(' AND ', $tmp);
        }
        else
            $this->queries['where'] = '1';

        return $this;
    }

    public function orderBy($order) {
        if (is_array($order)) {
            $this->queries['order'] = implode(', ', $order);
        }

        return $this;
    }

    public function limit($count, $offset = 0) {
        if ((int)$count > 0) {
            $this->queries['limit'] = $offset . ', ' . $count;
        }

        return $this;
    }

    public function getAssoc($oneRow = 0) {

        return DB::getAssocArray($this->createQuery(), $oneRow);
    }

    public function getAssocGroup() {
        return DB::getAssocGroup($this->createQuery());
    }

    public function getColumn($column = 0) {
        return DB::getColumn($this->createQuery(), $column);
    }

    public function mode() {
        if (!$this->query instanceof PDOStatement) {
            $this->query = DB::query($this->createQuery());
        }

        call_user_func_array(array($this->query, 'setFetchMode'), func_get_args());

        return $this;
    }

    public function model($class = null) {
        return $this->mode(\PDO::FETCH_CLASS, $class);
    }

    public function fetch() {
        if (!$this->query instanceof PDOStatement) {
            $this->query = DB::query($this->createQuery());
        }

        return call_user_func_array(array($this->query, 'fetchAll'), func_get_args());
    }

    private function createQuery() {
        return
            'SELECT ' . $this->queries['select'] .
            ' FROM ' . $this->queries['from'] .
            ($this->queries['where'] ? (' WHERE ' . $this->queries['where']) : '') .
            ($this->queries['order'] ? (' ORDER BY ' . $this->queries['order']) : '') .
            ($this->queries['limit'] ? (' LIMIT ' . $this->queries['limit']) : '');
    }
}