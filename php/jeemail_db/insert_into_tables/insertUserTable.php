<?php
    include './config/config.php';

    $database = new Database();
    $table = 'User';

    $database->query("INSERT INTO {$table}
        (first_name, last_name, username,
        email, pass, User_ImagesID) VALUES
        (:fname, :lname, :username, :email, :pass, :ImagesID)
        ");
    $database->bind(':name', x);
    $database->bind(':username', x);
    $database->bind(':email', x);
    $database->bind(':pass', x);
    $database->bind(':body', x);
    $database->bind(':ImagesID', x);

    $database->execute();
    echo 'Rows affected: '.$database->rowCount()."<br />";
    echo 'Id of last inserted row: '.$database->lastInsertId();
 ?>


<?php
    class db_User extends Database {
        private $table = 'User';
        private $imgTable = 'User_Images';
        private $setTable = 'User_Settings';

        public function insert_user($user) {
            try {
                $insertUser = "INSERT INTO {$this->table}
                                (first_name, last_name, username,
                                    email, pass)
                               Values
                                (:fname, :lname, :uname, :email, :pass)";
                $this->query($insertUser);
                $this->bind(':fname', x);
                $this->bind(':lname', x);
                $this->bind(':uname', x);
                $this->bind(':email', x);
                $this->bind(':pass', x);
                $this->execute();

                $lastID = $this->lastInsertId();
                $imgID = insert_user_img($lastID);
                $this->query("INSERT INTO {$this->table} (User_ImagesID) (:img)");
                $this->bind(':img', x);
                $this->execute();
            }

            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-insert');
            }
            return "<h1>Successfully created user \"{$user}\".</h1>";
        }

        public function encrypt($pass) {
            return;
        }


        public function insert_user_img($id) {
            return;
        }

        public function edit_user($id, $column, $diff) {
            try {
                if($column === 'pass') {
                    return;
                }
                $change = "UPDATE {$this->table}
                            SET {$column} = value
                                WHERE UserID = {$id}";
                $this->query($change);
                $this->bind('value', $diff);
                $this->execute();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-edit', $column);
            }
            return "<h1>Changes saved.</h1>";
        }

        public function get_pass($id) {
            $this->query("SELECT pass from {$this->table} AS pass
                            WHERE UserID = {$id}");
            return $this->single()['pass'];
        }

        public function validate_password($id, $inputPass) {
            if(encrypt($inputPass) === get_pass($id)) {
                return true;
            }
            return false;
        }

        public function change_password($id, $input, $diff) {
            try {
                $validated = $this->validate_pass($id, $input);
                if ($validated) {
                    $change = "UPDATE {$this->table}
                                SET pass = value
                                    WHERE UserID = {$id}";
                    $this->query($change);
                    $this->bind('value', encrypt($diff));
                }
                else {
                    return $this->err('bad-password');
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-edit', 'password');
            }
        }

        public function err($reason, $field = NULL) {
            switch ($reason) {
                case 'unsuccessful-insert':
                    return "<h1>There was an issue creating your user.
                            Please try again.</h1>";
                    break;
                case 'unsuccessful-encryption':
                    return "<h1>There was an issue in password encryption.</h1>";
                    break;

                case 'unsuccessful-edit':
                    return "<h1>Failed to edit {$field}. Please try again.</h1>";
                    break;

                case 'bad-password':
                    return "<h1>Password does not match.</h1>";
                    break;
                default:

                    return "<h1>Oops! Something went wrong.</h1>";
                    break;
            }
        }
    }
 ?>
