<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'User_Categories';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        User_CategoriesID INT(11) AUTO_INCREMENT PRIMARY KEY,
        UserID INT(11) NOT NULL,
        CategoriesID INT(11) NOT NULL,
        visibility BOOLEAN NOT NULL DEFAULT 1,

        UNIQUE KEY UserID__CategoriesID (UserID, CategoriesID),

        CONSTRAINT fk__User__User_Categories
        FOREIGN KEY (UserID) REFERENCES User(UserID)
            ON DELETE CASCADE
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
