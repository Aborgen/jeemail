<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Images';
    $sql = "CREATE TABLE IF NOT NULL {$table}(
        ImagesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        icon_small VARCHAR(255) NOT NULL UNIQUE,
        icon_medium VARCHAR(255) NOT NULL UNIQUE,
        icon_large VARCHAR(255) NOT NULL UNIQUE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
