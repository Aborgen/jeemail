<?php
    include './config/config.php';

    $database = new Database();
    $table = 'Email';

    $database->query("INSERT INTO {$table}
        (reply_to_email, mailed_by,
        signed_by, subject, body, LabelsID) VALUES
        (:reply, :mailed, :signed, :subject, :body, :labelsID)
        ");
    $database->bind(':reply', x);
    $database->bind(':mailed', x);
    $database->bind(':signed', x);
    $database->bind(':subject', x);
    $database->bind(':body', x);
    $database->bind(':labelsID', x);

    $database->execute();
    echo 'Rows affected: '.$database->rowCount()."<br />";
    echo 'Id of last inserted row: '.$database->lastInsertId();
 ?>
