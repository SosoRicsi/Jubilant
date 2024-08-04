<?php 
namespace Jubilant\commands;

class CreateNewMigration {

    public function run($filename) {
        echo "Creating migration...\n";

        $timestamp = date("YmdHis");
        $migrationName = '_' . $timestamp . '_' . $filename;
        $file = __DIR__ . '/../../migrations/' . $migrationName . '.php';

        if (file_exists($file)) {
            echo "Migration already exists.\n";
            return;
        }

        $template = <<<EOT
        <?php 
            namespace Migrations;

            class $migrationName {

                private \$con;

                public function __construct(\$con) {
                    \$this->con = \$con;
                }

                public function up() {
                    \$query = "
                        CREATE TABLE $filename (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )
                    ";
                    \$this->con->query(\$query);
                }

                public function down() {
                    \$query = "DROP TABLE $filename";
                    \$this->con->query(\$query);
                }
            }
        EOT;
        
        $handle = fopen($file, 'w');
        if ($handle) {
            fwrite($handle, $template);
            fclose($handle);
            echo "Migration '$filename' created successfully.\n";
        } else {
            echo "Failed to create migration '$filename'.\n";
        }
    }
}
