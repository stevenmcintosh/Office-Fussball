<?php

class DivisionModel {

    public $divisionId;
    public $divisionName;
    public $divisionShortName;
    public $divisionOrder;
    public $divisionHasBeenUsed;  //true if a league fixture exists using division
    public $errors = array();

    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function load($id) {
        if ($id != 0) {
            $sql = "SELECT divisionId, divisionName, divisionShortName, divisionOrder FROM division WHERE divisionId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $division = $query->fetchAll();
            $this->loadObjVarsFromSingle($division[0]);
        } else {
            $this->divisionId = 0;
        }
        return $this;
    }

    protected function loadObjVarsFromSingle($division) {
        foreach ($division as $var => $value) {
            $var = lcfirst($var);
            $this->$var = $value;
        }
        $this->updateDivisionHasBeenUsed();
    }

    public function divisionCanBeDeleted() {
        $returnval = true;
        if ($this->divisionHasBeenUsed) {
            $returnval = false;
        }
        return $returnval;
    }

    private function updateDivisionHasBeenUsed() {
        $this->divisionHasBeenUsed = false;
        $sql = "SELECT LF.seasonId 
        FROM leagueFixture LF 
        WHERE divisionId = :divisionId 
        LIMIT 1";
        $query = $this->db->prepare($sql);
        $sql_array = array(':divisionId' => $this->divisionId);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $this->divisionHasBeenUsed = true;
        }
    }

    public function saveDivisionFromAdminEdit() {
        $returnVal = false;
        if ($this->validate()) {

            $sql = "UPDATE division SET divisionName = :divisionName, divisionShortName = :divisionShortName, divisionOrder = :divisionOrder WHERE divisionId = :divisionId";
            $query = $this->db->prepare($sql);
            $sql_array = array(':divisionName' => $this->divisionName, ':divisionShortName' => $this->divisionShortName, ':divisionOrder' => $this->divisionOrder, ':divisionId' => $this->divisionId);
            $query->execute($sql_array);
            $returnVal = true;
        }
        return $returnVal;
    }

    public function deleteDivision() {
        $returnVal = false;
        if ($this->divisionCanBeDeleted()) {
            
            $sql = "DELETE FROM division WHERE divisionId = :divisionId";
            $query = $this->db->prepare($sql);
            $sql_array = array(':divisionId' => $this->divisionId);
            $query->execute($sql_array);
            $returnVal = true;
        }
        return $returnVal;
    }

    private function validate() {
        $returnval = true;

        if (empty($this->divisionName) or $this->divisionName == '') {
            $this->errors['feedback']['name'] = 'The division name can not be empty';
        }
        if (empty($this->divisionShortName) or $this->divisionShortName == '') {
            $this->errors['feedback']['shortname'] = 'The division short name can not be empty';
        }
        if (empty($this->divisionOrder) or $this->divisionOrder == '') {
            $this->errors['feedback']['order'] = 'The division name can not be empty';
        }
        if (strlen($this->divisionOrder) > 3) {
            $this->errors['feedback']['order'] = 'The division order must be a maximum of 3 chars';
        }
        if (!is_numeric($this->divisionOrder)) {
            $this->errors['feedback']['order'] = 'The division order must be a number';
        }



        if (count($this->errors) > 0) {
            $returnval = false;
        }
        return $returnval;
    }

    public function loadAllDivisions() {
        $sql = "SELECT divisionId, divisionName, divisionShortName, divisionOrder FROM division D ORDER BY divisionOrder ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $division = new DivisionModel($this->db);
            $arr[$val->divisionId] = $division->load($val->divisionId);
        }
        return $arr;
    }

    public function getLowestDivision() {
        $sql = "SELECT divisionId, divisionName, divisionShortName, divisionOrder FROM division D ORDER BY divisionOrder DESC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $division = new DivisionModel($this->db);
            $arr[] = $division->load($val->divisionId);
        }
        return $arr[0];
    }

    public function getHighestDivision() {
        $sql = "SELECT divisionId, divisionName, divisionShortName, divisionOrder FROM division D ORDER BY divisionOrder ASC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $division = new DivisionModel($this->db);
            $arr[] = $division->load($val->divisionId);
        }
        return $arr[0];
    }

}
