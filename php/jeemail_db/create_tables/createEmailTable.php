<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Email';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        EmailID INT(11) AUTO_INCREMENT PRIMARY KEY,
        reply_to_email VARCHAR(64) NULL,
        mailed_by VARCHAR(64) NOT NULL,
        signed_by VARCHAR(64) NOT NULL,
        subject VARCHAR(128) NOT NULL,
        body TEXT NOT NULL,
        LabelsID INT(11) NOT NULL,
        time_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
