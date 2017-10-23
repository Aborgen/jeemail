<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        left_most_position INT(11) NOT NULL,
        right_most_position INT(11) NOT NULL
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
