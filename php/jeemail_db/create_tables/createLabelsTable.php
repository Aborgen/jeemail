<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        System_LabelsID INT(11) NOT NULL,
        User_LabelsID INT(11) NOT NULL,
        CategoriesID INT(11) NOT NULL
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
