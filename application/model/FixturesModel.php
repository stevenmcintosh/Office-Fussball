<?php

class FixturesModel {

    public $fixtureId;
    public $competitionId;
    public $homeTeamId;
    public $awayTeamId;
    public $statusId;
    public $homeTeam; // user obj
    public $awayTeam; // user obj
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
            $sql = "SELECT fixtureId, competitionId, homeTeamId, awayTeamId, statusId FROM fixture WHERE fixtureId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $fixture = $query->fetchAll();
            $this->loadObjVarsFromSingle($fixture[0]);
        } else {
            $this->fixtureId = 0;
        }
        return $this;
    }

    protected function loadObjVarsFromSingle($fixture) {
        foreach ($fixture as $var => $value) {
            $var = lcfirst($var);
            $this->$var = $value;
        }

        $teamModel = new TeamModel($this->db);
        $this->homeTeam = $teamModel->load($this->homeTeamId);
        $teamModel2 = new TeamModel($this->db);
        $this->awayTeam = $teamModel2->load($this->awayTeamId);
    }

    protected function setStatusToClosed() {
        $sql = "UPDATE fixture SET statusId = '2' WHERE fixtureId = :fixtureId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':fixtureId' => $this->fixtureId));
    }

    protected function setStatusToCancelled() {
        $sql = "UPDATE fixture SET statusId = '4' WHERE fixtureId = :fixtureId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':fixtureId' => $this->fixtureId));
    }

    public function removeTmpFixtures() {
        $returnval = true;
        $sql = "DELETE FROM fixtureTmp";
        $query = $this->db->prepare($sql);
        if (!$query->execute()) {
            $returnval = false;
        }
        return $returnval;
    }

    public function loadAllFixturesByCompetitionIdAndSeasonIdAndDivisionId($competitionId, $seasonId, $divisionId) {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, awayTeamId
	    	FROM fixture F
	    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
	    	WHERE F.competitionId = :competitionId AND LD.seasonId = :seasonId AND LD.divisionId = :divisionId
	    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':competitionId' => $competitionId, ':divisionId' => $divisionId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    public function loadAllFixturesByCompetitionIdSeasonIdAndGameweek($competitionId, $seasonId, $gameweek) {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, awayTeamId
	    	FROM fixture F
	    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
	    	WHERE F.competitionId = :competitionId AND LD.seasonId = :seasonId AND LD.gameweek = :gameweek
	    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':competitionId' => $competitionId, ':gameweek' => $gameweek));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    public function loadAllOpenFixturesByCompetitionIdSeasonIdAndGameweek($competitionId, $seasonId, $gameweek) {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, awayTeamId
	    	FROM fixture F
	    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
	    	WHERE F.competitionId = :competitionId AND LD.seasonId = :seasonId AND LD.gameweek = :gameweek
	    	AND statusId = 1
	    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':competitionId' => $competitionId, ':gameweek' => $gameweek));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    public function getRecentResultsByCompetitionIdAndSeasonId($competitionId, $seasonId, $numResults) {
        $sql = sprintf("SELECT F.fixtureId, LD.leagueFixtureId
	    	FROM fixture F
	    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
	    	WHERE F.competitionId = :competitionId AND LD.seasonId = :seasonId
	    	AND statusId = 2
	    	ORDER BY lastUpdated DESC
	    	LIMIT %d", $numResults);
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':competitionId' => $competitionId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->leagueFixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    protected function saveFixtureToTmp() {
        $returnVal = false;

        if ($this->validate()) {
            $sql = "INSERT INTO fixtureTmp SET 
                        competitionId = :competitionId,
	        	homeTeamId = :homeTeamId,
	        	awayTeamId = :awayTeamId,
	        	statusId = :statusId";
            $query = $this->db->prepare($sql);
            $sql_array = array(
                ':competitionId' => $this->competitionId,
                ':homeTeamId' => $this->homeTeamId,
                ':awayTeamId' => $this->awayTeamId,
                ':statusId' => 1
            );
            if ($query->execute($sql_array)) {
                $returnVal = $this->db->lastInsertId();
            }
        }
        return $returnVal;
    }

    
    
    protected function makeTmpFixtureIdsSafe() {
        $highestFixture = $this->getHighestFixtureIdinFixtureTable();
        $sql = "UPDATE fixtureTmp SET fixtureId = fixtureId + " . $highestFixture;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sql = "UPDATE leagueFixtureTmp SET fixtureId = fixtureId + " . $highestFixture . ", leaguefixtureId = leaguefixtureId + " . $highestFixture;
        $query = $this->db->prepare($sql);
        $query->execute();
    }
    
    protected function moveTmpFixturesToLive() {
        
        $returnVal = false;
        $sql = "INSERT INTO fixture (fixtureId, competitionId, homeTeamId, awayTeamId, statusId)
    	SELECT fixtureId, competitionId, homeTeamId, awayTeamId, statusId FROM fixtureTmp";
        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            $returnVal = true;
        }
        
     
    }

    public function getHighestFixtureIdinFixtureTable() {
        $sql = "SELECT MAX(fixtureId) as highestId FROM fixture LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        if ($res[0]->highestId == NULL) {
            $returnVal = 0;
        } else {
            $returnVal = $res[0]->highestId;
        }
        return $returnVal;
    }
    
    

    public function getWinnerNameFromFixture() {
        if ($this->homeScore > $this->awayScore) {
            $name = $this->homeUser->firstName;
        } elseif ($this->homeScore == $this->awayScore) {
            $name = 'No Winner';
        } else {
            $name = $this->awayUser->firstName;
        }
        return $name;
    }

    public function getLoserNameFromFixture() {
        if ($this->homeScore < $this->awayScore) {
            $name = $this->homeUser->firstName;
        } elseif ($this->homeScore == $this->awayScore) {
            $name = 'No Winner';
        } else {
            $name = $this->awayUser->firstName;
        }
        return $name;
    }

    public function getWinnerScoreFromFixture() {
        if ($this->homeScore > $this->awayScore) {
            $score = $this->homeScore;
        } else {
            $score = $this->awayScore;
        }
        return $score;
    }

    public function getLoserScoreFromFixture() {
        if ($this->homeScore < $this->awayScore) {
            $score = $this->homeScore;
        } else {
            $score = $this->awayScore;
        }
        return $score;
    }

    private function validate() {
        $returnval = true;

        /* if(empty($this->colour) or $this->colour == '')
          {
          $this->errors['feedback']['colour'] = 'The colour can not be empty';
          }
         */

        if (count($this->errors) > 0) {
            $returnval = false;
        }
        return $returnval;
    }

}
