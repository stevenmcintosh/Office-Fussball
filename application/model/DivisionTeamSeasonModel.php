<?php
/*
class DivisionTeamSeasonModel {

    public $id;
    public $divisionId;
    public $teamId;
    public $seasonId;
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
            $sql = "SELECT id, divisionId, teamId, seasonId FROM divisionTeamSeason WHERE id = :id";
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
    }

    /*public function loadAllDivisionsBySeasonId($seasonId) {
        $sql = "SELECT DTS.id, D.divisionId, divisionName, divisionOrder FROM division D "
                . "JOIN divisionTeamSeason DTS ON DTS.divisionId = D.divisionId "
                . "WHERE DTS.seasonId = :seasonId GROUP BY D.divisionId ORDER BY D.divisionOrder ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId));

        $arr = array();
        foreach($query->fetchAll() as $key => $val) {
        	$division = new divisionModel($this->db);
        	$arr[$val->divisionId]['divisionId'] = $val->divisionId;
                $arr[$val->divisionId]['divisionName'] = $val->divisionName;
                $arr[$val->divisionId]['divisionOrder'] = $val->divisionOrder;
                $arr[$val->divisionId]['seasonId'] = $seasonId;
        }
        return $arr;
    }*/
    
    
    
    
    
    /*public function getDivisionIdByTeamIdAndSeasonId($teamId, $seasonId) {
        $sql = "SELECT DTS.divisionId FROM divisionTeamSeason DTS "
                . "WHERE DTS.teamId = :teamId AND DTS.seasonId = :seasonId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':teamId' => $teamId, ':seasonId' => $seasonId));
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $returnVal = $res[0]->divisionId;
        } else {
            $divisionModel = new DivisionModel($this->db);
            $divObj = $divisionModel->getLowestDivision();
            $returnVal = $divObj[0]->divisionId;
        }
        return $returnVal;
    }
    

    

}
*/