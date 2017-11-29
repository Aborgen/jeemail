<?php
    require 'config.php';
    require 'Database.php';
    require 'db_User.php';

    $db = new db_User();
    $userID = intval($_GET['q']);
    $data = $db->get_all_user($userID);
    var_dump($data);
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
