<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Themes';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_ThemesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL UNIQUE,
        ThemesID INT(11) NOT NULL

        FOREIGN KEY(UserID)
            REFERENCES UserID
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
