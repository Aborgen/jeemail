<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Themes';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        ThemesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        imgpath VARCHAR(255) NOT NULL,
        textcolor VARCHAR(64) NOT NULL,
        assetcolor VARCHAR(64) NOT NULL
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
