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
         * @param $img ARRAY (or NULL)
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
                $columns = "(first_name, last_name, address, phone,
                             username, email, pass)";
                $values  = "(:fname, :lname, :address, :phone,
                             :uname, :email, :pass)";
                $insert  = $this->insert($this->userTable, $columns, $values);
                $this->query($insert);
                $this->bind(':fname', $user['firstName']);
                $this->bind(':lname', $user['lastName']);
                $this->bind(':address', $user['address']);
                $this->bind(':phone', $user['phone']);
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
                    $newImg = $this->insert_user_image($img);
                    if(getType($newImg) !== 'boolean') {
                        throw new Exception($newImg);
                    }
                    // Phase #5 OPTIONAL
                    $imgID  = $this->lastInsertId();
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
         * Called in insert_user(). Used when creating a new user to
         * establish entries in various many-to-many relationship tables.
         *
         * @param $id INT
         *        A unique UserID from the User table
         */
        protected function insert_defaults($id) {
            try {
                // Themes defaults
                $insert = $this->insert($this->userThemesTable,
                                        '(UserID)', '(:userID)');
                $this->query($insert);
                $this->bind(':userID', $id);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
                // Settings defaults
                $insert = $this->insert($this->userSettingsTable,
                                        '(UserID)', '(:userID)');
                $this->query($insert);
                $this->bind(':userID', $id);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
                echo "<br /><br />HUUUUH<br /><br />";
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

        /**
         * INSERT user-defined contacts. There are three phases:
         *      1) First, a search is done to see if a row containing
         *         the name + email combination already exists.
         *         If it does not, then a new contact is inserted.
         *      2) Next, a row within Contact_Details is created
         *         for the particular user and contact.
         *      3) Finally, the UserID, the ContactID and Contact_DetailsID
         *         are all inserted in the User_Contacts table.
         *
         * If--at any point along the way--any issues crop up, there are
         * various checks to ensure that an incomplete user is not created.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $contact ARRAY
         *        The following is expected:
         *        [0] STRING
         *            This is assumed to be the 'name' value
         *        [1] STRING
         *            This is assumed to be the 'email' value
         * @param $details ARRAY
         *        This is to be an associative array in the form of:
         *        'type'         => STRING,
         *        'nickname'     => STRING,
         *        'company'      => STRING,
         *        'jobTitle'     => STRING,
         *        'phone'        => STRING,
         *        'address'      => STRING,
         *        'birthday'     => STRING,
         *        'relationship' => STRING,
         *        'website'      => STRING,
         *        'notes'        => STRING
         */
        public function insert_contact($id, $contact, $details) {
            $this->transaction();
            try {
                $where = "name = ? AND email = ?";
                // Phase #1
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
                // Phase #2
                $insertDetails = $this->insert_contact_details($id, $details);
                if(getType($insertDetails) !== 'boolean') {
                    throw new Exception($insertDetails);
                }

                $detailsID = $this->lastInsertId();
                // Phase #3
                $columns = "(UserID, ContactsID, Contact_DetailsID)";
                $values  = "(:userID, :contactsID, :detailsID)";
                $insert  = $this->insert($this->userContactsTable,
                                        $columns, $values);
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
                $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-contact');
            }

            $this->commit();
            return "<h1>Contact successfully created for {$contact[0]}!</h1>";
        }

        /**
         * If a contact is not already available, it is created here.
         *
         *@param $newContact ARRAY
         *       [0] STRING
         *           Expects a name value
         *       [1] STRING
         *           Expects an email value
         *
         *@return BOOL (or STRING on failure)
         */
        private function insert_new_contact($newContact) {
            try {
                $insert = $this->insert($this->contactsTable,
                                        '(name, email)', '(:name, :email)');
                $this->query($insert);
                $this->bind(':name', $newContact[0]);
                $this->bind(':email', $newContact[1]);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return true;
        }
        /**
         * Called in insert_contact(). Every contact will have a details
         * entry. One, none, or many may be empty strings.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $details ARRAY
         *        This is to be an associative array in the form of:
         *        'type'         => STRING,
         *        'nickname'     => STRING,
         *        'company'      => STRING,
         *        'jobTitle'     => STRING,
         *        'phone'        => STRING,
         *        'address'      => STRING,
         *        'birthday'     => STRING,
         *        'relationship' => STRING,
         *        'website'      => STRING,
         *        'notes'        => STRING
         */
        private function insert_contact_details($id, $details) {
            try{
                $columns = "(type, nickname, company, job_title,
                             phone, address, birthday, relationship,
                             website, notes)";
                $values  = "(:type, :nickname, :company, :jobTitle, :phone,
                            :address, :birthday, :relationship,
                            :website, :notes)";
                $insert  = $this->insert($this->contactDetailsTable,
                                         $columns, $values);
                $this->query($insert);
                $this->bind(':type', $details['type']);
                $this->bind(':nickname', $details['nickname']);
                $this->bind(':company', $details['company']);
                $this->bind(':jobTitle', $details['jobTitle']);
                $this->bind(':phone', $details['phone']);
                $this->bind(':address', $details['address']);
                $this->bind(':birthday', $details['birthday']);
                $this->bind(':relationship', $details['relationship']);
                $this->bind(':website', $details['website']);
                $this->bind(':notes', $details['notes']);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return true;
        }

        /**
         * INSERT undesirable emails to the Blocked table in two phases:
         *      1) If this email has been blocked before, get the BlockedID
         *         from the Blocked table. If not, create a new row.
         *      2) Insert UserID and BlockedID in the many-to-many
         *         table: User_Blocked.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $email STRING
         *        The email to be blocked
         */
        public function insert_blocked($id, $email) {
            $this->transaction();
            try {
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

                $insert  = $this->insert($this->userBlockedTable,
                                         '(UserID, BlockedID)',
                                         '(:userID, :blockedID)');
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':blockedID', $blockedID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-blocked');
            }

            $this->commit();
            return "<h1>Email blocked!</h1>";
        }

        /**
         * If a given email has not been blocked, it is blocked here.
         *
         *@param $email STRING
         *       The email to be added to the Blocked table
         *
         *@return BOOL (or STRING on failure)
         */
        private function insert_new_blocked($email) {
            try {
                $insert = $this->insert($this->blockedTable,
                                        '(email)', '(:email)');
                $this->query($insert);
                $this->bind(':email', $email);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return true;
        }

        /**
         * If a settings combination does not already exist, create it.
         *
         * @param $settings ARRAY
         *        [0] STRING (enum),
         *            maxPerPage
         *        [1] STRING (enum),
         *            maxContactShow
         *        [2] BOOL,
         *            replyOneOrAll
         *        [3] BOOL,
         *            displayImg
         *        [4] BOOL,
         *            labelsOrText
         *        [5] STRING (enum)
         *            displayType
         *
         * @return BOOL (or STRING on failure)
         */
        private function insert_settings_profile($settings) {
            try {
                $columns = "(max_email_per_page, max_contacts_show,
                             reply_one_or_all, display_images,
                             btn_labels_or_text, ui_display_type)";
                $values  = "(:maxPerPage, :maxContactShow, :replyOneOrAll,
                             :displayImg, :labelsOrText, :displayType)";
                $insert  = $this->insert($this->settingsTable,
                                         $columns, $values);
                $this->query($insert);
                $this->bind(':maxPerPage', $settings[0]);
                $this->bind(':maxContactShow', $settings[1]);
                $this->bind(':replyOneOrAll', $settings[2]);
                $this->bind(':displayImg', $settings[3]);
                $this->bind(':labelsOrText', $settings[4]);
                $this->bind(':displayType', $settings[5]);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch(Exception $err) {
                return $err->getMessage();
            }

            return true;
        }

        /**
         * INSERT user-defined labels by:
         *      1) First, check for the existance of the label. If it does, reuse.
         *         If not, insert into the Labels table.
         *      2) And then insert UserID and LabelID into the many-to-many
         *         table: User_Labels.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $name STRING
         *        The name of the label
         */
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

                $insert  = $this->insert($this->userLabelsTable,
                                         '(UserID, LabelsID)',
                                         '(:userID, :labelsID)');
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':labelsID', $labelID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-label');
            }

            $this->commit();
            return "<h1>Successfully created label \"{$name}\"</h1>";
        }

        /**
         * If a given label does not exist, it is created here.
         *
         *@param $name STRING
         *       The label to be inserted into the Labels table
         *
         *@return BOOL (or STRING on failure)
         */
        private function insert_new_label($name) {
            try {
                $insert = $this->insert($this->labelsTable,
                                        '(name)', '(:name)');
                $this->query($insert);
                $this->bind(':name', $name);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return true;
        }

        private function edit_fields($id, $table, $changesBundle) {
            try {
                $columns = [];
                $values  = [];

                foreach ($changesBundle as $arr) {
                    $columns[] = "{$arr['column']} = ?";
                    $values[]  = $arr['value'];
                }
                // This will allow an arbitrary number of columns
                // to be prepared before binding values in the for loop.
                $allColumns = implode(', ', $columns);

                $change = "UPDATE {$table}
                           SET {$allColumns}
                           WHERE UserID = {$id}";
                $this->query($change);
                for ($i=0; $i < count($values); $i++) {
                    $this->bind($i+1, $values[$i]);
                }

                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                $err->getMessage();
            }

            return true;
        }

        /**
         * Allows editing of user attributes in the User table
         *
         * @param $id INT
         *
         * @param $changesBundle ARRAY
         *        An array of arbitrary length with pattern:
         *        [
         *            [
         *                'column' => STRING,
         *                'value'  => STRING
         *            ],
         *            [
         *                ...
         *        ]
         */
        public function edit_user($id, $changesBundle) {
            $this->transaction();
            try {
                // This method is not intended to change email or passwords
                $filtered_array = array_filter($changesBundle, function($el) {
                    if($el['column'] !== 'email' && $el['column'] !== 'pass') {
                        return $el;
                    }
                });

                $exec =
                    $this->edit_fields($id, $this->userTable, $filtered_array);
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-edit');
            }

            $this->commit();
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
         *        A unique UserID from the User table
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

        /**
         * Allows user to change settings by:
         *      1) First, check to see if the settings correspond to an
         *         already-exisitng row in the Settings table.
         *         If it does, use it. If not, create another.
         *      2) Then, update the user's preferences in the
         *         many-to-many table: User_Settings.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $settings ARRAY
         *        [0] STRING (enum),
         *            maxPerPage
         *        [1] STRING (enum),
         *            maxContactShow
         *        [2] BOOL,
         *            replyOneOrAll
         *        [3] BOOL,
         *            displayImg
         *        [4] BOOL,
         *            labelsOrText
         *        [5] STRING (enum)
         *            displayType
         */
        public function edit_settings($id, $settings) {
            $this->transaction();
            try {
                $where = "max_email_per_page = ? AND
                          max_contacts_show  = ? AND
                          reply_one_or_all   = ? AND
                          display_images     = ? AND
                          btn_labels_or_text = ? AND
                          ui_display_type    = ?";
                $settingsID = $this->get_existing_field($this->settingsTable,
                                                        'SettingsID',
                                                        $where, $settings);
                if(getType($settingsID) === 'array') {
                    throw new Exception($settingsID[0]);
                }

                if(!$settingsID) {
                    $profile = $this->insert_settings_profile($settings);
                    if(getType($profile) !== 'boolean') {
                        throw new Exception($profile);
                    }

                    $settingsID = $this->lastInsertId();
                }
                $change = $this->update($this->userSettingsTable,
                                        'SettingsID', ':settingsID',
                                        "UserID = {$id}");
                $this->query($change);
                $this->bind(':settingsID', $settingsID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
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
                return $this->err('unsuccessful-get', 'labels');
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
                $this->query($select);
                if(isset($bind)) {
                    for ($i=0; $i < count($bind); $i++) {
                        $this->bind($i+1, $bind[$i]);
                    }
                }
                $exec = $this->single(PDO::FETCH_NUM);
                if(getType($exec) !== 'array' && $exec !== false) {
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
     	 * Queries System_Labels table for all results
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
         * Queries Categories for all results
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
