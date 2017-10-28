<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
