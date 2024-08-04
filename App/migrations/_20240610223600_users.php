<?php 
    namespace Migrations;

    class _20240610223600_users {

        private $con;

        public function __construct($con) {
            $this->con = $con;
        }

        public function up() {
            $query = "CREATE TABLE IF NOT EXISTS users(
                ID INT AUTO_INCREMENT PRIMARY KEY,
                customID VARCHAR(255) NOT NULL,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status VARCHAR(255) NOT NULL
            )";
            $this->con->query($query);
        }

        public function down() {
            $query = "DROP TABLE IF EXISTS users";
            $this->con->query($query);
        }

    }