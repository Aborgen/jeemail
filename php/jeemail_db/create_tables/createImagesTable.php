<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Images';
    $sql = "CREATE TABLE IF NOT NULL {$table}(
        ImagesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL UNIQUE,
        icon_small VARCHAR(255) NOT NULL,
        icon_medium VARCHAR(255) NOT NULL,
        icon_large VARCHAR(255) NOT NULL

        FOREIGN KEY (UserID)
                REFERENCES UserID
                ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
