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
    'address'   => 'nunya',
    'phone'     => '555-555-5555',
    'username'  => 'Bobbart',
    'email'     => 'bobbartTheMan',
    'pass'      => 'BobParrBobbat space '
];

$user2 = [
    'firstName' => 'Helen',
    'lastName'  => 'Melen',
    'address'   => '1234 Blue',
    'phone'     => '555-555-5555',
    'username'  => 'MsHelen',
    'email'     => 'helenmelen',
    'pass'      => '1234password'
];

$contact = ['Billy Bob Joe Plantation', 'onlythebest@gradual.decline'];
$editContact = ['Billy Bob Joe Plantation', 'goodemail.jeemail.com'];

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
    'notes' => 'Doesn\'t much care fer opera and such'
];
$editDetails = [
    [
        'column' => 'nickname',
        'value'  => 'EXTREME BOB'
    ],
    [
        'column' => 'birthday',
        'value'  => '01/01/1940'
    ],
    [
        'column' => 'relationship',
        'value'  => 'no.'
    ]
];

$changesBundle = [
    [
        'column' => 'first_name',
        'value' => 'Oh, its BOB!'
    ],
    [
        'column' => 'address',
        'value' => '123 Rural'
    ],
    [
        'column' => 'phone',
        'value' => '1211511551'
    ],
    [
        'column' => 'email',
        'value' => 'sneaky@shady.go'
    ]
];

$email = [
    'replyToEmail' => 'bobbartTheMan',
    'sentByEmail'  => 'bobbartTheMan',
    'subject'      => 'Hey, anybody out there?',
    'body'         => 'It is a cold, dark night. I can\'t see a thing...'
];
$received = ['oneEmail', 'TwoEmail', 'helenmelen', 'no', 'hehhehehe', 'bobbartTheMan'];

$settings = ['100', '250', 0, 1, 0, 'Cozy'];
$testID = 4;

/**********************************Good to go**********************************/
 /****************************************************************************/
// $foo = $db->edit_password(5, 'password', 'NEW');
// $foo = $db->insert_user($user2);
// $foo = $db->get_system_labels('System_LabelsID');
// $foo = $db->get_existing_field($db->userTable, 'email',
                               // "UserID = $testID AND first_name = 'Bob'");
// 11-8
// $foo = $db->insert_contact($testID, $contact, $details);
// $foo = $db->insert_blocked($testID, 'badd00d@jeemail.com');
// $foo = $db->edit_settings($testID, $settings);
// 11-9
// $foo = $db->insert_user_label($testID, 'GExelent??');
// $foo = $db->edit_user($testID, $changesBundle);
// 11-10
// $foo = $db->insert_email($testID, $email, $received);
/***********************************Testing************************************/
 /****************************************************************************/
 $foo = $db->edit_contact($testID, 27, $editContact, $editDetails);
 // ($id, $oldContactID, $contact = NULL,
 //                              $details = NULL) {
 // $foo = $db->edit_blocked();
 // $foo = $db->edit_user_image();
 // $foo = $db->edit_label();

 // $foo = $db->delete_contact();
 // $foo = $db->delete_blocked();
 // $foo = $db->delete_label();

 // $foo = $db->get_user();
 // $foo = $db->get_contacts();
 // $foo = $db->get_blocked();
 // $foo = $db->get_labels();

 // $foo = $db->toggle_visibility();

 // $foo = $db->insert_user_image();
 // TODO: Turn logging off MySQL : SET GLOBAL general_log = 'OFF';
 //                                SET GLOBAL log_output = 'TABLE';
 //                                TRUNCATE TABLE mysql.general_log;
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
