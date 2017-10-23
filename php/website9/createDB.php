<?php
    $host    = 'localhost';
    $user    = 'test';
    $pass    = 'test';
    $charset = 'utf8mb4';

    $db      = 'test_posts';
    $table   = 'posts';

    $dsn = "mysql:host=$host;charset=$charset";
    $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);

    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        author VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

    // First, create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$db};");
    // Then use the previously created database
    $pdo->exec("USE {$db};");
    // And create table(s)
    $pdo->exec($sql);
    echo "okay";
 ?>
