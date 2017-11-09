<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        LabelsID INT(11) NOT NULL,
        visibility BOOLEAN NOT NULL DEFAULT 1,

        UNIQUE KEY UserID__LabelsID (UserID, LabelsID),

        CONSTRAINT fk__User__User_Labels
        FOREIGN KEY (UserID) REFERENCES User(UserID)
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
