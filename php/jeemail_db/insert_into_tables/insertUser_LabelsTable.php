<?php
    class Insert_Label extends Database {
        private $table = 'User_Labels';
        private $left  = 'left_most_position';
        private $right = 'right_most_position';

        public function insert_root_label($label) {
            $var = "SELECT IFNULL(MAX({$this->right}), 0) AS highRight
                    FROM {$this->table}";
            $insert_label = "INSERT INTO {$this->table}
                                (name, {$this->left}, {$this->right})
                             VALUES
                                (:name, :left, :right)";
            $this->query($var);
            $righty = $this->single()['highRight'];

            $this->query($insert_label);
            $this->bind(':name', $label);
            $this->bind(':left', $righty + 1);
            $this->bind(':right', $righty + 2);
            $this->execute();
            return "<h1>Successfully created label \"{$label}\".</h1>";
        }

        public function insert_nonroot_label($parent, $label) {
            // First, lock the table to prevent any changes before this
            // method is through.
            $this->transaction();
            $this->lockOne($this->table, 'WRITE');
            try {
                $var = "SELECT {$this->right} AS parentRight
                        FROM {$this->table}
                        WHERE name = '{$parent}';";

                $this->query($var);
                $righty = $this->single()['parentRight'];

                $update_right = "UPDATE {$this->table}
                                 SET
                                    {$this->right} = {$this->right} + 2
                                    WHERE {$this->right} > {$righty};";

                $update_left = "UPDATE {$this->table}
                                 SET
                                    {$this->left} = {$this->left} + 2
                                    WHERE {$this->left} > {$righty};";

                $insert_label = "INSERT INTO {$this->table}
                                    (name, {$this->left}, {$this->right})
                                 VALUES
                                    (:name, :left, :right);";

                $this->query($update_right);
                $this->execute();
                $this->query($update_left);
                $this->execute();
                $this->query($insert_label);
                $this->bind(':name', $label);
                $this->bind(':left', $righty + 1);
                $this->bind(':right', $righty + 2);
                $this->execute();
                $this->unlock();
                // $this->clearCursor();
                return "<h1>Successfully created label \"{$label}\".</h1>";
            }
            catch (Exception $err) {
                echo 'Exception -> ';
                var_dump($err->getMessage());
                $tihs->rollBack();
                $tihs->clearCursor();
            }
        }

        public function getLabelsOrdered($type="name") {
            $select_all_labels = "SELECT nested.name AS labels
                                  FROM {$this->table} AS nested,
                                       {$this->table} AS parent
                                  WHERE nested.{$this->left}
                                      BETWEEN parent.{$this->left}
                                          AND parent.{$this->right}
                                      GROUP BY nested.User_LabelsID
                                      ORDER BY nested.{$type};";
            $this->query($select_all_labels);
            $result = $this->resultSet();
            return $result;
        }
    }
 ?>
