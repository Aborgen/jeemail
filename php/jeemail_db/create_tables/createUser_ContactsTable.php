<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Contacts';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_ContactsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        ContactsID INT(11) NOT NULL,
        Contact_DetailsID INT(11) NOT NULL UNIQUE

        FOREIGN KEY(UserID)
            REFERENCES UserID
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
