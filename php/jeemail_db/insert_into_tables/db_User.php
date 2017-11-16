<?php
// TODO: TODO: TODO: Search for todos!
    class db_User extends Database {
        public $blockedTable          = 'Blocked';
        public $categoriesTable       = 'Categories';
        public $contactsTable         = 'Contacts';
        public $contactDetailsTable   = 'Contact_Details';
        public $emailTable            = 'Email';
        public $imagesTable           = 'Images';
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
         * @param $user ASSOC ARRAY
         *        An array containing:
         *            'firstName' => STRING,
         *            'lastName'  => STRING,
         *            'gender'    => STRING,
         *            'username'  => STRING,
         *            'email'     => STRING,
         *            'pass'      => STRING
         * @param $img ASSOC ARRAY (or NULL)
         *        This array will be sent as argument to insert_user_image().
         *        It contains:
         *            'path'     => STRING,
         *            'name'     => STRING,
         *            'tempFile' => STRING,
         *            'format'   => STRING
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
                $columns = "(first_name, last_name, gender, address, phone,
                             username, email, pass)";
                $values  = "(:fname, :lname, :gender, :address, :phone,
                             :uname, :email, :pass)";
                $insert  = $this->insert($this->userTable, $columns, $values);
                $this->query($insert);
                $this->bind(':fname', $user['firstName']);
                $this->bind(':lname', $user['lastName']);
                $this->bind(':gender', $user['gender']);
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
                    echo "no";
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
                $lables     = $this->get_system_labels();
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
                $this->stmt->bindParam(':categoriesID', $extracted_CategoriesID,
                                       PDO::PARAM_INT);

                foreach ($categories as $category) {
                    extract($category, EXTR_PREFIX_ALL, 'extracted');
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

        /**
         * INSERT into the Images table by:
         *      1) Convert the already-saved $tempFile using Imagick
         *         into small, medium, and large sizes.
         *      2) Insert!
         *
         * @param $img ASSOC ARRAY
         *        It contains:
         *            'path'     => STRING,
         *            'name'     => STRING,
         *            'tempFile' => STRING,
         *            'format'   => STRING
         */
        // TODO: Imagick nonsense, I guess (in Database.php::resizeImage())
        public function insert_user_image($img) {
            // TODO: $name is going to be generated using uniqid() in some way
            try {
                $path     = $img['path'];
                $tempFile = $img['tempFile'];
                $name     = $img['name'];
                $format   = $img['format'];

                $imgSmall =
                    $this->resizeImage($path, $tempFile, $name, 0.25, $format);
                $imgMed   =
                    $this->resizeImage($path, $tempFile, $name, 0.5,  $format);
                $imgLarge =
                    $this->resizeImage($path, $tempFile, $name, 1.0,  $format);
                    echo "<br />GOOD BREAK<br />";
                // deleteFiles is located in Database.php and expects an array
                // $this->deleteFiles(["{$path}/{$tempFile}.{$format}"]);
                //
                // $insert = "INSERT INTO {$this->imagesTable}
                //               (icon_small, icon_medium, icon_large)
                //            VALUES
                //               (:iconSm, :iconMd, :iconLg)";
                // $this->query($insert);
                // $this->bind(':iconSm', $imgSmall);
                // $this->bind(':iconMd', $imgMed);
                // $this->bind(':iconLg', $imgLarge);
                // $this->execute();
            }

            catch (Exception $err) {
                return $err;
            }

            return true;
        }

        /**
         * INSERT an email by going through the following:
         *      1) Insert an email into the Email table.
         *      2) Insert the sender's UserID into the
         *         many-to-many table: User_Sent_Emails.
         *      3) Insert any and all recepients into the
         *         User_Received_Emails table.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $email ASSOC ARRAY
         *        An array containing all that constitutes an email:
         *            'replyToEmail' => STRING,
         *            'sentByEmail'  => STRING,
         *            'subject'      => STRING,
         *            'body'         => STRING
         * @param $received ARRAY
         *        A list of emails to receive the email
         *        [STRING, STRING, STRING, STRING]
         */
        // TODO: Automatically supply sent email
        // TODO: Fail sending an email if not valid recepient(s)
        // TODO: Need to check if user receiving email has blocked sender
        public function insert_email($id, $email, $received) {
            $this->transaction();
            try {
                $columns = "(reply_to_email, sent_by, subject, body)";
                $values  = "(:reply, :sent, :subject, :body)";
                $insert  = $this->insert($this->emailTable, $columns, $values);
                $this->query($insert);
                $this->bind(':reply', $email['replyToEmail']);
                $this->bind(':sent', $email['sentByEmail']);
                $this->bind(':subject', $email['subject']);
                $this->bind(':body', $email['body']);
                $this->execute();
                $emailID = $this->lastInsertId();
                $sent = $this->insert_sent_email($emailID, $id);
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
                $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-email');
            }

            $this->commit();
            return "<h2>Email successfully sent!</h2>";
        }

        /**
         * INSERT into User_Sent_Emails
         *
         * @param $emailID INT
         *        The ID of the newly created email
         * @param $id INT
         *        The UserID of the user who sent the email
         *
         * @return BOOL (or STRING on failure)
         */
        private function insert_sent_email($emailID, $id) {
            try {
                // CategoriesID 2 corresponds to 'Sent Mail'
                $where = "UserID = ? AND CategoriesID = 2";
                $catID = $this->get_existing_field($this->userCategoriesTable,
                                                   'User_CategoriesID',
                                                   $where, $id);
                if(getType($catID) === 'string') {
                    throw new Exception($catID);
                }

                $insert = $this->insert($this->emailSentTable,
                                        '(UserID, EmailID, User_CategoriesID)',
                                        '(:userID, :emailID, :catID)');
                $this->query($insert);
                $this->bind(':userID', $id);
                $this->bind(':emailID', $emailID);
                $this->bind(':catID', $catID[0]);
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
         * INSERT into User_Received_Emails by:
         *      1) Invoke verify_user() with $received. This will return
         *         an array: [0] is existing users, [1] is nonexisting.
         *      2) Insert each verified user's UserID into the many-to-many
         *         table User_Received_Emails, utilizing the first foreach loop.
         *      3) Finally, update the new rows with each user's default
         *         User_CategoriesID (the one with their unique UserID and
         *         a CategoriesID of 1).
         *
         * @param $emailID INT
         *        The ID of the newly created email
         * @param $received ARRAY
         *        An array of one or many emails that have been sent the email.
         *        [STRING, STRING, STRING, STRING]
         *
         * @return BOOL (or STRING on failure)
         */
        private function insert_received_email($emailID, $received) {
            try {
                $verify   = $this->verify_user($received);
                $existing = $verify[0];
                $nonUser  = $verify[1];

                $insert   = $this->insert($this->emailReceivedTable,
                                          '(UserID, EmailID)',
                                          '(:userID, :emailID)');
                $this->query($insert);
                $this->stmt->bindParam(':userID', $extracted_0, PDO::PARAM_INT);
                $this->bind(':emailID', $emailID);
                var_dump ($existing);
                foreach ($existing as $user) {
                    // Results in $extracted_0 due to the fact that each
                    // element in $existing is a key-value array with
                    // a key of 0.
                    extract($user, EXTR_PREFIX_ALL, 'extracted');
                    $exec = $this->execute();
                    if(getType($exec) !== 'boolean') {
                        throw new Exception($exec);
                    }
                }
                // This statement will set each user's User_Received_Email row
                // to point to their 'Inbox' row in User_Categories.
                $update = "UPDATE $this->emailReceivedTable tbl1
                           INNER JOIN
                           $this->userCategoriesTable tbl2
                           ON tbl1.UserID = tbl2.UserID
                           SET tbl1.User_CategoriesID = tbl2.User_CategoriesID
                           WHERE tbl2.CategoriesID = 1 AND
                                 tbl2.UserID = :userID";
                $this->query($update);
                $this->stmt->bindParam(':userID', $extracted_0, PDO::PARAM_INT);
                foreach ($existing as $user) {
                    extract($user, EXTR_PREFIX_ALL, 'extracted');
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

        /**
         * Called in insert_received_email() to check if email exists
         * within the User table.
         *
         * @param $emails ARRAY
         *        An array of one or many emails
         *        [STRING, STRING, STRING]
         *
         * @return MULTIDIMENSIONAL ARRAY
         *         [0] contains one or more associate arrays of verified
         *             email addresses.
         *         [1] will be an array of nonexistant email addresses or NULL.
         *         [[[0 => INT], [0 => INT]], [STRING, STRING, STRING]]
         */
        private function verify_user($emails) {
            $oneOrMany = [];
            // The for loop and implode simply create a string of question
            // marks separated by commas.
            for($i = 0; $i < count($emails); $i++) {
                $oneOrMany[] = '?';
            }
            $oneOrMany = implode($oneOrMany, ', ');
            $where = "email IN ({$oneOrMany})";
            // And we get back an array of all emails that do, infact, exist
            $userExists = $this->get_existing_field($this->userTable, 'email',
                                                    $where, $emails,
                                                    true);
            if(getType($userExists) === 'string') {
                throw new Exception($userExists);
            }
            // Flatten out the resultant array and get the difference from
            // our original list of email addresses.
            $userExists = array_merge(...$userExists);
            $nonUser = array_diff($emails, $userExists);
            // Repeat the pattern from up there (because it works)
            $oneOrMany = [];
            for($i = 0; $i < count($userExists); $i++) {
                $oneOrMany[] = '?';
            }
            //TODO: LATER -- Make this cleaner, pls. Dannae wanta repeat
            $oneOrMany = implode($oneOrMany, ', ');
            $where = "email IN ({$oneOrMany})";
            $userIDs = $this->get_existing_field($this->userTable, 'UserID',
                                                 $where, $userExists,
                                                 true);
            if(getType($userIDs) === 'string') {
                throw new Exception($userIDs);
            }

            if(count($nonUser) > 0){
                return [$userIDs, $nonUser];
            }

            return [$userIDs, NULL];
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
         * @param $details ASSOC ARRAY
         *        This is to be an associative array in the form of:
         *            'type'         => STRING,
         *            'nickname'     => STRING,
         *            'company'      => STRING,
         *            'jobTitle'     => STRING,
         *            'phone'        => STRING,
         *            'address'      => STRING,
         *            'birthday'     => STRING,
         *            'relationship' => STRING,
         *            'website'      => STRING,
         *            'notes'        => STRING
         */
        public function insert_contact($id, $contact, $details) {
            $this->transaction();
            try {
                $where = "name = ? AND email = ?";
                // Phase #1
                $contactID = $this->get_existing_field($this->contactsTable,
                                                       'ContactsID',
                                                       $where, $contact);
                if(getType($contactID) === 'string') {
                    throw new Exception($contactID);
                }

                if(!$contactID) {
                    $newContact = $this->insert_new_contact($contact);
                    if(getType($newContact) !== 'boolean') {
                        throw new Exception($newContact);
                    }

                    $contactID = $this->lastInsertId();
                }
                else {
                    $contactID = $contactID[0];
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
         * @param $details ASSOC ARRAY
         *        This is to be an associative array in the form of:
         *            'type'         => STRING,
         *            'nickname'     => STRING,
         *            'company'      => STRING,
         *            'jobTitle'     => STRING,
         *            'phone'        => STRING,
         *            'address'      => STRING,
         *            'birthday'     => STRING,
         *            'relationship' => STRING,
         *            'website'      => STRING,
         *            'notes'        => STRING
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
                                                       $where, $email);
                if(getType($blockedID) === 'string') {
                    throw new Exception($blockedID);
                }

                if(!$blockedID) {
                    $newBlocked = $this->insert_new_blocked($email);
                    if(getType($newBlocked) !== 'boolean') {
                        throw new Exception($newBlocked);
                    }

                    $blockedID = $this->lastInsertId();
                }
                else {
                    $blockedID = $blockedID[0];
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
                $where   = "name = ?";
                $labelID = $this->get_existing_field($this->labelsTable,
                                                     'LabelsID', $where, $name);
                if(getType($labelID) === 'string') {
                    throw new Exception($labelID);
                }

                if(!$labelID) {
                    $newLabel = $this->insert_new_label($name);
                    if(getType($newLabel) !== 'boolean') {
                        throw new Exception($newLabel);
                    }

                    $labelID = $this->lastInsertId();
                }
                else {
                    $labelID = $labelID[0];
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

        /**
         * A method to UPDATE an arbitrary number of fields
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $table STRING
         *        The table that is to be updated
         * @param $changesBundle ASSOC ARRAY
         *        Contains at least one column to update and one value
         *            [
         *                'column' => STRING,
         *                'value'  => STRING
         *            ],
         *            [
         *                ...
         *            ]
         */
        private function edit_fields($table, $changesBundle, $where) {
            try {
                $columns = [];
                $values  = [];
                // This will allow an arbitrary number of columns
                // to be prepared before binding values in the for loop.
                foreach ($changesBundle as $arr) {
                    $columns[] = "{$arr['column']} = ?";
                    $values[]  = $arr['value'];
                }

                $allColumns = implode(', ', $columns);
                $change = "UPDATE {$table}
                           SET {$allColumns}
                           WHERE {$where}";
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
         *        A unique UserID from the User table
         * @param $changesBundle ASSOC ARRAY
         *        An array of arbitrary length with pattern:
         *            [
         *                'column' => STRING,
         *                'value'  => STRING
         *            ],
         *            [
         *                ...
         *            ]
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

                $exec = $this->edit_fields($this->userTable, $filtered_array,
                                           "UserID = {$id}");
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

        /**
         * UPDATE contact by:
         *      1) See if there is a contact with the given name and email.
         *         If there is, use it. If not, create a new row in Contacts.
         *      2) Update row in User_Contacts for the given $id.
         *      3) Garbage collect old row in Contacts if no one else is
         *         using it.
         *      4) If $details is not null, pass it to edit_fields(),
         *         which allows changing of an arbitrary number of columns.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $oldID INT
         *        The ContactsID that is being changed.
         * @param $contact ARRAY (or NULL)
         *        This will always have a length of two:
         *        [0] STRING
         *            The name of the contact
         *        [1] STRING
         *            The email of the contact
         * @param $details ASSOC ARRAY (or NULL)
         *        The length of this array will vary, but will appear as:
         *            [
         *                'column' => STRING,
         *                'value'  => STRING
         *            ],
         *            [
         *                ...
         *            ]
         */
        public function edit_contact($id, $oldID, $contact = NULL,
                                     $details = NULL) {
            $this->transaction();
            try {
                if(is_null($contact) && is_null($details)) {
                    $this->rollBack();
                    return;
                }

                if(isset($contact)) {
                    $where  = "name = ? AND email = ?";
                    $contactID = $this->get_existing_field($this->contactsTable,
                                                           'ContactsID',
                                                           $where, $contact);
                    if(getType($contactID) === 'string') {
                        throw new Exception($contactID);
                    }

                    if(!$contactID) {
                        // Insert the new contact within Contacts
                        $result = $this->insert_new_contact($contact);
                        if(getType($result) !== 'boolean') {
                            throw new Exception($result);
                        }

                        $contactID = $this->lastInsertId();
                    }
                    else {
                        $contactID = $contactID[0];
                    }

                    $where = "ContactsID = {$oldID} AND UserID = {$id}";
                    $change = $this->update($this->userContactsTable,
                                            'ContactsID', $contactID, $where);
                    $this->query($change);
                    $exec = $this->execute();
                    if(getType($exec) !== 'boolean') {
                        throw new Exception($exec);
                    }
                    // Since we are changing ContactsID within User_Contacts,
                    // check to see if anyone else is using oldContactID
                    // inside User_Contacts. If not, it will be deleted
                    // from Contacts, as it is a useless row.
                    $juncTable = $this->userContactsTable;
                    $table     = $this->contactsTable;
                    $gc = $this->maybe_delete_unused_row($oldID,
                                                         $juncTable, $table,
                                                         'ContactsID');
                    if(getType($gc) !== 'boolean') {
                        throw new Exception($gc);
                    }
                }

                if(isset($details)) {
                    $select = $this->select($this->userContactsTable,
                                            'Contact_DetailsID',
                                            "UserID = {$id}");
                    $this->query($select);
                    $detailsID = $this->single(PDO::FETCH_NUM);
                    if(getType($detailsID) !== 'array') {
                        throw new Exception($detailsID);
                    }

                    $where = "Contact_DetailsID = {$detailsID[0]}";
                    $result = $this->edit_fields($this->contactDetailsTable,
                                                 $details, $where);
                    if(getType($result) !== 'boolean') {
                        throw new Exception($result);
                    }
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'contact');
            }

            $this->commit();
            return "<h1>Changes saved.</h1>";
        }

        /**
         * UPDATE blocked email by:
         *      1) Check to see if email has already been blocked.
         *         If it has, use it. If not, create a new row in Blocked.
         *      2) Update row in User_Blocked for the given $id.
         *      3) Garbage collect old row in Blocked if no one else is
         *         using it.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $oldID INT
         *        The BlockedID that is being changed.
         * @param $blocked STRING (or NULL)
         *        This is the email that is being changed to
         */
        public function edit_blocked($id, $oldID, $blocked) {
            $this->transaction();
            try {
                $where = "email = ?";
                $blockedID = $this->get_existing_field($this->blockedTable,
                                                       'BlockedID', $where,
                                                       $blocked);
                if(getType($blockedID) === 'string') {
                    throw new Exception($blockedID);
                }

                if(!$blockedID) {
                    $result = $this->insert_new_blocked($blocked);
                    if(getType($result) !== 'boolean') {
                        throw new Exception($result);
                    }

                    $blockedID = $this->lastInsertId();
                }
                else {
                    $blockedID = $blockedID[0];
                }
                $where = "BlockedID = {$oldID} AND UserID = {$id}";
                $change = $this->update($this->userBlockedTable, 'BlockedID',
                                        $blockedID, $where);
                $this->query($change);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
                // Since we are changing BlockedID within User_Blocked,
                // check to see if anyone else is using oldID
                // inside User_Blocked. If not, it will be deleted
                // from Blocked, as it is a useless row.
                $juncTable = $this->userBlockedTable;
                $table     = $this->blockedTable;
                $gc = $this->maybe_delete_unused_row($oldID, $juncTable, $table,
                                                     'BlockedID');
                if(getType($gc) !== 'boolean') {
                    throw new Exception($gc);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
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
                if(getType($settingsID) === 'string') {
                    throw new Exception($settingsID);
                }

                if(!$settingsID) {
                    $profile = $this->insert_settings_profile($settings);
                    if(getType($profile) !== 'boolean') {
                        throw new Exception($profile);
                    }

                    $settingsID = $this->lastInsertId();
                }
                else {
                    $settingsID = $settingsID[0];
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

        /**
         * UPDATE row from User_Labels and possibly DELETE from Labels.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $oldID INT
         *        ID of the label found in User_Labels
         * @param $name STRING
         *        The new name of the label
         */
        public function edit_label($id, $oldID, $name) {
            $this->transaction();
            try {
                $where   = "name = ?";
                $labelID = $this->get_existing_field($this->labelsTable,
                                                     'LabelsID', $where, $name);
                if(getType($labelID) === 'string') {
                    throw new Exception($labelID);
                }

                if(!$labelID) {
                    $result = $this->insert_new_label($name);
                    if(getType($result) !== 'boolean') {
                        throw new Exception($result);
                    }

                    $labelID = $this->lastInsertId();
                }
                else {
                    $labelID = $labelID[0];
                }
                $table     = $this->labelsTable;
                $juncTable = $this->userLabelsTable;
                $where     = "UserID = {$id}";
                $update    = $this->update($juncTable, 'LabelsID', $labelID,
                                           $where);
                $this->query($update);
                $this->execute();
                // Will delete from Labels if no one else is using $oldID
                $gc = $this->maybe_delete_unused_row($oldID, $juncTable,
                                                     $table, 'LabelsID');
                if(getType($gc) !== 'boolean') {
                    throw new Exception($gc);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-edit', 'blocked email');
            }

            $this->commit();
            return "<h1>Changes saved.</h1>";
        }

        /**
         * DELETE row from User_Contacts, Contact_Details,
         * and possibly Contacts.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $contactID INT
         *        ID of the contact to be deleted from User_Contacts
         */
        public function delete_contact($id, $contactID) {
            $this->transaction();
            try {
                $table     = $this->contactsTable;
                $juncTable = $this->userContactsTable;
                // First, get the Contact_DetailsID for the contact
                $detailsID = $this->get_existing_field($juncTable,
                                                       'Contact_DetailsID',
                                                       $where);
                if(getType($detailsID) === 'string') {
                    throw new Exception($detailsID);
                }

                $detailsID = $detailsID[0];
                // Then, delete the contact
                $where  = "UserID = :userID AND ContactsID = :contactID";
                $delete = $this->delete($juncTable, $where);
                $this->query($delete);
                $this->bind(':userID', $id);
                $this->bind(':contactID', $contactID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
                // Will delete from Contacts if no one else is using $contactID
                $gc = $this->maybe_delete_unused_row($contactID, $juncTable,
                                                     $table, 'ContactsID');
                if(getType($gc) !== 'boolean') {
                    throw new Exception($gc);
                }
                // Finally, delete the contact details
                $where  = "Contact_DetailsID = ?";
                $delete = $this->delete($this->contactDetailsTable, $where);
                $this->query($delete);
                $this->bind(1, $detailsID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-delete', 'contact');
            }

            $this->commit();
            return "<h1>Contact removed.</h1>";
        }

        /**
         * DELETE row from User_Blocked and possibly Blocked.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $blockedID INT
         *        ID of the blocked email to be deleted from User_Blocked
         */
        public function delete_blocked($id, $blockedID) {
            $this->transaction();
            try {
                $table     = $this->blockedTable;
                $juncTable = $this->userBlockedTable;
                // Delete the blocked entry with BlockedID of $blockedID
                $where  = "UserID = :userID AND BlockedID = :blockedID";
                $delete = $this->delete($juncTable, $where);
                $this->query($delete);
                $this->bind(':userID', $id);
                $this->bind(':blockedID', $blockedID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
                // Will delete from Contacts if no one else is using $blockedID
                $gc = $this->maybe_delete_unused_row($blockedID, $juncTable,
                                                     $table, 'BlockedID');
                if(getType($gc) !== 'boolean') {
                    throw new Exception($gc);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-delete', 'blocked email');
            }

            $this->commit();
            return "<h1>Unblocked email.</h1>";
        }

        /**
         * DELETE row from User_Labels and possibly Labels.
         *
         * @param $id INT
         *        A unique UserID from the User table
         * @param $labelID INT
         *        ID of the label to be deleted from User_Labels
         */
        public function delete_label($id, $labelID) {
            $this->transaction();
            try {
                $table     = $this->labelsTable;
                $juncTable = $this->userLabelsTable;
                // Delete the blocked entry with LabelsID of $labelID
                $where  = "UserID = :userID AND LabelsID = :labelID";
                $delete = $this->delete($juncTable, $where);
                $this->query($delete);
                $this->bind(':userID', $id);
                $this->bind(':labelID', $labelID);
                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }

                // Will delete from Contacts if no one else is using $labelID
                $gc = $this->maybe_delete_unused_row($labelID, $juncTable,
                                                     $table, 'LabelsID');
                if(getType($gc) !== 'boolean') {
                    throw new Exception($gc);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-delete', 'label');
            }

            $this->commit();
            return "<h1>Label removed.</h1>";
        }

        /**
         * This method will look inside a junction table to determine if a
         * given $id exists within it (meaning, another user is using it).
         * If no row within $juncTable contains $id, delete it from $table.
         * Either way, it will return true if all goes right with the query.
         *
         * @param $id INT
         *
         * @param $juncTable STRING
         *        The many-to-many table connected to $table and the User table
         * @param $table STRING
         *        A db table
         * @param $column STRING
         *        The common column within $juncTable and $table. It has a
         *        value of $id
         *
         * @return BOOL (or STRING on failure)
         */
        private function maybe_delete_unused_row($value, $juncTable,
                                                 $table, $column) {
            try {
                $where = "{$column} = ?";
                // Check to see if anyone is using a row in a
                // many-to-many table.
                $usedRow = $this->get_existing_field($juncTable, $column,
                                                     $where, $value);
                if(getType($usedRow) === 'string') {
                    throw new Exception($usedRow);
                }
                echo "<br />FROM MAYBE_DELETE: ";
                var_dump($usedRow);
                echo "<br />";
                // If no one is, delete it!
                if(!$usedRow) {
                    $delete = $this->delete($table, $where);
                    $this->query($delete);
                    $this->bind(1, $value);
                    $this->execute();
                }

                echo "{$value}, {$juncTable}, {$table}, {$column}";
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return true;
        }

        /**
         * Constructs an <h1> element with an error message in it dependant
         * on what is in $reason.
         *
         * @param $reason STRING
         *        The reason for the error
         * @param $field STRING/NULL
         *        Dictates where something went wrong
         *
         * @return STRING
         */
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
        // $foo = $db->get_contacts();
        // $foo = $db->get_blocked();
        // $foo = $db->get_labels();
        // $foo = $db->get_system_labels();
        // $foo = $db->get_categories();
        // $foo = $db->get_emails();
        public function get_all_user($id) {
            try {
                $user = $this->get_user($id);
                $contacts = $this->get_contacts($id);
                $blocked = $this->get_blocked($id);
                $labels = $this->get_labels($id);
                $sysLabels = $this->get_system_labels($id);
                $categories = $this->get_categories($id);
                $received = $this->get_received_emails($id);
                $sent = $this->get_sent_emails($id);

            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                return $this->err('unsuccessful-get', 'user details');
            }

            return [
                'user' => $user,
                'contacts' => $contacts,
                'blocked' => $blocked,
                'labels' => [
                    'user' => $labels,
                    'system' => $sysLabels
                ],
                'categories' => $categories,
                'emails' => [
                    'sent' => $sent,
                    'received' => $received
                ]
            ];
        }
        public function get_user($id) {
            try{
                $user   = $this->userTable;
                $img    = $this->imagesTable;
                $columns = 'CONCAT(first_name, " ", last_name) AS name,
                            CONCAT(email, "@jeemail.com") AS email,
                            username, gender, birthday, address, phone,
                            icon_small AS small, icon_medium AS medium,
                            icon_large AS large';
                $where  = "tb1.UserID = {$id}";
                $select = $this->selectJoin($user, $img, $columns,
                                            'ImagesID', $where);

                $this->query($select);
                $result = $this->resultSet();
                $result = $result[0];
            }
            catch (Exception $err) {
                return NULL;
            }

            return $result;
        }

        public function get_received_emails($id) {
            try{
                $emails         = $this->emailTable;
                $received       = $this->emailReceivedTable;
                $userCategories = $this->userCategoriesTable;
                $categories     = $this->categoriesTable;
                $where   = "tb2.UserID = {$id}";
                $columns = 'CONCAT(sent_by, "@jeemail.com") AS sender,
                            CONCAT(reply_to_email, "@jeemail.com") AS reply,
                            subject, body, important, starred,
                            tb4.name AS category, labels, time_sent AS time';
                $select = "SELECT {$columns}
                         FROM {$emails} tb1
                         LEFT JOIN {$received} tb2
                         ON tb1.EmailID = tb2.EmailID
                         LEFT JOIN {$userCategories} tb3
                         ON tb2.User_CategoriesID = tb3.User_CategoriesID
                         LEFT JOIN {$categories} tb4
                         ON tb3.CategoriesID = tb4.CategoriesID
                         WHERE {$where}";
                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                return NULL;
            }

                return $result;
        }

        public function get_sent_emails($id) {
            try{
                $emails         = $this->emailTable;
                $sent           = $this->emailSentTable;
                $userCategories = $this->userCategoriesTable;
                $categories     = $this->categoriesTable;
                $where   = "tb2.UserID = {$id}";
                $columns = 'CONCAT(sent_by, "@jeemail.com") AS sender,
                            CONCAT(reply_to_email, "@jeemail.com") AS reply,
                            subject, body, important, starred,
                            tb4.name AS category, labels, time_sent AS time';
                $select = "SELECT {$columns}
                         FROM {$emails} tb1
                         LEFT JOIN {$sent} tb2
                         ON tb1.EmailID = tb2.EmailID
                         LEFT JOIN {$userCategories} tb3
                         ON tb2.User_CategoriesID = tb3.User_CategoriesID
                         LEFT JOIN {$categories} tb4
                         ON tb3.CategoriesID = tb4.CategoriesID
                         WHERE {$where}";
                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                return NULL;
            }

                return $result;
        }

        public function get_contacts($id) {
            try{
                $contacts     = $this->contactsTable;
                $userContacts = $this->userContactsTable;
                $details      = $this->contactDetailsTable;
                $where        = "tb2.UserID = {$id}";
                $columns = 'name, email, type, nickname, company,
                            job_title AS job, phone, address, birthday,
                            relationship, website, notes';

                $select = "SELECT {$columns}
                         FROM {$contacts} tb1
                         LEFT JOIN {$userContacts} tb2
                         ON tb1.ContactsID = tb2.ContactsID
                         LEFT JOIN {$details} tb3
                         ON tb2.Contact_DetailsID = tb3.Contact_DetailsID
                         WHERE {$where}";
                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                return NULL;
            }

                return $result;
        }

        public function get_blocked($id) {
            try{
                $blocked     = $this->blockedTable;
                $userBlocked = $this->userBlockedTable;
                $where       = "UserID = {$id}";
                $select      = $this->selectJoin($blocked, $userBlocked,
                                                 'email', 'BlockedID', $where);
                $this->query($select);
                $result = $this->resultSet();
                // Change array into single dimensional
                foreach ($result as &$key) {
                    $key = $key['email'];
                }
            }
            catch (Exception $err) {
                return NULL;
            }

                return $result;
        }

        public function get_labels($id) {
            try{
                $labels     = $this->labelsTable;
                $userLabels = $this->userLabelsTable;
                $where      = "UserID = {$id}";
                $columns    = 'name, visibility';
                $select     = $this->selectJoin($labels, $userLabels, $columns,
                                                'LabelsID', $where);

                $this->query($select);
                $result = $this->resultSet();
            }
            catch (Exception $err) {
                return NULL;
            }

                return $result;
        }


        /**
     	 * Queries System_Labels table for all results
     	 *
         * @param $id INT/NULL
         *        A unique UserID from the User table. If it is NULL,
         *        this method is being used in insert_user()
         *
     	 * @return ARRAY (or STRING if it errors out)
    	 */
        public function get_system_labels($id = NULL) {
            try {
                if(is_null($id)) {
                    $select = $this->select($this->systemLabelsTable,
                              'System_LabelsID');
                    $this->query($select);
                    $labels = $this->resultSet();
                }
                else {
                    $labels     = $this->systemLabelsTable;
                    $userLabels = $this->userSystemLabelsTable;
                    $where      = "UserID = {$id}";
                    $columns    = 'name, visibility';
                    $select = $this->selectJoin($labels, $userLabels, $columns,
                                                'System_LabelsID', $where);

                    $this->query($select);
                    $labels = $this->resultSet();
                }
            }
            catch (Exception $err) {
                return NULL;
            }

            return $labels;
        }

        /**
         * Queries Categories for all results
         *
         * @param $id INT/NULL
         *        A unique UserID from the User table. If it is NULL,
         *        this method is being used in insert_user()
         *
         * @return ARRAY (or STRING if it errors out)
         */
        public function get_categories($id = NULL) {
            try {
                if(is_null($id)) {
                    $select = $this->select($this->categoriesTable,
                                            'CategoriesID');
                    $this->query($select);
                    $categories = $this->resultSet();
                }
                else {
                    $cats     = $this->categoriesTable;
                    $userCats = $this->userCategoriesTable;
                    $where    = "UserID = {$id}";
                    $columns  = 'name, visibility';
                    $select = $this->selectJoin($cats, $userCats, $columns,
                                                'CategoriesID', $where);

                    $this->query($select);
                    $categories = $this->resultSet();
                }
            }
            catch (Exception $err) {
                return NULL;
            }

            return $categories;
        }

        private function get_pass($id) {
            try {
            $select = "SELECT pass FROM {$this->userTable}
                          WHERE UserID = {$id}";
            $this->query($select);
            $result = $this->single(PDO::FETCH_NUM);
            if(getType($result) === 'string') {
                throw new Exception($result);
            }
            }
            catch (Exception $err) {
                // I am turning this exception into an array for a quick
                // and dirty check within validate_password.
                return [$err];
            }

            return $result[0];
        }

        /**
         * SELECTS from arbitrary table for arbitrary field and arbitrary WHERE.
         * If there are no results, return false.
         *
         * @param $table STRING
         *        Name of the table that is queried
         * @param $field STRING
         *        Value that is being queried for
         * @param $where STRING
         *        Where clause
         * @param $bind ARRAY or NULL
         *        These are the values that will be bound using PDO::bindValue
         *        [0] STRING/INT
         *        [1] STRING/INT
         *        ...
         * @param $outputMany BOOL or NULL
         *        A flag to determine whether the method will fetchAll or fetch.
         *
         * @return ARRAY/FALSE (or STRING on failure)
         *         Can be none, one, or many elements.
         */
        public function get_existing_field($table, $field, $where,
                                           $bind = NULL, $outputMany = NULL) {
            try {
                $select = $this->select($table, $field, $where);
                echo "<br />SELECT: {$select}";
                $butt = $this->query($select);
                var_dump($butt);
                echo "<br />";
                // This binds for placeholders within $where.
                if(isset($bind)) {
                    if(getType($bind) === 'array') {
                        for ($i=0; $i < count($bind); $i++) {
                            $this->bind($i+1, $bind[$i]);
                        }
                    }
                    else {
                        $this->bind(1, $bind);
                    }
                }

                if(is_null($outputMany)) {
                    $exec = $this->single(PDO::FETCH_NUM);
                }
                else {
                    $exec = $this->resultSet(PDO::FETCH_NUM);
                }

                if(getType($exec) !== 'array' && $exec !== false) {
                    throw new Exception($exec);
                }

                $existingID = sizeOf($exec) > 0
                    ? $exec : false;
            }
            catch (Exception $err) {
                return $err->getMessage();
            }

            return $existingID;
        }


        public function toggle_visibility($id, $table, $column, $otherID) {
            $this->transaction();
            try {
                $where = "UserID = {$id} AND {$column} = {$otherID}";
                $select = $this->select($table, 'visibility', $where);
                $this->query($select);
                $state = $this->single(PDO::FETCH_NUM);
                if(getType($state) === 'string') {
                    throw new Exception($state);
                }

                $state = $state[0];
                $update = $this->update($table, 'visibility', '?', $where);
                $this->query($update);
                if($state === 0) {
                    $this->bind(1, 1);
                }
                else {
                    $this->bind(1, 0);
                }

                $exec = $this->execute();
                if(getType($exec) !== 'boolean') {
                    throw new Exception($exec);
                }
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                echo $err->getMessage();
                $this->rollBack();
                return $this->err('unsuccessful-toggle', 'visibility');
            }

            $this->commit();
            return "<h1>Good toggle.</h1>";
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
