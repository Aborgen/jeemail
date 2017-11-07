<?php
class Database {
    private $host    = DB_HOST;
    private $user    = DB_USER;
    private $pass    = DB_PASS;
    private $charset = 'utf8mb4';
    private $dbname  = DB_NAME;

    public  $pdo;
    private $error;

    private $stmt;

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
 	 * @param string $placeholder
     * Used as a placeholder for the actual value is from as in ':name'
 	 * @param string $value
     * Value to be assigned to corresponding $placeholder as in 'John Doe'
 	 * @param string $type
     * While $placeholder expects a string, $type determines
     * what type of data the column accepts.
	 */
    public function bind($placeholder, $value, $type=NULL) {
        if(is_null($type)) {
            // If $type is not explicitly sent as a param
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
    }

    public function query($query) {
        $this->stmt = $this->pdo->prepare($query);
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

    public function select($table, $value, $alias = NULL, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            $select = !isset($alias) ?
                "SELECT {$value} FROM {$table}" :
                "SELECT {$value} FROM {$table} AS {$alias}";
        }
        else {
            $select = !isset($alias) ?
                "SELECT {$value} FROM {$table} WHERE {$maybeWhere}" :
                "SELECT {$value} FROM {$table} AS {$alias}
                    WHERE {$maybeWhere}";
        }

        return $select;
    }

    protected function selectJoin($table1, $table2, $value,
                                $common, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            $select = "SELECT {$table1}.{$value}
                       FROM {$table1} tb1
                       LEFT JOIN {$table2} tb2
                       ON tb1.{$common} = tb2.{$common}";
        }
        else {
            $select = "SELECT {$table1}.{$value}
                       FROM {$table1} tb1
                       LEFT JOIN {$table2} tb2
                       ON tb1.{$common} = tb2.{$common}
                       WHERE {$maybeWhere}";
        }

        return $select;
    }

    protected function update($table, $column, $value, $maybeWhere = NULL) {
        if(!isset($maybeWhere)) {
            // TODO: Not sure if this works! Placeholders for column and value
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
            echo 'Exception -> ';
            var_dump($err->getMessage());
        }
    }

    /**
 	 * Invokes execute()
 	 * @return ARRAY Returns an array of rows
	 */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Invokes execute()
    * @return ARRAY Returns an array of a single row
    */
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
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
    public function commit() {
        return $this->pdo->commit();
    }
    public function rollBack() {
        return $this->pdo->rollBack();
    }

    public function lockOne($value, $type) {
        $this->pdo->exec("LOCK TABLE {$value} {$type};");
    }

    public function unlock() {
        $this->pdo->exec("UNLOCK TABLES;");
    }

    /**
     * @return (?) Doesn't return anything, but will INSERT debug info (?)
     */
    public function debugDumpParams(){
        return $this->stmt->debugDumpParams();
    }

    public function clearCursor() {
        return $this->pdo->clearCursor();
    }

    protected function deleteFiles($files) {
        // If any files fail to be deleted, collect them
        $result =
            array_filter(array_map('deleteFile', $files), function($el) {
                if(getType($el) !== 'boolean') {
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

    protected function resizeImage($path, $origName, $name, $size, $format) {
        $img = new Imagick("{$path}/{$origName}");
        $width = $img->getImageWidth() * $size;
        // Setting height to 0 automatically maintains a square aspect ratio
        $img->newImage($width, 0);
        $img->setImageFormat($format);
        switch ($size) {
            case 1.0:
                $newName = "{$name}_LARGE";
                break;

            case 0.5:
                $newName = "{$name}_MEDIUM";
                break;

            case 0.25:
                $newName = "{$name}_SMALL";
                break;
        }

        file_put_contents("{$path}/{$newName}.{$format}", $img);
        return "{$path}/{$newName}.{$format}";
    }
}
 ?>
