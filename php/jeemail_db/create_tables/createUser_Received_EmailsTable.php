<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Received_Emails';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_Received_EmailsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        EmailID INT(11) NOT NULL,
        important BOOLEAN NOT NULL DEFAULT 0,
        starred BOOLEAN NOT NULL DEFAULT 0

        FOREIGN KEY(UserID)
            REFERENCES UserID
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
