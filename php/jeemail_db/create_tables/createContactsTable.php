<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Contacts';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        ContactsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(64) NOT NULL UNIQUE,
        email VARCHAR(64) NOT NULL UNIQUE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
