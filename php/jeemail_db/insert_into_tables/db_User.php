<?php
// TODO: TODO: TODO: Search for todos!


    class db_User extends Database {
        private $blockedTable      = 'Blocked';
        private $contactsTable     = 'Contacts';
        private $imgTable          = 'Images';
        private $settingsTable     = 'Settings';
        private $userTable         = 'User';

        // Junction tables
        private $userBlockedTable  = 'User_Blocked';
        private $userContactsTable = 'User_Contacts';
        private $userSettingsTable = 'User_Settings';

        public function insert_user($user) {
            // TODO: Plaintext password can be no longer than 72 characters
            // when using PASSWORD_BCRYPT
            try {
                $hashpass = $this->encrypt($user['pass']);
                if(!$hashpass) {
                    throw new Exception("Error in password encryption");
                }

                $insert = "INSERT INTO {$this->userTable}
                                (first_name, last_name, username,
                                    email, pass)
                               Values
                                (:fname, :lname, :uname, :email, :pass)";
                $this->query($insert);
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

        public function insert_contact($id, $newOrOldContact, $details) {
            $this->transaction();
            try {
                list($name, $email) = $newOrOldContact;
                $select =
                    "SELECT ContactsID FROM {$this->ContactsTable} AS tarID
                     WHERE
                        name  = :name AND
                        email = :email";
                $this->query($select);
                $this->bind(':name', $name);
                $this->bind(':email', $email);

                $contactID = sizeOf($this->single()) > 0
                    ? $this->single()['tarID'] : false;

                if(!$contactID) {
                    $newContact = insert_new_contact($id, $newOrOldContact);
                    if(getType($newContact) !== 'boolean') {
                        throw new Exception($newContact);
                    }

                    $contactID = $this->lastInsertId();
                }
                // TODO: THIS IS NOT COMPLETE!
                // TODO: The line below is replacing the second
                //       insert_contact_details.
                $insertDetails =
                    $this->insert_contact_details($id, $details);
                if(getType($insertDetails) !== 'boolean') {
                    throw new Exception($insertDetails);
                }

                $detailsID = lastInsertId();

                $insert = "INSERT INTO {$this->userContactsTable}
                              (UserID, ContactsID, Contact_DetailsID)
                           VALUES
                              (:userID, :contactsID, :detailsID)";
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':contactsID', $contactID);
                $this->bind(':detailsID', $detailsID);
                $this->execute();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                $this->clearCursor();
                return $this->err('unsuccessful-contact');
            }

            $this->commit();
            $this->clearCursor();
            return "<h1>Contact successfully created!</h1>";
        }

        public function insert_new_contact($id, $newContact) {
            try {
                list($name, $email) = $newContact;
                $insert = "INSERT INTO {$this->contactsTable}
                              (name, email)
                           VALUES
                              (:name, :email)";
                $this->query($insert);
                $this->bind(':name', $name);
                $this->bind(':email', $email);
                $this->execute();
            }
            catch (Exception $err) {
                return $err;
            }

            return true;
        }
        // TODO: Does this need to be a private function?
        public function insert_contact_details($id, $details) {
            try{
                list($type, $nickname, $company, $jobTitle, $phone, $address,
                        $birthday, $relationship, $website, $notes) = $details;

                $insert = "INSERT INTO {$this->contactDetails}
                              (type, nickname, company, job_title, phone, address
                                  birthday, relationship, website, notes)
                           VALUES
                              (:type, :nickname, :company, :jobTitle, :phone,
                                  :address, :birthday, :relationship,
                                     :website, :notes)";
                $this->query($insert);
                $this->bind(':type', $type);
                $this->bind(':nickname', $nickname);
                $this->bind(':company', $company);
                $this->bind(':jobTitle', $jobTitle);
                $this->bind(':phone', $phone);
                $this->bind(':address', $address);
                $this->bind(':birthday', $birthday);
                $this->bind(':relationship', $relationship);
                $this->bind(':website', $website);
                $this->bind(':notes', $notes);
                $this->execute();
            }
            catch (Exception $err) {
                return $err;
            }

            return true;
        }

        public function edit_user($id, $column, $diff) {
            try {
                // This method is not intended to change these values
                if($column === 'UserID' || $column === 'User_ImagesID' ||
                   $column === 'email'  || $column === 'pass') {
                    return;
                }

                $change = "UPDATE {$this->userTable}
                           SET {$column} = :value
                              WHERE UserID = {$id}";
                $this->query($change);
                $this->bind(':value', $diff);
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
                if(getType($validated !== "boolean")) {
                    throw new Exception($validated);
                }

                if ($validated) {
                    $hashpass = $this->encrypt($diffPass);
                    if(!$hashpass) {
                        throw new Exception("Error in password encryption");
                    }
                    $change = "UPDATE {$this->userTable}
                               SET pass = :value
                                   WHERE UserID = {$id}";
                    $this->query($change);
                    $this->bind(':value', $hashpass);
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
            $this->transaction();
            try {
                list($maxPage, $maxContacts, $replyStyle, $img, $labels, $type)
                    = $updateSettings;
                $select =
                    "SELECT SettingsID FROM {$this->userSettingsTable} AS tarID
                     WHERE
                        max_page = :maxPage AND
                        max_contacts = :maxContacts AND
                        reply_style = :replyStyle AND
                        display_img = :img AND
                        button_labels = :labels AND
                        display_type = :type";

                $this->query($select);
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
            }
            catch(Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                $this->clearCursor();
                return $this->err('unsuccessful-edit', 'settings');
            }

            $this->commit();
            $this->clearCursor();
            return "<h1>Changes saved.</h1>";
        }


        public function create_settings_profile($id, $updateSettings) {
            $this->transaction();
            try {
                list($maxPage, $maxContacts, $replyStyle, $img, $labels, $type)
                    = $updateSettings;
                $insert = "INSERT INTO {$this->userSettingsTable}
                              (max_page, max_contacts, reply_style,
                                  display_img, button_labels, display_type)
                           VALUES
                              (:maxPage, :maxContacts, :replyStyle,
                                  :img, :labels, :type)";
                $this->query($insert);
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
                $this->rollBack();
                $this->clearCursor();
                return $err;
            }

            $this->commit();
            $this->clearCursor();
            return true;
        }

        public function err($reason, $field = NULL) {
            switch ($reason) {
                case 'unsuccessful-insert':
                    return "<h1>There was an issue creating your user.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-contact':
                    return "<h1>There was an issue creating the contact.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-blocked':
                    return "<h1>There was an issue in blocking this email.
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
            try {
            $this->query("SELECT pass from {$this->table} AS pass
                          WHERE UserID = {$id}");
            $result = $this->single()['pass'];
            }
            catch (Exception $err) {
                // I am turning this exception into an array for
                // a quick and dirty check within validate_password.
                return [$err];
            }

            return $result;
        }

        private function validate_password($id, $inputPass) {
            $currentPass = $this->get_pass($id);
            // In this case, there was an issue with the SQL query in get_pass.
            // This is sending the exception back up to where it was called.
            if(getType($currentPass) === 'array') {
                return $currentPass[0];
            }

            if(password_verify($inputPass, $currentPass)) {
                return true;
            }

            return false;
        }

        private function encrypt($pass) {
            // As of OCT 25 2017, DEFAULT is bcrypt algorithym,
            // which will output a 60 char string or false on failure.
            $alg = PASSWORD_DEFAULT;
            $hash = password_hash($pass, $alg);
            return $hash;
        }
    }
 ?>
