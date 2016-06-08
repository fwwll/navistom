<?php
/**
 * Data Base class
 */
class DB {
    private static $DBO;
    private static $select = array();
    private  static $options = array();

    static public function connect($config) {
        try {
            self::$options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['db_charset']}"
            );

            self::$DBO = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']}", $config['db_user'], $config['db_pass'], self::$options);

            self::$DBO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            return true;
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    static public function DBObject() {
        return self::$DBO;
    }

    static public function query($query) {
        try {
            $start_time = microtime(true);

            $result = self::$DBO->query($query);

            $end_time = microtime(true);

            Debug::setLogSQL($query, $start_time, $end_time);
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        return $result;
    }

    static public function exec($query) {
        try {
            $result = self::$DBO->exec($query);
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        return $result;
    }

    static public function getAssocArray($query, $flag_one_row = 0) {
        try {
            if ($query = self::query($query))
                $result =  $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        if ($flag_one_row > 0)
            return $result[0];
        else
            return $result;
    }

    static public function getTableCount($table, $where = null) {
        if ($where != null) {
            $where = DB::_whereReplace($where);
        }
        else {
            $where = 1;
        }

        $query = "SELECT COUNT(*) FROM `$table` WHERE $where";

        return self::getColumn($query);
    }

    public static function fetch($query) {
        try {
            if ($query = self::query($query)) {
                $args = func_get_args();
                array_shift($args);
                return call_user_func_array(array($query, 'fetchAll'), $args);
            }
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    static public function getAssocKey($query) {
        try {
            if ($query = self::query($query))
                return $query->fetchAll(PDO::FETCH_KEY_PAIR );
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    static public function getAssocGroup($query) {
        try {
            if ($query = self::query($query))
                return $query->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    static public function getColumn($query, $column = 0) {
        try {
            if ($query = self::query($query))
                $result = $query->fetchColumn($column);
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        return $result;
    }

    static public function getNumArray($query) {
        try {
            if ($query = self::query($query))
                $result =  $query->fetchAll(PDO::FETCH_NUM);
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    static public function lastInsertId() {
        return self::$DBO->lastInsertId();
    }

    static public function delete($table, $where = null) {
        try {
            $where = DB::_whereReplace($where);

            $result = self::$DBO->exec("DELETE IGNORE FROM `$table` WHERE $where");
        }
        catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        return $result;
    }

    static public function update($table, $write, $where = null) {
        try {
            $where = DB::_whereReplace($where);

            foreach ($write as $key => $val) {
                $update[] = $key.' = :'.$key;
            }

            $update = @implode(', ', $update);

            $result = self::$DBO->prepare("UPDATE IGNORE `$table` SET $update WHERE $where");
            $result->execute($write);

        } catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        return $result;
    }

    static public function sorted($table, $data, $column) {
        try {
            $key = key($data);
            $set = '';

            for ($i = 0, $c = count($data[$key]); $i < $c; $i++) {
                $set .= ' WHEN '. $key .' = ' . (int) $data[$key][$i] . ' THEN ' . $i;
            }

            return self::query(join(' ', array('UPDATE IGNORE', $table, 'SET', $column, '= CASE', $set, 'ELSE', $column, 'END')));

        } catch (PDOException $e) {
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    static public function insert($table, $write, $flag_replace = 0) {
        try {
            foreach ($write as $key => $val) {
                $keys[] 	= $key;
                $values[]	= ':'.$key;
            }

            $keys 	= @implode(', ', $keys);
            $values	= @implode(', ', $values);

            $comand = $flag_replace > 0 ? 'REPLACE INTO' : 'INSERT INTO';

            $result = self::$DBO->prepare("$comand `$table` ($keys) VALUES($values)");
            $result->execute($write);
        }
        catch (PDOException $e) {
	
            Debug::setError('SQL', $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            return false;
        }

        return true;
    }

    static public function truncate($table) {
        return self::query('TRUNCATE ' . $table);
    }

    static public function close() {
        self::$DBO = null;
    }

    static public function now($flag_date = 0, $p=0) {
		
		if($p){
		  	return (date('Y').'-'.date('m').'-'.(date('d')+$p));
			
		}
		
		
        if($flag_date) {
            return date('Y-m-d');
        }
        else {
            return date('Y-m-d H:i:s');
        }
    }

    public static function select($columns = '*') {
        return new Select($columns);
    }

    static private function _whereReplace($where) {
        if ($where != null and is_array($where)) {
            foreach ($where as $key => $val)
                $_w[] = $key.' = '.self::$DBO->quote($val);
            $where = @implode(' AND ', $_w);
        }
        else
            $where = 1;

        return $where;
    }
}