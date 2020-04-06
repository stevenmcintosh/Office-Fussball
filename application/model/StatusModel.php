<?php

class StatusModel {

    public $statusId;
    public $statusName;
    public $errors = array();

    /**
     * @param object $db A PDO database connection
     */
    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function load($id) {
        if ($id != 0) {
            $sql = "SELECT statusId, statusName FROM status 
	    	WHERE statusId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $season = $query->fetchAll();
            $this->loadObjVarsFromSingle($season[0]);
        } else {
            $this->season = 0;
        }
        return $this;
    }

    private function loadObjVarsFromSingle($season) {
        foreach ($season as $var => $value) {
            $var = lcfirst($var);
            $this->$var = $value;
        }
        
    }

   
}
