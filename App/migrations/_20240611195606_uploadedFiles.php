<?php 
    namespace Migrations;

    class _20240611195606_uploadedFiles {

        private $con;

        public function __construct($con) {
            $this->con = $con;
        }

        public function up() {
            $query = "
                CREATE TABLE uploadedFiles (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";
            $this->con->query($query);
        }

        public function down() {
            $query = "DROP TABLE uploadedFiles";
            $this->con->query($query);
        }
    }