<?php
    class db_User extends Database {
        private $userTable = 'User';
        private $imgTable = 'Images';
        private $settingsTable = 'Settings';

        // Junction tables
        private $userSettingsTable = 'User_Settings';

        public function insert_user($user) {
            // TODO: Plaintext password can be no longer than 72 characters
            // when using PASSWORD_BCRYPT
            try {
                $hashpass = $this->encrypt($user['pass']);
                if(!$hashpass) {
                    throw new Exception("Error in password encryption");
                }

                $insertUser = "INSERT INTO {$this->userTable}
                                (first_name, last_name, username,
                                    email, pass)
                               Values
                                (:fname, :lname, :uname, :email, :pass)";
                $this->query($insertUser);
                $this->bind(':fname', $user['firstName']);
                $this->bind(':lname', $user['lastName']);
                $this->bind(':uname', $user['username']);
                $this->bind(':email', $user['email']);
                $this->bind(':pass', $hashpass);
                $this->execute();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-insert');
            }
            return "<h1>Successfully created user \"{$user}\".</h1>";
        }

        public function insert_user_img($id) {
            return;
        }

        public function edit_user($id, $column, $diff) {
            try {
                // This method is not intended to change these values
                if($column === 'UserID' || $column === 'User_ImagesID' ||
                   $column === 'email'  || $column === 'pass') {
                    return;
                }

                $change = "UPDATE {$this->userTable}
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

        public function change_password($id, $inputPass, $diffPass) {
            try {
                $validated = $this->validate_password($id, $inputPass);
                if ($validated) {
                    $hashpass = $this->encrypt($diffPass);
                    if(!$hashpass) {
                        throw new Exception("Error in password encryption");
                    }
                    $change = "UPDATE {$this->userTable}
                               SET pass = value
                                   WHERE UserID = {$id}";
                    $this->query($change);
                    $this->bind('value', $hashpass);
                    $this->execute();
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

        public function change_settings($id, $updateSettings) {
            try {
                list($maxPage, $maxContacts, $replyStyle, $img, $labels, $type)
                    = $updateSettings;
                $change = "SELECT SettingsID FROM {$settingsTable} AS tarID
                           WHERE
                              max_page = :maxPage AND
                           WHERE
                              max_contacts = :maxContacts AND
                           WHERE
                              reply_style = :replyStyle AND
                           WHERE
                              display_img = :img AND
                           WHERE
                              button_labels = :labels AND
                           WHERE
                              display_type = :type";
                $this->query($change);
                $this->bind(':maxPage', $maxPage);
                $this->bind(':maxContacts', $maxContacts);
                $this->bind(':replyStyle', $replyStyle);
                $this->bind(':img', $img);
                $this->bind(':labels', $labels);
                $this->bind(':type', $type);

                $result = sizeOf($this->single()) > 0
                    ? $this->single()['tarID'] : false;

                if(!$result) {
                    $profile = create_settings_profile($id, $updateSettings);
                    if(getType($profile) !== 'boolean') {
                        throw new Exception($profile);
                    }
                }
                else {
                    $change = "UPDATE {$this->userSettingsTable}
                               SET SettingsID = {$result}
                               WHERE UserID = {$id}";
                    $this->query($change);
                    $this->execute();
                }
                return "<h1>Changes saved.</h1>";

            }
            catch(Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-edit', 'settings');
            }
        }


        public function create_settings_profile($id, $updateSettings) {
            try {
                list($maxPage, $maxContacts, $replyStyle, $img, $labels, $type)
                    = $updateSettings;
                $change = "INSERT INTO {$this->userSettingsTable}
                              (max_page, max_contacts, reply_style,
                                  display_img, button_labels, display_type)
                           VALUES
                              (:maxPage, :maxContacts, :replyStyle,
                                  :img, :labels, :type)";
                $this->query($change);
                $this->bind(':maxPage', $maxPage);
                $this->bind(':maxContacts', $maxContacts);
                $this->bind(':replyStyle', $replyStyle);
                $this->bind(':img', $img);
                $this->bind(':labels', $labels);
                $this->bind(':type', $type);
                $this->execute();

                $lastID = $this->lastInsertId();

                $change = "UPDATE {$this->userSettingsTable}
                              SET SettingsID = {$lastID}
                              WHERE UserID = {$id}";
                $this->query($change);
                $this->execute();
            }
            catch(Exception $err) {
                return $err;
            }
            
            return true;
        }

        public function err($reason, $field) {
            switch ($reason) {
                case 'unsuccessful-insert':
                    return "<h1>There was an issue creating your user.
                            Please try again.</h1>";
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
        // PRIVATES
        private function get_pass($id) {
            $this->query("SELECT pass from {$this->table} AS pass
                          WHERE UserID = {$id}");
            return $this->single()['pass'];
        }

        private function validate_password($id, $inputPass) {
            if(password_verify($inputPass, $this->get_pass($id))) {
                return true;
            }
            return false;
        }

        private function encrypt($pass) {
            // As of OCT 25 2017, DEFAULT is bcrypt algorithym,
            // which always will output a 60 char string or false.
            $alg = PASSWORD_DEFAULT;
            $hash = password_hash($pass, $alg);
            return $hash;
        }
    }
 ?>
