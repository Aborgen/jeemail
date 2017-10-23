<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Images';
    $sql = "CREATE TABLE IF NOT NULL {$table}(
        User_ImagesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        icon_small VARCHAR(255) NOT NULL,
        icon_medium VARCHAR(255) NOT NULL,
        icon_large VARCHAR(255) NOT NULL
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
