<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Categories';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        CategoriesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        visibility BOOLEAN NOT NULL DEFAULT 1
        );";

    $default = "INSERT INTO {$table} (name, visibility)";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
