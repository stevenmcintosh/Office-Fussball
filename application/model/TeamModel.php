<?php

class TeamModel {

    public $teamId;
    public $teamName;
    public $teamType;
    public $teamMembers = array();
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
            $sql = "SELECT teamId, teamName, teamType FROM team
	    	WHERE teamId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $team = $query->fetchAll();
            $this->loadObjVarsFromSingle($team[0]);
        } else {
            $this->teamId = 0;
        }
        return $this;
    }

    private function loadObjVarsFromSingle($team) {
        foreach ($team as $var => $value) {
            $var = lcfirst($var);
            $this->$var = $value;
        }
        $this->loadTeamMembers();
    }

    private function loadTeamMembers() {
        $sql = "SELECT U.userId FROM team T
                  JOIN teamUser TU ON TU.teamId = T.teamId
                  JOIN user U ON U.userId = TU.userId
                WHERE T.teamId = :teamId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':teamId' => $this->teamId));
        foreach ($query->fetchAll() as $key => $val) {
            $user = new UserModel($this->db);
            $this->teamMembers[] = $user->load($val->userId);
        }
    }

    public function saveTeam() {
        $returnVal = false;
        if ($this->validate()) {
            $sql = "INSERT INTO team (teamName, teamType) VALUES (:teamName, :teamType)";
            $query = $this->db->prepare($sql);
            $sql_array = array(':teamName' => $this->teamName, ':teamType' => $this->teamType);
            $query->execute($sql_array);
            $this->teamId = $this->db->lastInsertId();

            foreach ($this->teamMembers as $teamMember) {
                $sql = "INSERT INTO teamUser (teamId, userId) VALUES (:teamId, :userId)";
                $query = $this->db->prepare($sql);
                $sql_array = array(':teamId' => $this->teamId, ':userId' => $teamMember->userId);
                $query->execute($sql_array);
                $returnVal = true;
            }
        }
        return $returnVal;
    }

    public function loadSingleTeams() {
        $sql = "SELECT T.teamId FROM team T
              JOIN teamUser TU ON TU.teamId = T.TeamId
              JOIN user U ON U.userId = TU.userId
              GROUP BY T.teamId
              HAVING COUNT(TU.teamId) = 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $team = new TeamModel($this->db);
            $arr[$val->teamId] = $team->load($val->teamId);
            $team->loadTeamMembers();
        }
        return $arr;
    }

    public function loadDoubleTeams() {
        $sql = "SELECT T.teamId FROM team T
              JOIN teamUser TU ON TU.teamId = T.teamId
              JOIN user U ON U.userId = TU.userId
              GROUP BY T.teamId
              HAVING COUNT(TU.teamId) = 2";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $team = new TeamModel($this->db);
            $arr[$val->teamId] = $team->load($val->teamId);
        }
        return $arr;
    }

    private function doesTeamNameExist() {
        $returnVal = false;
        $sql = "SELECT teamId FROM team
                WHERE teamName = :teamName";
        $query = $this->db->prepare($sql);
        $query->execute(array(':teamName' => $this->teamName));
        if ($query->rowCount() == 1) {
            $returnVal = true;
        }
        return $returnVal;
    }

    private function doesSingleTeamAlreadyExist() {
        $returnVal = false;
        $sql = "SELECT T.teamId FROM teamUser TU
                  JOIN team T ON T.teamId = TU.teamId
                  JOIN teamType TT ON TT.teamTypeId = T.teamType
                WHERE userId = :userId1 AND TT.teamTypeId =1
                GROUP BY T.teamId
                HAVING count(T.teamId) = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':userId1' => $this->teamMembers[0]->userId));

        if ($query->rowCount() >= 1) {
            $returnVal = true;
        }
        return $returnVal;
    }

    private function doesDoublesTeamAlreadyExist() {
        $returnVal = false;
        $sql = "SELECT teamId FROM teamUser
                WHERE
              (userId = :userId1) OR (userId = :userId2)
              GROUP BY teamId
            HAVING count(teamId) = 2";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':userId1' => $this->teamMembers[0]->userId,
            ':userId2' => $this->teamMembers[1]->userId));

        if ($query->rowCount() >= 1) {
            $returnVal = true;
        }
        return $returnVal;
    }
    
    public function getTeamIdsByUserId($userId) {
        $teamMembers = Array();
        $sql = "SELECT * FROM team T
                  JOIN teamUser TU ON TU.teamId = T.teamId
                  JOIN user U ON U.userId = TU.userId
                WHERE U.userId = :userId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':userId' => $userId));
        foreach ($query->fetchAll() as $key => $val) {
            $team = new TeamModel($this->db);
            $teamMembers[] = $team->load($val->teamId);
        }
        return $teamMembers;
    }
    

    private function validate() {

        $returnVal = true;

        if ((count($this->teamMembers) == 1) && $this->doesSingleTeamAlreadyExist()) {
            $this->errors['feedback']['singleTeamExists'] = "This user is already part of a singles team";
        }

        if ($this->doesTeamNameExist()) {
            $this->errors['feedback']['teamNameExists'] = "This team name already exists";
        }

        if (empty($this->teamName) or $this->teamName == '') {
            $this->errors['feedback']['teamNameEmpty'] = "This team name can not be empty";
        }

        if ((count($this->teamMembers) == 2) and ( $this->teamMembers[0] == $this->teamMembers[1])) {
            $this->errors['feedback']['teamNameEmpty'] = "A doubles team must have different players";
        }

        if ((count($this->teamMembers) == 2) && ($this->doesDoublesTeamAlreadyExist())) {
            $this->errors['feedback']['doubleTeamExists'] = "This doubles team already exists";
        }

        if (count($this->errors) > 0) {
            $returnVal = false;
        }
        return $returnVal;
    }

}
