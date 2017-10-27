<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);
    //TODO: Remember to delete this row if associated User is deleted
    $table = 'Contact_Details';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        Contact_DetailsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        type ENUM('Business', 'Personal') NULL,
        nickname VARCHAR(64) NULL,
        company VARCHAR(64) NULL,
        job_title VARCHAR(64) NULL,
        phone VARCHAR(64) NULL,
        address VARCHAR(64) NULL,
        birthday VARCHAR(64) NULL,
        relationship VARCHAR(64) NULL,
        website VARCHAR(64) NULL,
        notes TEXT NULL

        FOREIGN KEY(UserID)
            REFERENCES UserID
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
