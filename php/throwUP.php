<?php
// TODO: recreate User table
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
    'gender'    => 'female',
    'birthday'  => '1968-12-25',
    'address'   => '1234 Blue',
    'phone'     => '555-555-5555',
    'username'  => 'MsHelen',
    'email'     => 'helenmelen',
    'pass'      => '1234password'
];
$noreply = [
    'firstName' => 'noreply',
    'lastName'  => 'noreply',
    'gender'    => '',
    'birthday'  => '',
    'address'   => '',
    'phone'     => '',
    'username'  => 'noreply',
    'email'     => 'noreply',
    'pass'      => '1234password'
];

$contact = ['Billy Bob Joe Plantation', 'onlythebest@gradual.decline'];
$editContact = ['Billy Bob Joe', 'goodemail@jeemail.com'];

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
$received = ['no', 'hehhehehe', 'helenmelen'];
$img = [
    'path'     => '/home/thatman/Documents/__Code_Personal/react-clones/' .
                  'jeemail/php/jeemail_db/insert_into_tables',
    'tempFile' => 'heh'
];

$settings = ['100', '250', 0, 1, 0, 'Cozy'];
$editSettings = ['100', '250', 0, 1, 0, 'Cozy'];
$testID = 4;

/**********************************Good to go**********************************/
 /****************************************************************************/
// 11-7
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
// $foo = $db->insert_email(4, $email, $received);
// 11-13
// $foo = $db->edit_contact(3, 78, $editContact, $editDetails);
// $foo = $db->edit_blocked(3, 8, 'orangegrass@bad.net');
// $foo = $db->edit_label(3, 10, 'Bibble Babble');
// 11-14
// $foo = $db->delete_contact($testID, 79);
// $foo = $db->delete_blocked($testID, 16);
// $foo = $db->delete_label($testID, 9);
// $foo = $db->get_user($testID);
// $foo = $db->get_contacts($testID);
// $foo = $db->get_blocked($testID);
// $foo = $db->get_labels($testID);
// $foo = $db->get_system_labels($testID);
// $foo = $db->get_categories($testID);
// 11-15
// $foo = $db->get_received_emails($testID);
// $foo = $db->get_sent_emails(3);
// $foo = $db->get_all_user(3);
// $foo = $db->toggle_visibility(3, 'User_Categories', 'CategoriesID', 2);
// 11-16
// $foo = $db->insert_user_image($img);
// $foo = $db->edit_user_image($testID, $img);
// $foo = $db->edit_settings($testID, $editSettings);
/***********************************Testing************************************/
 /****************************************************************************/
 // TODO: chmod 777 /home/thatman/Documents/__Code_Personal/react-clones/jeemail/php/jeemail_db/insert_into_tables/DEFAULT
 //       Need to find a way to do this in a not-so-sledgehammery way.
 //       (755) is set to me and root being able to write here?
 // TODO: Start populating DB with users! :O
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
        <pre><?php echo $foo['emails']['received'][4]['body'] ?></pre>
    </body>
</html>
