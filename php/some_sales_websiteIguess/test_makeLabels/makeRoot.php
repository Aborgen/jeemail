<?php
    require '../website9/Database.php';
    require '../jeemail_db/insert_into_tables/config/config.php';
    require '../jeemail_db/insert_into_tables/insertUser_LabelsTable.php';

    // Removes excess whitespace from $_POST
    function trim_value(&$value) {
        $value = trim($value);
    }

    try {
        $labels = new Insert_Label();
    }

    catch (Exception $err) {
        echo "<h1>Unable to connect to database.</h1>";
    }

    if(filter_has_var(INPUT_POST, 'submit')) {
        array_walk_recursive($_POST, 'trim_value');
        $root_label = $_POST['rooty'];
        $result = $labels->insert_root_label($root_label);
        echo $result;
    }

    if(filter_has_var(INPUT_POST, 'submit2')) {
        array_walk_recursive($_POST, 'trim_value');
        $non_root_label = $_POST['non-rooty'];
        if(filter_has_var(INPUT_POST, 'parent')) {
            $opt = $_POST['parent'];
            $result2 = $labels->insert_nonroot_label($opt, $non_root_label);
            echo $result2;
        }
        else {
            echo $labels->err('no-parent');
        }
    }

    $existingLabels = $labels->getLabels();
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Root Labels</title>
    </head>
    <body>
        <?php foreach($existingLabels as $label): ?>
            <pre><?php echo $label['labels']; ?></pre>
        <?php endforeach; ?>
        <form action=<?php echo $_SERVER['PHP_SELF']; ?> method="POST">
            <label for="rooty">Root Label</label>
            <input type="text" name="rooty" value="">
            <button type="submit" name="submit">Submit</button>
        </form>
        <form action=<?php echo $_SERVER['PHP_SELF']; ?> method="POST">
            <label for="non-rooty">Non-Root Label</label>
            <input type="text" name="non-rooty" value="">
            <select class="" name="parent">
                <option hidden disabled selected value> -- select an option -- </option>
                <?php foreach($existingLabels as $label): ?>
                    <option value=<?php echo trim($label['labels'], '&emsp;'); ?>>
                        <?php echo $label['labels']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="submit2">Submit</button>
        </form>
    </body>
</html>
