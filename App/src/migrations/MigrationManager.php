<?php

namespace Jubilant\Migration;

use Jubilant\Database;

class MigrationManager {

    private $db;
    private $migrationsPath;

    public function __construct(Database $db, $migrationsPath) {
        $this->db = $db;
        $this->migrationsPath = $migrationsPath;
    }

    private function getMigrations() {
        $files = glob($this->migrationsPath . '/*.php');
        usort($files, function ($a, $b) {
            return basename($a) <=> basename($b);
        });

        return $files;
    }

    private function getAppliedMigrations() {
        $result = $this->db->select('migrations', 'migration');
        return array_map(function ($row) {
            return $row['migration'];
        }, $result);
    }

    private function applyMigration($file) {
        $migration = basename($file, '.php');

        require_once $file;

        $class = 'Migrations\\' . $migration;
        $migrationInstance = new $class($this->db->getConnection());
        $migrationInstance->up();

        $this->db->insert('migrations', [$migration], ['migration']);
    }

    private function revertMigration($migration) {
        require_once $this->migrationsPath . '/' . $migration . '.php';

        $class = 'Migrations\\' . $migration;
        $migrationInstance = new $class($this->db->getConnection());
        $migrationInstance->down();

        $this->db->delete('migrations', 'migration="' . $migration . '"');
    }

    public function migrate() {
        $migrations = $this->getMigrations();

        foreach ($migrations as $migration) {
            $this->applyMigration($migration);
        }
    }

    public function rollback() {
        $migrations = $this->getAppliedMigrations();

        if (!empty($migrations)) {
            $lastMigration = end($migrations);
            $this->revertMigration($lastMigration);
        }
    }
}
