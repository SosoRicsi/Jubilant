<?php 
    namespace Migrations;

    class _20240610223618_lastlogins {

        private $con;

        public function __construct($con) {
            $this->con = $con;
        }

        public function up() {
            $query  = "CREATE TABLE IF NOT EXISTS lastLogins(
                ID INT AUTO_INCREMENT PRIMARY KEY,
                userCustomID VARCHAR(255) NOT NULL,
                sessionID VARCHAR(255) NOT NULL,
                sessionIP VARCHAR(255) NOT NULL,
                sessionBrowser VARCHAR(255) NOT NULL,
                loginDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            $this->con->query($query);
        }

        public function down() {
            $query = "DROP TABLE lastlogins";
            $this->con->query($query);
        }

    }