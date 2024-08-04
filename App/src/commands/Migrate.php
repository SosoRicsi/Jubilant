<?php

    namespace Jubilant\commands;

    use Jubilant\Database;
    use Jubilant\Migration\MigrationManager;

    class Migrate {
        
        private $db;

        public function __construct(Database $db) {
            $this->db = $db;
        }

        public function run($filename) {
            $manager = new MigrationManager($this->db, __DIR__ . '/../../migrations');
            switch ($filename) {
                case 'rollback':
                    $manager->rollback();
                    echo "Last migration rolled back.\n";
                    break;
                case 'migrate':
                    $manager->migrate();
                    echo "Migration applied.\n";
                    break;
                default:
                    echo "Invalid migration command.\n";
                    break;
            }
        }

    }
