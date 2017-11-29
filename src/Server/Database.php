<?php
class Database {
    private $host    = DB_HOST;
    private $user    = DB_USER;
    private $pass    = DB_PASS;
    private $charset = 'utf8mb4';
    private $dbname  = DB_NAME;

    private $pdo;
    private $error;

    protected $stmt;

    public function __construct(){
        $dsn = "mysql:host={$this->host};
                dbname={$this->dbname};
                charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];

        $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
    }
    /**
 	 * Initial preparation of sql statements.
 	 * @param $placeholder STRING
     * Used as a placeholder for the actual value is from as in ':name'
 	 * @param $value STRING
     * Value to be assigned to corresponding $placeholder as in 'John Doe'
 	 * @param $type STRING
     * While $placeholder expects a string, $type determines
     * what type of data the column accepts.
	 */
    public function bind($placeholder, $value, $type=NULL) {
        if(is_null($type)) {
            switch (true) {
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($placeholder, $value, $type);
        return true;
    }

    public function query($query) {
        try {
            $this->stmt = $this->pdo->prepare($query);
        }
        catch (Exception $err) {
            return $err;
        }

        return true;
    }

    protected function insert($table, $columns, $values, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            $insert = "INSERT INTO {$table}
                          {$columns}
                       VALUES
                          {$values}";
        }
        else {
            $insert = "INSERT INTO {$table}
                          {$columns}
                       VALUES
                          {$values}
                       WHERE {$maybeWhere}";
        }

        return $insert;
    }

    public function select($table, $value, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            $select = "SELECT {$value} FROM {$table}";
        }
        else {
            $select = "SELECT {$value} FROM {$table} WHERE {$maybeWhere}";
        }

        return $select;
    }

    protected function selectJoin($table1, $table2, $value,
                                  $common, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            $select = "SELECT {$value}
                       FROM {$table1} tb1
                       LEFT JOIN {$table2} tb2
                       ON tb1.{$common} = tb2.{$common}";
        }
        else {
            $select = "SELECT {$value}
                       FROM {$table1} tb1
                       LEFT JOIN {$table2} tb2
                       ON tb1.{$common} = tb2.{$common}
                       WHERE {$maybeWhere}";
        }

        return $select;
    }

    protected function update($table, $column, $value, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            $update = "UPDATE {$table}
                       SET {$column} = {$value}";
        }
        else {
            $update = "UPDATE {$table}
                       SET {$column} = {$value}
                          WHERE {$maybeWhere}";
        }

        return $update;
    }

    protected function delete($table, $where) {
        $delete = "DELETE FROM {$table}
                   WHERE {$where}";

        return $delete;
    }

    /**
 	 * @return FUNCTION Returns the result of queued up sql statement execution
     * found in $stmt.
	 */
    public function execute() {
        try {
            return $this->stmt->execute();
        }
        catch(Exception $err) {
            return $err->getMessage();
        }

    }

    /**
 	 * Invokes execute()
 	 * @return ARRAY Returns an array of rows
	 */
    public function resultSet($fetchType = PDO::FETCH_ASSOC) {
        $exec = $this->execute();
        if(getType($exec) !== 'boolean') {
            return $exec;
        }

        return $this->stmt->fetchAll($fetchType);
    }

    /**
    * Invokes execute()
    * @return ARRAY Returns an array of a single row
    */
    public function single($fetchType = PDO::FETCH_ASSOC) {
        $exec = $this->execute();
        if(getType($exec) !== 'boolean') {
            return $exec;
        }

        return $this->stmt->fetch($fetchType);
    }

    /**
 	 * @return INT Returns number of rows after a successful DELETE
	 */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    /**
     * @return INT Returns the id of the last succesfully INSERTed row
     */
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }


    public function transaction() {
        return $this->pdo->beginTransaction();
    }
    public function inTransaction() {
        return $this->pdo->inTransaction();
    }
    public function commit() {
        return $this->pdo->commit();
    }
    public function rollBack() {
        return $this->pdo->rollBack();
    }

    protected function deleteFiles($files) {
        // If any files fail to be deleted, collect them
        $result =
            array_filter(array_map('deleteFile', $files), function($el) {
                if(getType($el) === 'string') {
                    return $el;
                }
            });

        if(count($result) > 0) {
            return $result;
        }

        return true;
    }

    private function deleteFile($file) {
        $result = unlink($file);

        if(!$result) {
            return $file;
        }

        return $result;
    }

    protected function resizeImage($path, $origName, $name, $size) {
        $img = new Imagick("{$path}/{$origName}");
        $format = 'jpg';

        $img->setImageFormat($format);
        switch ($size) {
            case 128:
            $img->cropThumbnailImage($size, $size);
                $newName = "{$name}_LARGE";
                break;

            case 72:
            $img->cropThumbnailImage($size, $size);
                $newName = "{$name}_MEDIUM";
                break;

            case 48:
            $img->cropThumbnailImage($size, $size);
                $newName = "{$name}_SMALL";
                break;
        }

        $img->writeImage("{$path}/{$newName}.{$format}");
        $img->clear();
        return "{$path}/{$newName}.{$format}";
    }
}
 ?>
