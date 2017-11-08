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
    'type' => 'onlythebest@gradual.decline',
    'nickname' => 'onlythebest@gradual.decline',
    'company' => 'onlythebest@gradual.decline',
    'jobTitle' => 'onlythebest@gradual.decline',
    'phone' => 'onlythebest@gradual.decline',
    'address' => 'onlythebest@gradual.decline',
    'birthday' => 'onlythebest@gradual.decline',
    'relationship' => 'onlythebest@gradual.decline',
    'website' => 'onlythebest@gradual.decline',
    'notes' => 'onlythebest@gradual.decline',
];

/**********************************Good to go**********************************/
 /****************************************************************************/
// $foo = $db->edit_password(5, 'password', 'NEW');
// $foo = $db->insert_user($user);
// $foo = $db->get_system_labels('System_LabelsID');
// $foo = $db->get_existing_field($db->userTable, 'email',
// "UserID = 164 AND first_name = 'Bob'");

/***********************************Testing************************************/
 /****************************************************************************/
$foo = $db->insert_contact(164, $contact, $details);
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
