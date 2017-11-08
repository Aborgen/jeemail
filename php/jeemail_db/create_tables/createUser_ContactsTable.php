<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Contacts';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_ContactsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        ContactsID INT(11) NOT NULL,
        Contact_DetailsID INT(11) NOT NULL,

        UNIQUE KEY User_Contact_Details (UserID, ContactsID, Contact_DetailsID),

        CONSTRAINT fk__User__User_Contacts
        FOREIGN KEY (UserID) REFERENCES User(UserID)
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
