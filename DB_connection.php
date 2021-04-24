<?php

    include "DB_configuration.php";

    class Database{
        public $con;
        public $db_config;
        
        public function __construct(){
            $db_config = new DatabaseConfiguration();
            $this->db_config = $db_config;
        }
        public function getConnection(){
            $con = null;
            $con = mysqli_connect($this->db_config->getServerName(),$this->db_config->getUsername(),$this->db_config->getPassword(),$this->db_config->getDatabaseName());
                    
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            return $con;
        }
    }
?>