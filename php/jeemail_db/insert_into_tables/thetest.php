<?php
    require '../../website9/Database.php';
    require './config/config.php';

    $database = new Database();

    // $database->query("LOCK TABLE User_Labels WRITE;");
    // $database->execute();
    echo "HELLO";
    $database->transaction();
    $database->lockOne('User_Labels', 'WRITE');

    try {
        $database->query("INSERT INTO User_Labels (name, left_most_position, right_most_position)
                          VALUES (:name, :left, :right)");
        $database->bind(':name','hohoho');
        $database->bind(':left', 5151);
        $database->bind(':right',5152);
        $database->execute();
        $database->commit();
    }
    catch(Exception $err) {
        echo 'Exception -> ';
        var_dump($err->getMessage());
        $database->rollBack();
        $database->clearCursor();
    }
    $database->query("SHOW OPEN TABLES FROM jeemail");
    $var = ($database->resultSet());
    $database->unlock();
?>
<pre><?php var_dump( $var) ?></pre>
