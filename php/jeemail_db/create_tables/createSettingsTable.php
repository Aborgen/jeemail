<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'Settings';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        SettingsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        max_email_per_page ENUM('10', '15', '20', '25', '50', '100') NOT NULL DEFAULT '20',
        max_contacts_show ENUM('50', '100', '250') NOT NULL DEFAULT '50',
        reply_one_or_all BOOLEAN NOT NULL DEFAULT 0,
        display_images BOOLEAN NOT NULL DEFAULT 1,
        btn_labels_or_text BOOLEAN NOT NULL DEFAULT 0,
        ui_display_type ENUM('Comfortable', 'Compact', 'Cozy') NOT NULL DEFAULT 'Comfortable',

        UNIQUE KEY settings__set (max_email_per_page, max_contacts_show,
                                  reply_one_or_all, display_images,
                                  btn_labels_or_text, ui_display_type)
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>
