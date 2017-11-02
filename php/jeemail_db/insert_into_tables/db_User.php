<?php
// TODO: TODO: TODO: Search for todos!


    class db_User extends Database {
        private $blockedTable          = 'Blocked';
        private $contactsTable         = 'Contacts';
        private $contactDetailsTable   = 'Contact_Details';
        private $imgTable              = 'Images';
        private $settingsTable         = 'Settings';
        private $systemLabelsTable     = 'System_Labels_Table';
        private $userTable             = 'User';

        // Junction tables
        private $userBlockedTable      = 'User_Blocked';
        private $userContactsTable     = 'User_Contacts';
        private $userImagesTable       = 'User_Images';
        private $userSettingsTable     = 'User_Settings';
        private $userSystemLabelsTable = 'User_System_Labels';

        public function insert_user($user, $img = NULL) {
            // TODO: Plaintext password can be no longer than 72 characters
            // when using PASSWORD_BCRYPT
            $this->transaction();
            list($firstName, $lastName, $username, $email, $pass) = $user;
            try {
                $hashpass = $this->encrypt($pass);
                if(!$hashpass) {
                    throw new Exception("Error in password encryption");
                }

                $insert = "INSERT INTO {$this->userTable}
                                (first_name, last_name, username,
                                    email, pass, ImagesID)
                               Values
                                (:fname, :lname, :uname, :email,
                                    :pass)";
                $this->query($insert);
                $this->bind(':fname', $firstName);
                $this->bind(':lname', $lastName);
                $this->bind(':uname', $username);
                $this->bind(':email', $email);
                $this->bind(':pass',  $hashpass);
                $this->execute();

                $userID  = $this->lastInsertId();
                $newUser = $this->insert_defaults($userID);
                if(getType($newUser) !== 'boolean') {
                    throw new Exception($newUser);
                }

                if(isset($img)) {
                    $newImg =
                        $this->insert_user_image($img);
                    if(getType($newImg) !== 'boolean') {
                        throw new Exception($newImg);
                    }

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
                var_dump($err->getMessage());
                $this->rollBack();
                return $this->err('unsuccessful-user');
            }

            $this->commit();
            return "<h1>Successfully created user
                \"{$firstName} {$lastName}\".</h1>";
        }

        private function insert_defaults($id) {
            try {
                $insert = "INSERT INTO {$this->userThemesTable}
                              (UserID)
                           VALUES
                              (:userID)";
                $this->bind(':userID', $id);
                $this->execute();

                $lables     = $this->get_system_labels();
                $categories = $this->get_categories();

                $insert = "INSERT INTO {$this->userSystemLabelsTable}
                              (UserID, SystemLabelsID)
                           VALUES
                              (:userID, :sysLabelID)";
                $this->bind(':userID', $id);
                $this->bind(':sysLabelID', $looped_label);
                foreach ($lables as $label) {
                    extract($label, EXTR_PREFIX_ALL, 'looped');
                    $this->execute();
                }

                $insert = "INSERT INTO {$this->userCategoriesTable}
                              (UserID, CategoriesID)
                           VALUES
                              (:userID, :categoriesID)";
                $this->bind(':userID', $id);
                $this->bind(':categoriesID', $looped_category);
                foreach ($categories as $category) {
                    extract($label, EXTR_PREFIX_ALL, 'looped');
                    $this->execute();
                }
            }
            catch (Exception $err) {
                return $err;
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

        public function insert_contact($id, $contact, $details) {
            $this->transaction();
            list($name, $email) = $contact;
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
                $table = $this->contactsTable;
                $where = "name = ? AND email = ?";
                $contactID = $this->get_existing_field($table, 'ContactsID',
                                                       $where, $contact);

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
                $this->execute();
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
        // TODO: Refactor this method to work generally. edit_fields!
        //       Should be able to work with edit_user, edit_contact, edit_blocked!

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
                $newImg = $this->insert_user_image($img);
                if(getType($newImg) !== 'boolean') {
                    throw new Exception($newImg);
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
                    if(!$contactID) {
                        $this->insert_new_contact($contact);
                        $contactID = $this->lastInsertId();
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

        public function edit_blocked($id, $blocked) {
            $this->transaction();
            try {
                $table = $this->blockedTable;
                $where = "email = ?";
                $blockedID =
                    $this->get_existing_field($table, 'BlockedID',
                                              $where, $blocked);
                if(!$blockedID) {
                    $this->insert_new_blocked($blocked);
                    $blockedID = $this->lastInsertId();
                }

                $change = "UPDATE {$this->userBlockedTable}
                           SET BlockedID = :blockedID
                           WHERE UserID = :userID";
                $this->query($change);
                $this->bind(':blockedID', $blockedID);
                $this->bind(':userID', $UserID);
                $this->execute();
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

        public function edit_password($id, $inputPass, $diffPass) {
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
                                   WHERE UserID = :userID";
                    $this->query($change);
                    $this->bind(':value', $hashpass);
                    $this->bind(':userID', $id);
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

        private function err($reason, $field = NULL) {
            switch ($reason) {
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

                case 'bad-password':
                    return "<h1>Passwords do not match.</h1>";
                    break;

                default:
                    return "<h1>Oops! Something went wrong.</h1>";
                    break;
            }
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

        public function get_existing_field($table, $field, $where, $bind) {
            try {
                $select =
                    "SELECT {$field} FROM {$table} AS tarID
                     WHERE
                        {$where}";
                $this->query($select);
                for ($i=1; $i < count($bind); $i++) {
                    $this->bind($i, $bind[i]);
                }

                $existingID = sizeOf($this->single()) > 0
                    ? $this->single()['tarID'] : false;
            }
            catch (Exception $err) {
                return $err;
            }

            return $existingID;
        }

        public function get_system_labels() {
            try {
                $select =
                    "SELECT name FROM {$this->systemLabelsTable} AS label";
                $this->query($select);
                $labels = $this->resultSet();
            }
            catch (Exception $err) {
                return $err;
            }

            return $labels;
        }

        public function get_categories() {
            try {
                $select =
                    "SELECT name FROM {$this->categoriesTable} AS category";
                $this->query($select);
                $categories = $this->resultSet();
            }
            catch (Exception $err) {
                return $err;
            }

            return $categories;
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
