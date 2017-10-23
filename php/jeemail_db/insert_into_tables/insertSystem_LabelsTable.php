<?php
    include './config/config.php';

    $database = new Database();
    $table = 'System_Labels';

    $database->query("INSERT INTO {$table}
        (label_name) VALUES
        (:labelName)
        ");
    $database->bind(':labelName', x);

    $database->execute();
    echo 'Rows affected: '.$database->rowCount()."<br />";
    echo 'Id of last inserted row: '.$database->lastInsertId();
 ?>
