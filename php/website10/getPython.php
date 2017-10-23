<?php
    $json = json_decode(file_get_contents('../../../../python/synthesizeEmails/data.json'));
    // $python = `python t.py`;
 ?>
<?php foreach ($json as $email): ?>
    <pre><?php var_dump($email); ?></pre>
<?php endforeach; ?>
