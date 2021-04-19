<?php

    class Database{
        public $con;
        
        public function getConnection(){
            $con = null;
            $con = mysqli_connect('localhost','root','','kosan');
                    
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            return $con;
        }
    }
?>