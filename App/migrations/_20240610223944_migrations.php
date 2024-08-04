<?php 
    namespace Migrations;

    class _20240610223944_migrations {

        private $con;

        public function __construct($con) {
            $this->con = $con;
        }

        public function up() {
            $query = "CREATE TABLE IF NOT EXISTS migrations(
                ID INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->con->query($query);
        }

        public function down() {
            $query = "DROP TABLE migrations";
            $this->con->query($query);
        }
    }