<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        left_most_position INT(11) NOT NULL,
        right_most_position INT(11) NOT NULL,
        visibility BOOLEAN DEFAULT 1

        FOREIGN KEY (UserID)
                REFERENCES UserID
                ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
