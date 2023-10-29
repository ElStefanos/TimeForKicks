<?php

    namespace database;

    use mysqli;
    use dataBase\dataBaseConfig;

    class dataBase {
        private $config;
        private $params;
        private $mysqli;

        private $row;

        public function __construct()
        {
            $this->config = new dataBaseConfig();
            $this->params = $this->config->getDetails();
            $this->mysqli = new mysqli($this->params['hostname'], $this->params['username'], $this->params['password'], $this->params['database']);
        }

        public function grabResultsTable($table, $term = '')
        {   $this->mysqli = new mysqli($this->params['hostname'], $this->params['username'], $this->params['password'], $this->params['database']);
            if(empty($term)) {
                $result = $this->mysqli->query("SELECT * FROM $table WHERE 1");
            } else {
                $result = $this->mysqli->query("SELECT * FROM $table $term");
            }
    
            $resultData[] = array();
    
            if(!$result)
            {
                return false;
            }
            $rows = array();
            while($result->row = $result->fetch_assoc())
            {
                $rows[] = $result->row;
            }
            return $rows;
        }

        public function updateTable($table, $term)
        {   
            $this->mysqli = new mysqli($this->params['hostname'], $this->params['username'], $this->params['password'], $this->params['database']);
            if($this->mysqli->query("UPDATE $table SET $term")) {
                return 1;
            } else {
                return 0;
            }
        }

        public function insertTable($table, $values, $term)
        {   
            $this->mysqli->query("INSERT INTO $table $values VALUES $term");
        }

        public function deleteData($table, $term = "") {
            if(empty($term)) {
                $this->mysqli->query("DELETE FROM $table WHERE 1");
            } else {
                $this->mysqli->query("DELETE FROM $table $term");
            }
        }
    }