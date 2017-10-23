<?php
    $host    = 'localhost';
    $user    = 'test';
    $pass    = 'test';
    $charset = 'utf8mb4';
    $db      = 'jeemail';

    $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Email';
    $table2 = 'Labels';
    $table3 = 'System_Labels';
    $table4 = 'User_Labels';
    $table5 = 'Important_Label';
    $table6 = 'Starred_Label';
    $table7 = 'Chats_Label';
    $table8 = 'Sent_Mail_Label';
    $table9 = 'Drafts_Label';
    $table10 = 'All_Mail_Label';
    $table11 = 'Spam_Label';
    $table12 = 'Trash_Label';
    $table13 = 'Notes_Label';
    $table14 = 'Personal_Label';
    $table15 = 'Receipts_Label';
    $table16 = 'Travel_Label';
    $table17 = 'Work_Label';

    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        EmailID INT(11) AUTO_INCREMENT PRIMARY KEY,
        reply_to_email VARCHAR(64) NULL,
        mailed_by VARCHAR(64) NOT NULL,
        signed_by VARCHAR(64) NOT NULL,
        subject VARCHAR(128) NOT NULL,
        body TEXT NOT NULL,
        labels VARCHAR(255) NOT NULL,
        important BOOLEAN DEFAULT 0,
        starred BOOLEAN DEFAULT 0,
        time_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

    $sql2 = "CREATE TABLE IF NOT EXISTS {$table2}(
        LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        User_LabelsID INT(11) NOT NULL

        );";

    $sql3 = "CREATE TABLE IF NOT EXISTS {$table3}(
        System_LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        Important_LabelID INT(11) NOT NULL,
        Starred_LabelID INT(11) NOT NULL,
        Chats_LabelID INT(11) NOT NULL,
        Sent_Mail_LabelID INT(11) NOT NULL,
        Drafts_LabelID INT(11) NOT NULL,
        All_Mail_LabelID INT(11) NOT NULL,
        Spam_LabelID INT(11) NOT NULL,
        Trash_LabelID INT(11) NOT NULL
        );";

    $sql4 = "CREATE TABLE IF NOT EXISTS {$table4}(
        User_LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        Notes_LabelID INT(11) NOT NULL,
        Personal_LabelID INT(11) NOT NULL,
        Receipts_LabelID INT(11) NOT NULL,
        Travel_LabelID INT(11) NOT NULL,
        Work_LabelID INT(11) NOT NULL
        );";
//              ******************************************
    $sql5 = "CREATE TABLE IF NOT EXISTS {$table5}(
        Important_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        important BOOLEAN DEFAULT 0
        );";

    $sql6 = "CREATE TABLE IF NOT EXISTS {$table6}(
        Starred_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        starred BOOLEAN DEFAULT 0
        );";

    $sql7 = "CREATE TABLE IF NOT EXISTS {$table7}(
        Chats_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        chats BOOLEAN DEFAULT 0
        );";

    $sql8 = "CREATE TABLE IF NOT EXISTS {$table8}(
        Sent_Mail_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        sent_mail BOOLEAN DEFAULT 0
        );";

    $sql9 = "CREATE TABLE IF NOT EXISTS {$table9}(
        Drafts_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        drafts BOOLEAN DEFAULT 0
        );";

    $sql10 = "CREATE TABLE IF NOT EXISTS {$table10}(
        All_Mail_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        all_mail BOOLEAN DEFAULT 0
        );";

    $sql11 = "CREATE TABLE IF NOT EXISTS {$table11}(
        Spam_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        spam BOOLEAN DEFAULT 0
        );";

    $sql12 = "CREATE TABLE IF NOT EXISTS {$table12}(
        Trash_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        trash BOOLEAN DEFAULT 0
        );";
//              ******************************************
    $sql13 = "CREATE TABLE IF NOT EXISTS {$table13}(
        Notes_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        notes BOOLEAN DEFAULT 0
        );";

    $sql14 = "CREATE TABLE IF NOT EXISTS {$table14}(
        Personal_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        personal BOOLEAN DEFAULT 0
        );";

    $sql15 = "CREATE TABLE IF NOT EXISTS {$table15}(
        Receipts_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        receipts BOOLEAN DEFAULT 0
        );";

    $sql16 = "CREATE TABLE IF NOT EXISTS {$table16}(
        Travel_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        travel BOOLEAN DEFAULT 0
        );";

    $sql17 = "CREATE TABLE IF NOT EXISTS {$table17}(
        Work_LabelID INT(11) AUTO_INCREMENT PRIMARY KEY,
        System_LabelsID INT(11) NOT NULL,
        work BOOLEAN DEFAULT 0
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
