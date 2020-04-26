<?php

class DatabaseModel {

    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function backUpDatabase() {
        $filename = 'db-backup-' . date('d-m-y') . '-' . time() . '.sql';
        //$command = 'mysql -h ' . DB_HOST . ' -u ' . DB_USER . ' -p ' . DB_PASS . ' ' . DB_NAME . ' > ' . DB_BACKUP_PATH . '/' . $filename;
        $command = 'mysqldump -h ' . DB_HOST . ' -u ' . DB_USER . '-p' . DB_PASS .' '. DB_NAME . ' > ' . DB_BACKUP_PATH . '/' . $filename;
        //$command = "\C:\\xampp\\mysql\\bin\\mysqldump.exe\"";
        exec($command);
    }

}