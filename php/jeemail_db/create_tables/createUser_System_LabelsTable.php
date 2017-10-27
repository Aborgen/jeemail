<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_System_Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_System_LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        System_LabelsID INT(11) NOT NULL,
        visibility BOOLEAN NOT NULL DEFAULT 1

        FOREIGN KEY (UserID)
                REFERENCES UserID
                ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
