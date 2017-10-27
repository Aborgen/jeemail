<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Settings';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        SettingsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        max_page ENUM('10', '15', '20', '25', '50', '100') NOT NULL DEFAULT '20',
        max_contacts ENUM('50', '100', '250') NOT NULL DEFAULT '50',
        reply_style BOOLEAN NOT NULL DEFAULT 0,
        display_img BOOLEAN NOT NULL DEFAULT 0,
        button_labels BOOLEAN NOT NULL DEFAULT 0,
        display_type ENUM('Comfortable', 'Compact', 'Cozy') NOT NULL DEFAULT 'Comfortable'
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
