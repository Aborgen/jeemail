<?php
    include './config/config.php';

    // Removes excess whitespace from $_POST
    function trim_value(&$value) {
        $value = trim($value);
    }
    /**
 	 * This function is called in order to generate an error
 	 * which will be inserted into the <body> above the <form>
 	 * @param STRING the type of error that is going to be returned
 	 * @param STRING input field that the error is for
 	 * @return STRING this is an HTML <h1> element with the error
     * message within
	 */
    function yield_error($type, $field) {
        switch ($type) {
            case 'missing':
                $error = "<h1>Please fill in the {$field} field!</h1>";
                break;
            case 'missing-all':
                $error = "<h1>Please fill in all fields!</h1>";
                break;
            case 'too-long':
                $field = ucwords($field);
                $error = "<h1>{$field} has a max of 255 characters!</h1>";
                break;
        }
        return $error;
    }

    // If the submit button has not been clicked yet, do nothing
    if(filter_has_var(INPUT_POST, 'submit')) {
        // First, strip any extraneous whitespace from the input strings
        array_walk_recursive($_POST, 'trim_value');
        // Then we create an array of input values
        $raw_inputs = [
            'title'  => $_POST['title'],
            'body'   => $_POST['body'],
            'author' => $_POST['author']
        ];
        // Returns an array of the inputs that is NOT empty
        $non_empty_values = array_filter($raw_inputs, function($element) {
            if(!empty($element)) {
                return $element;
            }
        });
        // Returns an array that IS empty
        $missing_fields = array_diff($raw_inputs, $non_empty_values);
        $errors = [];
        // If there are any inputs that were empty when
        // the submit button was clicked, then return
        // appropriate error messages.
        if(count($missing_fields) > 0) {
            if(count($missing_fields) === 3) {
                $errors[] = yield_error('missing-all', NULL);
            }
            else {
                foreach (array_keys($missing_fields) as $field) {
                    $errors[] = yield_error('missing', $field);
                }
            }
        }
        // Raises errors if 'author' or 'title' input values exceed
        // 255 characters.
        else if(strlen($non_empty_values["title"]) > 255 || strlen(
            $non_empty_values["author"]) > 255) {

            if(strlen($non_empty_values["title"]) > 255) {
                $errors[] = yield_error('too-long', 'title');
            }

            if(strlen($non_empty_values["author"])  > 255) {
                $errors[] = yield_error('too-long', 'author');
            }
        }
        // Otherwise, continue. The $errors variable is unset in order to
        // prevent extraneous work from being done.
        else {
            unset($errors);
            $filtered_values = array_map('htmlspecialchars', $non_empty_values);

            $database = new Database();
            $database->query('INSERT INTO posts (title, body, author) VALUES
                (:title, :body, :author)');

            $database->bind(':title', $filtered_values['title']);
            $database->bind(':body', $filtered_values['body']);
            $database->bind(':author', $filtered_values['author']);
            $database->execute();
            echo 'Rows affected: '.$database->rowCount()."<br />";
            echo 'Id of last inserted row: '.$database->lastInsertId();
        }
    }
?>

<?php include './includes/header.php' ?>
    <title>hello</title>
    <style media="screen">
        label, button[name="submit"], #timeIt {
            display: block;
        }

        input[type="text"] {
            padding: .25rem;
        }

        input[type="text"], textarea[name="body"], button[name="submit"], #timeIt {
            margin-bottom: 2%;
        }

        input[type="text"]:last-of-type {
            margin-bottom: 2.5rem;
        }

        input[type="text"]:hover, input[type="text"]:focus {
            border-color: crimson;
        }
        textarea[name="body"]:hover, textarea[name="body"]:focus {
            border-color: crimson;
        }

        #timeIt {
            font-size: .75rem;
            font-style: oblique;
            font-weight: bold;
            line-height: 1.25rem;
            border: solid thin grey;
            border-radius: 1px;
            padding: 1% 10px;
            width: 27%;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="app">
        <?php if(isset($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <?php echo $error; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href=<?php echo ROOT_URL.'/text.php' ?>>
            <button type="button" name="button">Posts</button>
        </a>
        <form action=<?php echo $_SERVER['PHP_SELF']; ?> method="POST">
            <label for="author">Author *</label>
            <input type="text" name="author" value="">
            <label for="title">Title *</label>
            <input type="text" name="title" value="">
            <label for="body">Body *</label>
            <textarea name="body" rows="8" cols="80"></textarea>
            <span id="timeIt">
                <?php
                    date_default_timezone_set(MST);
                    echo date("D M j G:i:s T Y");
                ?>
            </span>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
<?php include './includes/footer.php' ?>
