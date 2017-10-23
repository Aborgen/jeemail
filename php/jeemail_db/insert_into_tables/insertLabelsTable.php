<?php
    include './config/config.php';

    $database = new Database();
    $table = 'Labels';

    $database->query("INSERT INTO {$table}
        (System_LabelsID, User_LabelsID) VALUES
        (:systemID, :labelsID)
        ");
    $database->bind(':systemID', x);
    $database->bind(':labelsID', x);

    $database->execute();
    echo 'Rows affected: '.$database->rowCount()."<br />";
    echo 'Id of last inserted row: '.$database->lastInsertId();
 ?>
