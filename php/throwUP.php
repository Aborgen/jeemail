<?php
require './website9/Database.php';
require './jeemail_db/insert_into_tables/config/config.php';
require './jeemail_db/insert_into_tables/db_User.php';
echo 'Testing all the live long day'."<br />";
echo "<hr />";
$db = new db_User();
$user = [
    'firstName' => 'Bob',
    'lastName'  => 'Parr',
    'username'  => 'Bobbart',
    'email'     => 'bobbartTheMan',
    'pass'      => 'BobParrBobbat space '
];

$contact = ['Billy Bob Joe Plantation', 'onlythebest@gradual.decline'];

$details = [
    'type' => 'Personal',
    'nickname' => 'Ol\' Bobby',
    'company' => '',
    'jobTitle' => '',
    'phone' => '0215541325741158741444',
    'address' => '123 Rural Rd.',
    'birthday' => '',
    'relationship' => 'Eh.',
    'website' => '',
    'notes' => 'Doesn\'t much care fer opera and such',
];

$settings = ['100', '250', 0, 1, 0, 'Cozy'];

/**********************************Good to go**********************************/
 /****************************************************************************/
// $foo = $db->edit_password(5, 'password', 'NEW');
// $foo = $db->insert_user($user);
// $foo = $db->get_system_labels('System_LabelsID');
// $foo = $db->get_existing_field($db->userTable, 'email',
                               // "UserID = 164 AND first_name = 'Bob'");
// 11-8
// $foo = $db->insert_contact(164, $contact, $details);
// $foo = $db->insert_blocked(164, 'badd00d@jeemail.com');
// $foo = $db->edit_settings(164, $settings);
/***********************************Testing************************************/
 /****************************************************************************/
 $foo = $db->insert_user_label(164, 'Good Label');
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <style media="screen">
            body {
                background: #ddd;
                font-size: 1.5rem;
            }
            p {
                color: red;
                background: slategrey;
                display: inline;
                font-size: 2rem;
            }
            pre {
                color: darkgreen;
            }
        </style>
        <?php if(getType($foo) !== 'array'): ?>
            <p><?php echo $foo ?></p>
            <script>
                const bod = document.querySelector('body');
                bod.setAttribute('style', 'background: #888')
            </script>
        <?php else: ?>
            <pre><?php var_dump ($foo) ?></pre>
        <?php endif; ?>
    </body>
</html>
