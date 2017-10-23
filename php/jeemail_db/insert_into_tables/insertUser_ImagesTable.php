<?php
    include './config/config.php';

    $database = new Database();
    $table = 'User';

    $database->query("INSERT INTO {$table}
        (UserID, icon_small,
        icon_medium, icon_large) VALUES
        (:userID, :small, :medium, :large)
        ");
    // The icon colomns refer to filepaths
    $database->bind(':userID', x);
    $database->bind(':small', x);
    $database->bind(':medium', x);
    $database->bind(':large', x);

    $database->execute();
    echo 'Rows affected: '.$database->rowCount()."<br />";
    echo 'Id of last inserted row: '.$database->lastInsertId();
 ?>
