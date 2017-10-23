<?php
    include './config/config.php';

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $table = 'System_Labels';
    $sql = "CREATE TABLE IF NOT EXISTS {$table}(
        System_LabelsID INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
        );";

    $pdo->exec($sql);
    echo "Created {$table} table";
 ?>


when a new label is created, the system_labels table will first be queried with
the proposed name. If a match is found, an error is raised.

Need a many-to-many table to keep track of nested user-defined labels.

FOR user-defined labels: Each label must be unique and can be used many times.
Label cannot possess the same name as a protected system-label
within system_label table.

There are two types of user-defined labels: parents and children.
Parents must all be unique, while children can have duplicate names within
its unique tree (even same name as parent!). Think variable ghosting in JS.

ex:
    Is all valid.
    Personal -> Receipts, Personal
    Receipts -> Personal, Receipts
    Secrets -> big_ones ->
                        Kennedy Assassination, Area 52
               small_ones, some_as_big_as_your_head
    Is not valid.
    Personal -> Family
    Personal -> Secrets
    Is also not valid.
    Personal ->
        Personal ->
            Personal, Personal
        Personal
    REALLY VALID AND A GOOD IDEA
    Personal ->
        Personal ->
            Personal ->
                Personal ->
                    Personal ->
                        Personal ->
                            Personal
In other words, each user-defined label can be both a parent and
a child.
However, only one in a tree can be root (No parent). Siblings can not have same name.

If label (or tree) already exists due to someone else having your completely
original idea, great! Less work. If the label (or tree) does not
exist, create it. No big deal.

When a user deletes a label, query the many-to-many inbetween table to assess
if label is used by any other user. If it is, no change is made. If there are no
more users utilizing that particular label (or tree), then delete it from the
user_labels table.

Turns out, when you allow user-customization with nesting, things get complicated.

I will be using the Nested Set Model in order to map this relationship in mySQL.

Labels are displayed in alphabetical order. This does not mean, however,
that they must be so within the table. SQL doesn't care. This can be done after
the server makes its query.
