<?php
// TODO: TODO: TODO: Search for todos!


    class db_User extends Database {
        public $blockedTable          = 'Blocked';
        public $categoriesTable       = 'Categories';
        public $contactsTable         = 'Contacts';
        public $contactDetailsTable   = 'Contact_Details';
        public $emailTable            = 'emailTable';
        public $imgTable              = 'Images';
        public $labelsTable           = 'Labels';
        public $settingsTable         = 'Settings';
        public $systemLabelsTable     = 'System_Labels';
        public $userTable             = 'User';

        // Junction tables
        public $emailReceivedTable    = 'User_Received_Emails';
        public $emailSentTable        = 'User_Sent_Emails';
        public $userBlockedTable      = 'User_Blocked';
        public $userCategoriesTable   = 'User_Categories';
        public $userContactsTable     = 'User_Contacts';
        public $userImagesTable       = 'User_Images';
        public $userLabelsTable       = 'User_Labels';
        public $userSettingsTable     = 'User_Settings';
        public $userSystemLabelsTable = 'User_System_Labels';
        public $userThemesTable       = 'User_Themes';

        /**
         * INSERT a new user. There are five phases, two of which are optional:
         *      1) The provided password will be hashed.
         *      2) The user details will then be inserted into db.
         *      3) Next, insert_defaults() will be invoked with the newly
         *         created UserID.
         *      4) If $img is provided, it will be sent as an argument
         *         to insert_user_image() (otherwise, default icon is used)
         *      5) Finally, the fk to User_Images will be updated with the
         *         newly inserted User_ImagesID.
         *
         * If--at any point along the way--any issues crop up, there are
         * various checks to ensure that an incomplete user is not created.
         *
         * @param $user ARRAY
         *        An array containing:
         *        'firstName' => STRING
         *        'lastName'  => STRING
         *        'username'  => STRING
         *        'email'     => STRING
         *        'pass'      => STRING
         * @param $img ARRAY
         *        This array will be sent as argument to insert_user_image().
         *        It contains:
         *        'path'     => STRING
         *        'name'     => STRING
         *        'tempFile' => STRING
         *        'format'   => STRING
         */
        public function insert_user($user, $img = NULL) {
            // TODO: Plaintext password can be no longer than 72 characters
            // when using PASSWORD_BCRYPT
            $this->transaction();
            try {
                // Phase #1
                $hashpass = $this->encrypt($user['pass']);
                if(!$hashpass) {
                    throw new Exception("Error in password encryption");
                }
                // Phase #2
                $columns = "(first_name, last_name, username, email, pass)";
                $values = "(:fname, :lname, :uname, :email, :pass)";
                $insert = $this->insert($this->userTable, $columns, $values);
                $this->query($insert);
                $this->bind(':fname', $user['firstName']);
                $this->bind(':lname', $user['lastName']);
                $this->bind(':uname', $user['username']);
                $this->bind(':email', $user['email']);
                $this->bind(':pass',  $hashpass);
                $this->execute();

                // Phase #3
                $userID  = $this->lastInsertId();
                $newUser = $this->insert_defaults($userID);
                if(getType($newUser) !== 'boolean') {
                    throw new Exception($newUser);
                }

                if(isset($img)) {
                    // Phase #4 OPTIONAL
                    $newImg =
                        $this->insert_user_image($img);
                    if(getType($newImg) !== 'boolean') {
                        throw new Exception($newImg);
                    }
                    // Phase #5 OPTIONAL
                    $imgID = $this->lastInsertId();
                    $change = "UPDATE {$this->userTable}
                               SET ImagesID = :imgID
                               WHERE
                                  UserID = :userID";
                    $this->query($change);
                    $this->bind(':imgID', $imgID);
                    $this->bind(':userID', $userID);
                    $this->execute();
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-user');
            }

            $this->commit();
            return "<h1>Successfully created user
                \"{$user['firstName']} {$user['lastName']}\".</h1>";
        }

        /**
         * Used when creating a new user to establish entries in various
         * many-to-many relationship tables.
         *
         * @param $id INT
         *        The unique UserID value of the newly created user.
         */
        protected function insert_defaults($id) {
            try {
                $insert = $this->insert($this->userThemesTable,
                                        '(UserID)', '(:userID)');
                $this->query($insert);
                $this->bind(':userID', $id, PDO::PARAM_INT);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    echo "ERROR THEME";
                    throw new Exception($exec);
                }
                // These two are defined up here in order to not interfere with
                // the statements that come next.
                $lables     = $this->get_system_labels('System_LabelsID');
                $categories = $this->get_categories('CategoriesID');

                $columns = "(UserID, System_LabelsID)";
                $values  = "(:userID, :sysLabelID)";
                $insert  = $this->insert($this->userSystemLabelsTable,
                                         $columns, $values);
                $this->query("{$insert}");
                $this->bind(':userID', $id);
                $this->stmt->bindParam(':sysLabelID', $looped_System_LabelsID,
                                       PDO::PARAM_INT);

                foreach ($lables as $label) {
                    extract($label, EXTR_PREFIX_ALL, 'looped');
                    $exec = $this->execute();
                    if(getType($exec) !== 'boolean') {
                        throw new Exception($exec);
                    }
                }

                $columns = "(UserID, CategoriesID)";
                $values  = "(:userID, :categoriesID)";
                $insert  = $this->insert($this->userCategoriesTable,
                                         $columns, $values);
                $this->query("{$insert}");
                $this->bind(':userID', $id);
                $this->stmt->bindParam(':categoriesID', $looped_CategoriesID,
                                       PDO::PARAM_INT);

                foreach ($categories as $category) {
                    extract($category, EXTR_PREFIX_ALL, 'looped');
                    $exec = $this->execute();
                    if(getType($exec) !== 'boolean') {
                        throw new Exception($exec);
                    }
                }
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return true;
        }
        // TODO: Imagick nonsense, I guess (in Database.php::resizeImage())
        public function insert_user_image($img) {
            // TODO: $name is going to be generated using uniqid() in some way
            list($path, $name, $tempFile, $format) = $img;
            try {
                $imgSmall =
                    $this->resizeImage($path, $tempFile, $name, 0.25, $format);
                $imgMed   =
                    $this->resizeImage($path, $tempFile, $name, 0.5,  $format);
                $imgLarge =
                    $this->resizeImage($path, $tempFile, $name, 1.0,  $format);
                // deleteFiles is located in Database.php and expects an array
                $this->deleteFiles(["{$path}/{$tempFile}.{$format}"]);

                $insert = "INSERT INTO {$this->imagesTable}
                              (icon_small, icon_medium, icon_large)
                           VALUES
                              (:iconSm, :iconMd, :iconLg)";
                $this->query($insert);
                $this->bind(':iconSm', $imgSmall);
                $this->bind(':iconMd', $imgMed);
                $this->bind(':iconLg', $imgLarge);
                $this->execute();
            }

            catch (Exception $err) {
                return $err;
            }

            return true;
        }

        public function insert_email($id, $email, $sent, $received) {
            $this->transaction();
            list($replyEmail, $sentEmail, $subject, $body) = $email;
            try {
                $table   = $this->emailTable;
                $columns = "(reply_to_email, sent_by, subject, body)";
                $values  = "(:reply, :sent, :subject, :body)";
                $insert  = $this->insert($table, $columns, $values);
                $this->query($insert);
                $this->bind(':reply', $replyEmail);
                $this->bind(':sent', $sentEmail);
                $this->bind(':subject', $subject);
                $this->bind(':body', $body);
                $this->execute();

                $emailID = $this->lastInsertId();
                $sent = $this->insert_sent_email($emailID, $sent);
                if(getType($sent) !== 'boolean') {
                    throw new Exception($sent);
                }

                $received = $this->insert_received_email($emailID, $received);
                if(getType($received) !== 'boolean') {
                    throw new Exception($received);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-email');
            }

            $this->commit();
            return "<h2>Email successfully sent!</h2>";
        }

        private function insert_received_email($emailID, $received) {
            try {
                //TODO: Waiting on verify_user
                list($existing, $nonUser) = $this->verify_user($received);
                $table = $this->emailReceivedTable;
                $columns = "(UserID, EmailID)";
                $values = "(:userID, :emailID)";
                $insert = $this->insert($table, $columns, $values);
                $this->query($insert);
                $this->bind(':userID', $extracted_user);
                $this->bind(':emailID', $emailID);
                foreach ($existing as $user) {
                    extract($user, EXTR_PREFIX_ALL, 'extracted');
                    $this->execute();
                }
            }
            catch (Exception $err) {
                return $err;
            }

        }

        private function verify_user($emailOrArray) {
            if(getType($emailOrArray) !== 'array') {
                $table = $this->userTable;
                $where = "email = {$emailOrArray}";
                //TODO: Waiting on question above get_exisiting_field
                $userExists = $this->get_existing_field($table, 'UserID'.
                                                        $where);
                if(getType($userExists) === 'array') {
                    throw new Exception($userExists);
                }

                return $userExists;
            }

            $user = [];
            $fake = [];
            foreach ($emailOrArray as $email) {
                # code...
            }
        }

        public function insert_contact($id, $contact, $details) {
            $this->transaction();
            // list($name, $email) = $contact;
            try {
                // $select =
                //     "SELECT ContactsID FROM {$this->contactsTable} AS tarID
                //      WHERE
                //         name  = :name AND
                //         email = :email";
                // $this->query($select);
                // $this->bind(':name', $name);
                // $this->bind(':email', $email);
                //
                // $contactID = sizeOf($this->single()) > 0
                //     ? $this->single()['tarID'] : false;
                $where = "name = ? AND email = ?";
                $contactID = $this->get_existing_field($this->contactsTable,
                                                       'ContactsID',
                                                       $where, $contact);
                if(getType($contactID) === 'array') {
                    throw new Exception($contactID[0]);
                }

                if(!$contactID) {
                    $newContact = $this->insert_new_contact($contact);
                    if(getType($newContact) !== 'boolean') {
                        throw new Exception($newContact);
                    }

                    $contactID = $this->lastInsertId();
                }

                $insertDetails =
                    $this->insert_contact_details($id, $details);
                if(getType($insertDetails) !== 'boolean') {
                    throw new Exception($insertDetails);
                }

                $detailsID = $this->lastInsertId();

                $insert = "INSERT INTO {$this->userContactsTable}
                              (UserID, ContactsID, Contact_DetailsID)
                           VALUES
                              (:userID, :contactsID, :detailsID)";
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':contactsID', $contactID);
                $this->bind(':detailsID', $detailsID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-contact');
            }

            $this->commit();
            return "<h1>Contact successfully created for {$name}!</h1>";
        }

        private function insert_new_contact($newContact) {
            list($name, $email) = $newContact;
            try {
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

        private function insert_contact_details($id, $details) {
            list($type, $nickname, $company, $jobTitle,
                    $phone, $address, $birthday, $relationship,
                        $website, $notes) = $details;
            try{
                $insert = "INSERT INTO {$this->contactDetailsTable}
                              (type, nickname, company, job_title,
                                  phone, address birthday, relationship,
                                    website, notes)
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

        public function insert_blocked($id, $email) {
            $this->transaction();
            try {
                // $select =
                //     "SELECT BlockedID FROM {$this->blockedTable} AS tarID
                //      WHERE email = :email";
                // $this->query($select);
                // $this->bind(':email', $email);
                //
                //
                // $blockedID = sizeOf($this->single()) > 0
                //     ? $this->single()['tarID'] : false;
                $table = $this->blockedTable;
                $where = "email = ?";
                $blockedID = $this->get_existing_field($table, 'BlockedID',
                                                       $where, [$email]);
                if(getType($blockedID) === 'array') {
                    throw new Exception($blockedID[0]);
                }

                if(!$blockedID) {
                    $newBlocked = $this->insert_new_blocked($email);
                    if(getType($newBlocked) !== 'boolean') {
                        throw new Exception($newBlocked);
                    }

                    $blockedID = $this->lastInsertId();
                }

                $insert = "INSERT INTO {$this->userBlockedTable}
                              (UserID, BlockedID)
                           VALUES
                              (:userID, :blockedID)";
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':blockedID', $blockedID);
                $this->execute();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-blocked');
            }

            $this->commit();
            return "<h1>Email added to blocked list.!</h1>";
        }

        private function insert_new_blocked($email) {
            try {
                $insert = "INSERT INTO {$this->blockedTable}
                              (email)
                           VALUES
                              (:email)";
                $this->query($insert);
                $this->bind(':email', $email);
                $this->execute();
            }
            catch (Exception $err) {
                return $err;
            }

            return true;
        }

        private function insert_settings_profile($updateSettings) {
            list($maxPage, $maxContacts, $replyStyle,
                    $img, $labels, $type) = $updateSettings;
            try {
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
            }
            catch(Exception $err) {
                return $err;
            }

            return true;
        }

        public function insert_user_label($id, $name) {
            $this->transaction();
            try {
                $table   = $this->labelsTable;
                $where   = "name = ?";
                $labelID = $this->get_existing_field($table, 'LabelsID',
                                                     $where, [$name]);
                if(getType($labelID) === 'array') {
                    throw new Exception($labelID[0]);
                }

                if(!$labelID) {
                    $newLabel = $this->insert_new_label($name);
                    if(getType($newLabel) !== 'boolean') {
                        throw new Exception($newLabel);
                    }

                    $labelID = $this->lastInsertId();
                }
                $table   = $this->userLabelsTable;
                $columns = "(UserID, LabelsID)";
                $values  = "(:userID, :labelsID)";
                $insert  = $this->insert($table, $columns, $values);
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':labelsID', $labelID);
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-label');
            }

            $this->commit();
            return "<h1>Successfully created label \"{$name}!\"</h1>";
        }

        private function insert_new_label($name) {
            try {
                $table  = $this->labelsTable;
                $column = "(name)";
                $value  = "(:name)";
                $insert = $this->insert($table, $column, $value);
                $this->query($insert);
                $this->bind(':name', $name);
                $this->execute();
            }
            catch (Exception $err) {
                return $err;
            }

            return true;
        }



        private function edit_fields($id, $table, $changesBundle) {
            // $changesBundle = [
            //     '0' => [
            //         'column' => 'one_column',
            //         'newValue' => 'extreme wheelbarrowing'
            //     ]
            // ]
            $this->transaction();
            try {
                $change = "UPDATE {$table}
                           SET {$extracted_column} = :newValue";
                $this->query($change);
                $this->bind(':newValue', $extracted_newValue);

                foreach ($changesBundle as $change => $value) {
                    // Will result in $extracted_column and $extracted_newValue
                    extract($value, EXTR_PREFIX_ALL, 'extracted');
                    $this->execute();
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', $extracted_column);
            }

            $this->commit();
            return true;
        }
        public function edit_user($id, $changesBundle) {
            $this->transaction();
            try {
                // $change = "UPDATE {$this->userTable}
                //            SET {$column} = :value
                //               WHERE UserID = :userID";
                // $this->query($change);
                // $this->bind(':value', $diff);
                // $this->bind(':userID', $id);
                // $this->execute();
                $table = $this->userTable;
                $this->edit_fields($id, $table, $changesBundle);
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', $column);
            }

            return "<h1>Changes saved.</h1>";
        }

        public function edit_user_image($id, $img) {
            $this->transaction();
            try{
                // First, select the id of the current image row
                $select = "SELECT ImagesID FROM {$this->userTable} AS imgID
                           WHERE UserID = {$id}";
                $this->query($select);
                $oldImgID = $this->single()['imgID'];
                // Second, remove that row
                $remove = "DELETE FROM {$this->imagesTable}
                           WHERE ImagesID = :imgID";
                $this->query($remove);
                $this->execute();
                // Next, add a new row
                $result = $this->insert_user_image($img);
                if(getType($result) !== 'boolean') {
                    throw new Exception($result);
                }
                // Finally, update User to have the new row's ID as
                // a foreign key.
                $newImgID = $this->lastInsertId();
                $change = "UPDATE {$this->userTable}
                           SET ImagesID = :imgID
                           WHERE UserID = :userID";
                $this->query($change);
                $this->bind(':imgID', $imgID);
                $this->bind(':userID', $id);
                $this->execute();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'icon');
            }

            $this->commit();
            return "<h1>Your icon has been updated.</h1>";
        }

        public function edit_contact($id, $contact = NULL, $details = NULL) {
            $this->transaction();
            try {
                if(!isset($contact)) {
                    $table = $this->contactTable;
                    $where = "name = ? AND email = ?";
                    $contactID =
                        $this->get_existing_field($table, 'ContactsID',
                                                  $where, $contact);
                        if(getType($contactID) === 'array') {
                            throw new Exception($contactID[0]);
                        }
                    if(!$contactID) {
                        $result = $this->insert_new_contact($contact);
                        if(getType($result) !== 'boolean') {
                            throw new Exception($result);
                        }
                        $contactID = $this->lastInsertId();
                        //TODO: Garbage collect a possibly useless row
                    }

                    $change = "UPDATE {$this->userContactsTable}
                               SET ContactsID = :contactID
                               WHERE UserID = :userID";
                    $this->query($change);
                    $this->bind(':contactID', $contactID);
                    $this->bind(':userID', $UserID);
                    $this->execute();
                }

                if(!isset($detailsBundle)) {
                    $table = $this->contactDetailsTable;
                    $result = $this->edit_fields($id, $table, $details);
                    if(getType($result) !== 'boolean') {
                        throw new Exception($result);
                    }
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'contact');
            }

            $this->commit();
            return "<h1>Changes saved.</h1>";
        }

        public function edit_blocked($id, $oldID, $blocked) {
            $this->transaction();
            try {
                $table = $this->blockedTable;
                $where = "email = ?";
                $blockedID =
                    $this->get_existing_field($table, 'BlockedID',
                                              $where, [$blocked]);
                if(getType($blockedID) === 'array') {
                    throw new Exception($blockedID[0]);
                }
                if(!$blockedID) {
                    $result = $this->insert_new_blocked($blocked);
                    if(getType($result) !== 'boolean') {
                        throw new Exception($result);
                    }
                    $blockedID = $this->lastInsertId();
                }

                $change = "UPDATE {$this->userBlockedTable}
                           SET BlockedID = :blockedID
                           WHERE UserID = :userID";
                $this->query($change);
                $this->bind(':blockedID', $blockedID);
                $this->bind(':userID', $UserID);
                $this->execute();

                $juncTable = $this->userBlockedTable;
                $deletedBlocked = $this->maybe_delete_unused_row($oldID,
                                                                 $juncTable,
                                                                 $table,
                                                                 'BlockedID');
                if(getType($deletedBlocked) === 'array') {
                    throw new Exception($deletedBlocked[0]);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'blocked email');
            }

            $this->commit();
            return "<h1>Changes saved.</h1>";
        }

        /**
         * Allows changing of user password
         *
         * @param $id INT
         *        The unique id assigned to the user
         * @param $inputPass STRING
         *        The user's password attempt
         * @param $diffPass STRING
         *        The new value of the user's password,
         *        assuming successful validation.
         */
        public function edit_password($id, $inputPass, $diffPass) {
            try {
                $validated = $this->validate_password($id, $inputPass);
                if(getType($validated) !== 'boolean') {
                    throw new Exception($validated);
                }

                if (!$validated) {
                    return $this->err('bad-password');
                }

                $hashpass = $this->encrypt($diffPass);
                if(!$hashpass) {
                    throw new Exception("Error in password encryption");
                }

                $where = 'UserID = :userID';
                $change = $this->update($this->userTable, 'pass',
                                        ':value', $where);
                $this->query($change);
                $this->bind(':value', $hashpass);
                $this->bind(':userID', $id);
                $this->execute();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-edit', 'password');
            }

            return "<h1>Password successfully updated</h1>";
        }

        public function edit_settings($id, $settings) {
            $this->transaction();
            list($maxPage, $maxContacts, $replyStyle,
                    $img, $labels, $type) = $settings;
            try {
                // $select =
                //     "SELECT SettingsID FROM {$this->settingsTable} AS tarID
                //      WHERE
                //         max_page      = :maxPage     AND
                //         max_contacts  = :maxContacts AND
                //         reply_style   = :replyStyle  AND
                //         display_img   = :img         AND
                //         button_labels = :labels      AND
                //         display_type  = :type";
                //
                // $this->query($select);
                // $this->bind(':maxPage', $maxPage);
                // $this->bind(':maxContacts', $maxContacts);
                // $this->bind(':replyStyle', $replyStyle);
                // $this->bind(':img', $img);
                // $this->bind(':labels', $labels);
                // $this->bind(':type', $type);
                //
                // $settingsProfileID = sizeOf($this->single()) > 0
                //     ? $this->single()['tarID'] : false;
                $table = $this->settingsTable;
                $where = "max_page      = ? AND
                          max_contacts  = ? AND
                          reply_style   = ? AND
                          display_img   = ? AND
                          button_labels = ? AND
                          display_type  = ?";
                $settingsProfileID = get_existing_field($table, 'SettingsID',
                                                        $where, $settings);
                if(getType($settingsProfileID) === 'array') {
                    throw new Exception($settingsProfileID[0]);
                }

                if(!$settingsProfileID) {
                    $profile =
                        $this->insert_settings_profile($id, $settings);
                    if(getType($profile) !== 'boolean') {
                        throw new Exception($profile);
                    }

                    $settingsProfileID = $this->lastInsertId();
                }

                $change = "UPDATE {$this->userSettingsTable}
                           SET SettingsID = :settingsID
                           WHERE UserID = :userID";
                $this->query($change);
                $this->bind(':settingsID', $settingsProfileID);
                $this->bind(':userID', $id);
                $this->execute();
            }
            catch(Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'settings');
            }

            $this->commit();
            return "<h1>Changes saved.</h1>";
        }

        public function edit_label($id, $oldID, $name) {
            $this->transaction();
            try {
                $table   = $this->labelTable;
                $where   = "name = ?";
                $labelID =
                    $this->get_existing_field($table, 'LabelsID',
                                              $where, [$name]);
                if(getType($labelID) === 'array') {
                    throw new Exception($labelID[0]);
                }

                if(!$labelID) {
                    $result = $this->insert_new_label($name);
                    if(getType($result) !== 'boolean') {
                        throw new Exception($result);
                    }

                    $labelID = $this->lastInsertId();
                }

                $juncTable = $this->userLabelsTable;
                $where     = "UserID = {$id}";
                $update    = $this->update($table, 'LabelsID', $labelID, $where);
                $this->query($update);
                $this->execute();

                $deletedContact = $this->maybe_delete_unused_row($oldID,
                                                                 $juncTable,
                                                                 $table,
                                                                 'LabelsID');
                if(getType($deletedContact) === 'array') {
                    throw new Exception($deletedContact[0]);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'blocked email');
            }

            $this->commit();
            return "<h1>Changes saved.</h1>";
        }

        public function delete_contact($id, $contactID) {
            $this->transaction();
            try {
                $juncTable = $this->userContactsTable;
                $where = "UserID = {$id} AND ContactsID = {$contactID}";
                $delete = $this->delete($table, $where);
                $this->query($delete);
                $this->execute();

                $table = $this->contactsTable;
                // Might or might not delete the row from the Contacts table.
                $deletedContact = $this->maybe_delete_unused_row($contactID,
                                                                 $juncTable,
                                                                 $table,
                                                                 'ContactsID');
                if(getType($deletedContact !== 'boolean')) {
                    throw new Exception($deletedContact);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-delete', 'contact');
            }

            $this->commit();
            return "<h1>Contact removed.</h1>";
        }

        public function delete_blocked($id, $blockedID) {
            $this->transaction();
            try {
                $juncTable = $this->userBlockedTable;
                $where = "UserID = {$id} AND BlockedID = {$blockedID}";
                $delete = $this->delete($table, $where);
                $this->query($delete);
                $this->execute();

                $table = $this->blockedTable;
                // Might or might not delete the row from the Blocked table.
                $deletedBlocked = $this->maybe_delete_unused_row($blockedID,
                                                                 $juncTable,
                                                                 $table,
                                                                 'BlockedID');
                if(getType($deletedBlocked !== 'boolean')) {
                    throw new Exception($deletedBlocked);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-delete', 'blocked email');
            }

            $this->commit();
            return "<h1>Contact removed.</h1>";
        }

        public function delete_label($id, $labelID) {
            $this->transaction();
            try {
                $juncTable = $this->userLabelsTable;
                $where = "UserID = {$id} AND LabelsID = {$labelID}";
                $delete = $this->delete($table, $where);
                $this->query($delete);
                $this->execute();

                $table = $this->labelsTable;
                // Might or might not delete the row from the Labels table.
                $deleteLabel = $this->maybe_delete_unused_row($labelID,
                                                              $juncTable,
                                                              $table,
                                                              'LabelsID');
                if(getType($deleteLabel !== 'boolean')) {
                    throw new Exception($deleteLabel);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-delete', 'label');
            }

            $this->commit();
            return "<h1>Label removed.</h1>";
        }

        private function maybe_delete_unused_row($id, $juncTable,
                                                 $table, $column) {
            try {
                $where = "{$column} = {$id}";
                // Check to see if anyone is using a row in a
                // many-to-many table.
                $usedRow = $this->get_existing_field($juncTable, $id, $where);
                if(getType($usedRow) === 'array') {
                    throw new Exception($usedRow[0]);
                }
                // If no one is, delete it!
                if(!$usedRow) {
                    $delete = $this->delete($table, $where);
                    $this->query($delete);
                    $this->execute();
                }
            }
            catch (Exception $err) {
                return $err;
            }

            return true;
        }

        private function err($reason, $field = NULL) {
            switch ($reason) {
                case 'unsuccessful-get':
                    return "<h1>There was a problem getting {$field}.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-user':
                    return "<h1>There was an issue creating your user.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-edit':
                    return "<h1>Failed to edit {$field}.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-contact':
                    return "<h1>There was an issue creating the contact.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-blocked':
                    return "<h1>There was an issue in blocking this email
                            address. Please try again.</h1>";
                    break;

                case 'unsuccessful-label':
                    return "<h1>There was an issue in creating the label.
                            Please try again.</h1>";
                    break;

                case 'unsuccessful-delete':
                    return "<h1>Could not remove {$field}.
                            Please try again.</h1>";
                    break;

                case 'bad-password':
                    return "<h1>Passwords do not match.</h1>";
                    break;

                default:
                    return "<h1>Oops! Something went wrong.</h1>";
                    break;
            }
        }

        public function get_user($id) {
            try{
                $user   = $this->userTable;
                $img    = $this->imagesTable;
                $where  = "UserID = {$id}";
                $select = $this->selectJoin($user, $img, '*',
                                            'ImagesID', $where);

                $this->query($select);
                $result = $this->single();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-get', 'user details');
            }

            return $result;
        }

        public function get_contacts($id) {
            try{
                $contacts     = $this->contactsTable;
                $userContacts = $this->userContactsTable;
                $where        = "UserID = {$id}";
                $select       = $this->selectJoin($contacts, $userContacts, '*',
                                                  'ContactsID', $where);

                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-get', 'contacts');
            }

                return $result;
        }

        public function get_blocked($id) {
            try{
                $blocked     = $this->blockedTable;
                $userBlocked = $this->userBlockedTable;
                $where       = "UserID = {$id}";
                $select      = $this->selectJoin($blocked, $userBlocked, '*',
                                                  'BlockedID', $where);
                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-get', 'blocked email list');
            }

                return $result;
        }

        public function get_labels($id) {
            try{
                $labels     = $this->labelsTable;
                $userLabels = $this->userLabelsTable;
                $where      = "UserID = {$id}";
                $select     = $this->selectJoin($labels, $userLabels, '*',
                                                'LabelsID', $where);

                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-get', 'contacts');
            }

                return $result;
        }

        private function get_pass($id) {
            try {
            $select = "SELECT pass FROM {$this->userTable} AS pass
                          WHERE UserID = {$id}";
            $this->query($select);
            $result = $this->single()['pass'];
            }
            catch (Exception $err) {
                // I am turning this exception into an array for a quick
                // and dirty check within validate_password.
                return [$err];
            }

            return $result;
        }
        //TODO: Should this method check for arrays and perform differently,
        //      or should get_existing_fields be written?
        public function get_existing_field($table, $field, $where,
                                           $bind = NULL) {
            try {
                $select = $this->select($table, $field, $where);
                echo count($bind);
                $this->query($select);
                // TODO: UHHHHHHHHHHHHHHHHHHHHHHHHH
                if(isset($bind)) {
                    for ($i=0; $i < count($bind); $i++) {
                        // $this->stmt->bindParams($i, $bind[i]);
                        echo "<br /><br />OH YEAAAAAAAAH<br /><br />";
                        echo "{$i} AND {$bind[$i]}";
                        $this->bind($i+1, $bind[$i]);
                    }
                }

                $exec = $this->single();
                if(getType($exec) !== 'array') {
                    throw new Exception($exec);
                }
                $existingID = sizeOf($exec) > 0
                    ? $exec[0] : false;
            }
            catch (Exception $err) {
                // I am turning this exception into an array for a quick
                // and dirty check where it is called.
                return [$err->getMessage()];
            }

            return $existingID;
        }

        /**
     	 * Queries db for all System Labels
     	 *
     	 * @return ARRAY (or STRING if it errors out)
    	 */
        public function get_system_labels($column) {
            try {
                $select = $this->select($this->systemLabelsTable, $column);
                $this->query($select);
                $labels = $this->resultSet();
            }
            catch (Exception $err) {
                return $err;
            }

            return $labels;
        }

        /**
         * Queries db for all Categories
         *
         * @return ARRAY (or STRING if it errors out)
         */
        public function get_categories($column) {
            try {
                $select = $this->select($this->categoriesTable, $column);
                $this->query($select);
                $categories = $this->resultSet();
            }
            catch (Exception $err) {
                return $err;
            }

            return $categories;
        }

        public function toggle_visibility($id, $typeID, $value, $elementID) {
            try {
                $table = $this->userLabelsTable;
                $where = "UserID = {$id} AND {$typeID} = {$elementID}";
                $update = $this->update($table, 'visibility', $value, $where);
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                return $this->err('unsuccessful-toggle', 'visibility');
            }
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

            echo '<br />uhoh<br />';
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
