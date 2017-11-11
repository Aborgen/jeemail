<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Sent_Emails';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_Sent_EmailsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        EmailID INT(11) NOT NULL,
        important BOOLEAN NOT NULL DEFAULT 0,
        starred BOOLEAN NOT NULL DEFAULT 0,
        User_CategoriesID INT(11) NOT NULL DEFAULT -100,
        labels VARCHAR(255) NULL,

        UNIQUE KEY UserId__EmailID (UserID, EmailID),

        CONSTRAINT fk__User__User_Sent_Emails
        FOREIGN KEY (UserID) REFERENCES User(UserID)
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
