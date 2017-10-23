<?php
include './config/config.php';

$database = new Database;
$database->query('SELECT * FROM posts');
// $database->bind(':author', 'Billy Boy');
$posts = $database->resultSet();

$database = NULL;
 ?>


<?php include './includes/header.php' ?>
        <title>BLERG</title>
    </head>
    <body>
        <?php foreach ($posts as $post): ?>
            <span>
                <strong style="border-bottom:2px solid #000;;display:inline">
                    From:
                 </strong>
                <?php echo $post['author'] ?>
            </span>
            <p><?php echo $post['title'] ?></p>
            <pre><?php echo $post['body']; ?></pre>
            <pre style="border:2px solid #000;font-weight:bold;display:inline"><?php echo $post['created_at'] ?></pre>
            <hr>
        <?php endforeach; ?>
        <a href=<?php echo ROOT_URL ?>><button type="button" name="button">Back</button></a>
<?php include './includes/footer.php' ?>
