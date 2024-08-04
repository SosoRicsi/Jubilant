<?php 
    namespace Jubilant;

    
    class Database {
        private $db_host = '';
        private $db_user = '';
        private $db_pass = '';
        private $db_name = '';
        private $con = '';
        
        public function __construct() {
            require __DIR__.'/../settings.php';
            $this->db_host = $DatabaseConnection[0];
            $this->db_user = $DatabaseConnection[1];
            $this->db_pass = $DatabaseConnection[2];
            $this->db_name = $DatabaseConnection[3];
        }

        public function connect() {
            if(!$this->con) {
                $this->con = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

                if($this->con) {
                    $seldb = mysqli_select_db($this->con, $this->db_name);

                    if($seldb) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function getConnection() {
            return $this->con;
        }

        public function disconnect() {
            if($this->con) {
                if(mysqli_close($this->con)) {
                    $this->con = false;
                    return true;
                } else 
                return false;
            }
        }

        public function tableExists(string $table) {
            $tablesInDB = mysqli_query($this->con, 'SHOW TABLES LIKE "'.$table.'"');

            if($tablesInDB) {
                if(mysqli_num_rows($tablesInDB) == 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        public function select(string $table, $rows = '*', $where = null, $order = null, $orderType = null) {
            $q = 'SELECT '.$rows.' FROM '.$table;

            if($where != null) {
                $q .= ' WHERE '.$where;
            }
            if($order != null) {
                $q .= ' ORDER BY '.$order;
            }
            if($orderType != null && $order != null) {
                $q .= $orderType;
            }

            if($this->tableExists($table)) {
                $result = $this->con->query($q);

                if($result) {
                    $arrResult = $result->fetch_all(MYSQLI_ASSOC);

                    return $arrResult;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        private function generateCustomID($lenght) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLenght = strlen($characters);
            $randomString = '';

            for($i = 0; $i < $lenght; $i++) {
                $randomCharacter = $characters[rand(0, $charactersLenght - 1)];
                $randomString .= $randomCharacter;
            }

            return $randomString;
        }

        public function insert(string $table, $values, $rows = null){

            if ($this->tableExists($table)) {
                $insert = 'INSERT INTO '.$table;
                if ($rows !== null) {
                    $insert .= ' (' . implode(',', $rows) . ')';
                }

                for ($i = 0; $i < count($values); $i++) {
                    $values[$i] = mysqli_real_escape_string($this->con, $values[$i]);
                    if (is_string($values[$i])) {
                        $values[$i] = '"'.$values[$i].'"';
                    }
                }

                $values = implode(',', $values);
                $insert .= ' VALUES ('.$values.')';
                $ins = mysqli_query($this->con, $insert);

                if ($ins) {
                    http_response_code(201);
                    return true;
                } else {
                    return false;
                }
            }
        }

        public function delete($table, $where = null) {
            if($this->tableExists($table)) {
                if($where == null) {
                    $delete = 'DELETE '.$table;
                } else {
                    $delete = 'DELETE FROM '.$table.' WHERE '.$where;
                }

                $del = $this->con->query($delete);

                if($del) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        public function update($table, $rows, $where) {
            if($this->tableExists($table)) {
                $update = 'UPDATE '.$table.' SET ';
                $setValues = [];
        
                foreach ($rows as $key => $value) {
                    $setValues[] = "`$key` = ?";
                }
                
                $update .= implode(',', $setValues);
                $update .= ' WHERE ' . key($where) . ' = ?';
        
                $stmt = $this->con->prepare($update);
        
                if ($stmt) {
                    $types = str_repeat('s', count($rows)) . 's';
                    $bindParams = array_merge(array_values($rows), [reset($where)]);
                    $stmt->bind_param($types, ...$bindParams);
                    $stmt->execute();
                    
                    if ($stmt->affected_rows > 0) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        
    }
?>