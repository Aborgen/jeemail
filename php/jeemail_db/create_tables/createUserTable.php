<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        UserID INT(11) AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(64) NOT NULL,
        last_name VARCHAR(64) NOT NULL,
        username VARCHAR(64) NOT NULL,
        email VARCHAR(64) NOT NULL,
        pass VARCHAR(255) NOT NULL,
        User_ImagesID INT(11) NOT NULL
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
