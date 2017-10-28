<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Blocked';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        BlockedID INT(11) AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(64) NOT NULL UNIQUE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
