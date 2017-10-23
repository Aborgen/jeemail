<?php
    include './config/config.php';

    $database = new Database();
    $table = 'User';

    $database->query("INSERT INTO {$table}
        (name, username,
        email, pass, User_ImagesID) VALUES
        (:name, :username, :email, :pass, :body, :ImagesID)
        ");
    $database->bind(':name', x);
    $database->bind(':username', x);
    $database->bind(':email', x);
    $database->bind(':pass', x);
    $database->bind(':body', x);
    $database->bind(':ImagesID', x);
    
    $database->execute();
    echo 'Rows affected: '.$database->rowCount()."<br />";
    echo 'Id of last inserted row: '.$database->lastInsertId();
 ?>
