<?php

class LeagueFixtureModel extends FixturesModel {

    public $leagueFixtureId;
    public $seasonId;
    public $divisionId;
    public $statusId;
    public $fixtureId;
    public $gameweek;
    public $homeScore;
    public $awayScore;
    public $homeWinPts = 0;
    public $awayWinPts = 0;
    public $homeGrannyPts = 0;
    public $awayGrannyPts = 0;
    public $homeLosePts = 0;
    public $awayLosePts = 0;
    public $team_a_provisional_score;
    public $team_b_provisional_score;
    public $admin_verified;
    public $provisional_score_added_by_team_id;
    public $provisional_score_verified_by_team_id;
    public $resultStatus = ''; // open, confirmed, awaiting verification

    public function load($id) {
        if ($id != 0) {
            $sql = "SELECT * FROM leagueFixture LD
	    	JOIN fixture F ON LD.fixtureId = F.fixtureId
	    	WHERE LD.leagueFixtureId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
//echo Helper::debugPDO($sql, array(':id' => $id));
            $fixture = $query->fetchAll();

            $this->loadObjVarsFromSingle($fixture[0]);

            // $this->calcIsVerified();
            // $this->calcIsAwaitingVerified();
        } else {
            $this->leagueFixtureId = 0;
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
        $division = new DivisionModel($this->db);
        $this->division = $division->load($this->divisionId);

        if ($this->admin_verified > 0) {
            $userModel = new UserModel($this->db);
            $this->result_verfied_by_user = $userModel->load($this->admin_verified);
        }
        if ($this->provisional_score_added_by_team_id > 0) {
            $teamModel = new TeamModel($this->db);
            $this->provisional_score_added_by_team = $teamModel->load($this->provisional_score_added_by_team_id);
        }
        if ($this->provisional_score_verified_by_team_id > 0) {
            $teamModel = new TeamModel($this->db);
            $this->provisional_score_verified_by_team = $teamModel->load($this->provisional_score_verified_by_team_id);
        }

        $this->updateResultStatus();
    }

    private function updateResultStatus() {
        if ($this->statusId == 1 && $this->provisional_score_added_by_team_id > 0) {
            $this->resultStatus = 'awaiting verification';
        } else if ($this->statusId == '2') {
            $this->resultStatus = 'closed';
        } else if ($this->statusId == '1') {
            $this->resultStatus = 'open';
        }
    }

    public function loadTmp($id) {
        if ($id != 0) {
            $sql = "SELECT * FROM leagueFixtureTmp LD
	    	JOIN fixtureTmp F ON LD.fixtureId = F.fixtureId
	    	WHERE LD.leagueFixtureId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $fixture = $query->fetchAll();
            $this->loadObjVarsFromSingle($fixture[0]);
        } else {
            $this->leagueFixtureId = 0;
        }

        return $this;
    }

    public function loadByFixtureId($id) {
        if ($id != 0) {
            $sql = "SELECT * FROM leagueFixture LD
	    	JOIN fixture F ON LD.fixtureId = F.fixtureId
	    	WHERE F.fixtureId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
//echo Helper::debugPDO($sql, array(':id' => $id));
            $fixture = $query->fetchAll();
//print_r($fixture);

            $this->loadObjVarsFromSingle($fixture[0]);
        } else {
            $this->leagueFixtureId = 0;
        }

        return $this;
    }

    public function getCurrentGameWeek() {
        $returnVal = 1;
        $sql = "SELECT F.fixtureId, LF.gameweek as current_gw
        FROM fixture F
        JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
        WHERE F.statusId = '1' ORDER BY seasonId, gameweek LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $returnVal = $res[0]->current_gw;
        }
        return $returnVal;
    }

    public function isLastGameweekOfSeason($seasonId, $currentGameweek) {
        $returnVal = true;
        $sql = "SELECT MAX(LF.gameweek) as lastGameweek
        FROM fixture F
        JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
        WHERE seasonId = :seasonId
        LIMIT 1";
        $sql_array = array(':seasonId' => $seasonId);
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        if ($res[0]->lastGameweek > $currentGameweek) {
            $returnVal = false;
        }
        return $returnVal;
    }

    public function removeTmpLeagueFixture() {
        $returnval = true;
        $sql = "DELETE FROM leagueFixtureTmp";
        $query = $this->db->prepare($sql);
        if (!$query->execute()) {
            $returnval = false;
        }
        return $returnval;
    }

    public function moveTmpLeagueFixtureToLive() {
        $returnVal = false;
        $sql = "INSERT INTO leagueFixture
        SELECT * FROM leagueFixtureTmp";
        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            $returnVal = true;
        }
        return $returnVal;
    }

    public function saveLeagueFixtureToTmp($seasonId, $fixtureId, $gameweek, $divisionId) {
        $returnval = true;

        if ($this->validate()) {
            if ($this->leagueFixtureId == 0) {
                $sql = "INSERT INTO leagueFixtureTmp SET
	        	leagueFixtureId = :leagueFixtureId,
                        seasonId = :seasonId,
                        divisionId = :divisionId,
	        	fixtureId = :fixtureId,
	        	gameweek = :gameweek,
	        	homeScore = :homeScore,
	        	awayScore = :awayScore,
	        	homeWinPts = :homeWinPts,
	        	awayWinPts = :awayWinPts,
	        	homeGrannyPts = :homeGrannyPts,
	        	awayGrannyPts = :awayGrannyPts,
	        	homeLosePts = :homeLosePts,
	        	awayLosePts = :awayLosePts";
                $query = $this->db->prepare($sql);
                $sql_array = array(
                    ':leagueFixtureId' => $fixtureId,
                    ':seasonId' => $seasonId,
                    ':divisionId' => $divisionId,
                    ':fixtureId' => $fixtureId,
                    ':gameweek' => $gameweek,
                    ':homeScore' => $this->homeScore,
                    ':awayScore' => $this->awayScore,
                    ':homeWinPts' => $this->homeWinPts,
                    ':awayWinPts' => $this->awayWinPts,
                    ':homeGrannyPts' => $this->homeGrannyPts,
                    ':awayGrannyPts' => $this->awayGrannyPts,
                    ':homeLosePts' => $this->homeLosePts,
                    ':awayLosePts' => $this->awayLosePts);
                if (!$query->execute($sql_array)) {
                    $returnval = false;
                }
            }
        }
        return $returnval;
    }

    public function saveCancelMatchFromAdminInput() {
        global $user;
        $returnval = false;


        $sql = "UPDATE leagueFixture SET
                        admin_verified = :admin_verified
	        	WHERE fixtureId = :fixtureId";
        $query = $this->db->prepare($sql);
        $sql_array = array(
            ':admin_verified' => $user->userId,
            ':fixtureId' => $this->fixtureId);
        if ($query->execute($sql_array)) {
            $this->setStatusToCancelled();
            $this->checkIfSeasonNeedsToClose();
            $returnval = true;
        }

        return $returnval;
    }

    public function saveResultFromAdminInput() {
        global $user;
        $returnval = false;

        if ($this->validateResult()) {
            $sql = "UPDATE leagueFixture SET
	        	homeScore = :homeScore,
	        	awayScore = :awayScore,
	        	homeWinPts = :homeWinPts,
	        	awayWinPts = :awayWinPts,
	        	homeGrannyPts = :homeGrannyPts,
	        	awayGrannyPts = :awayGrannyPts,
	        	homeLosePts = :homeLosePts,
	        	awayLosePts = :awayLosePts,
                        admin_verified = :admin_verified
	        	WHERE fixtureId = :fixtureId";
            $query = $this->db->prepare($sql);
            $sql_array = array(
                ':homeScore' => $this->homeScore,
                ':awayScore' => $this->awayScore,
                ':homeWinPts' => $this->homeWinPts,
                ':awayWinPts' => $this->awayWinPts,
                ':homeGrannyPts' => $this->homeGrannyPts,
                ':awayGrannyPts' => $this->awayGrannyPts,
                ':homeLosePts' => $this->homeLosePts,
                ':awayLosePts' => $this->awayLosePts,
                ':admin_verified' => $user->userId,
                ':fixtureId' => $this->fixtureId);
            if ($query->execute($sql_array)) {
                $this->setStatusToClosed();
                $this->checkIfSeasonNeedsToClose();
                $returnval = true;
            }
        }
        return $returnval;
    }

    public function saveProvisionalResultFromUserInput() {
        global $user;
        $returnval = false;

        if ($this->validateResultFromUser()) {
            $sql = "UPDATE leagueFixture SET
	        	team_a_provisional_score = :team_a_provisional_score,
	        	team_b_provisional_score = :team_b_provisional_score,
	        	provisional_score_added_by_team_id = :provisional_score_added_by_team_id
	        	WHERE fixtureId = :fixtureId";
            $query = $this->db->prepare($sql);
            $sql_array = array(
                ':team_a_provisional_score' => $this->team_a_provisional_score,
                ':team_b_provisional_score' => $this->team_b_provisional_score,
                ':provisional_score_added_by_team_id' => $this->provisional_score_added_by_team_id,
                ':fixtureId' => $this->fixtureId);
            if ($query->execute($sql_array)) {
                $returnval = true;
            }
        }
        return $returnval;
    }

    public function saveProvisionalResultFromUserVerify() {
        global $user;
        $returnval = false;

        if ($this->validateResult()) {
            $sql = "UPDATE leagueFixture SET
	        	homeScore = :homeScore,
	        	awayScore = :awayScore,
	        	homeWinPts = :homeWinPts,
	        	awayWinPts = :awayWinPts,
	        	homeGrannyPts = :homeGrannyPts,
	        	awayGrannyPts = :awayGrannyPts,
	        	homeLosePts = :homeLosePts,
	        	awayLosePts = :awayLosePts,
                        provisional_score_verified_by_team_id = :provisional_score_verified_by_team_id
	        	WHERE fixtureId = :fixtureId";
            $query = $this->db->prepare($sql);
            $sql_array = array(
                ':homeScore' => $this->homeScore,
                ':awayScore' => $this->awayScore,
                ':homeWinPts' => $this->homeWinPts,
                ':awayWinPts' => $this->awayWinPts,
                ':homeGrannyPts' => $this->homeGrannyPts,
                ':awayGrannyPts' => $this->awayGrannyPts,
                ':homeLosePts' => $this->homeLosePts,
                ':awayLosePts' => $this->awayLosePts,
                ':provisional_score_verified_by_team_id' => $this->provisional_score_verified_by_team_id,
                ':fixtureId' => $this->fixtureId);
            if ($query->execute($sql_array)) {
                $this->setStatusToClosed();
                $this->checkIfSeasonNeedsToClose();
                $returnval = true;
            }
        }
        return $returnval;
    }

    public function removeProvisionalResultFromUserInput() {
        global $user;
        $returnval = false;

        if ($this->validateResultFromUser()) {
            $sql = "UPDATE leagueFixture SET
	        	team_a_provisional_score = :team_a_provisional_score,
	        	team_b_provisional_score = :team_b_provisional_score,
	        	provisional_score_added_by_team_id = :provisional_score_added_by_team_id
	        	WHERE fixtureId = :fixtureId";
            $query = $this->db->prepare($sql);
            $sql_array = array(
                ':team_a_provisional_score' => 'null',
                ':team_b_provisional_score' => 'null',
                ':provisional_score_added_by_team_id' => 0,
                ':fixtureId' => $this->fixtureId);
            if ($query->execute($sql_array)) {
                $returnval = true;
            }
        }
        return $returnval;
    }

    public function isProvisionalResultValid() {
        return $this->validateResultFromUser();
    }

    private function checkIfSeasonNeedsToClose() {
        if (!$this->hasSeasonOpenFixtures()) {
            $seasonModel = new SeasonModel($this->db);
            $season = $seasonModel->load($this->seasonId);
            $season->setStatusToClosed();
        }
    }

    public function moveAllTmpToLive() {
        $this->makeTmpFixtureIdsSafe();
        $this->moveTmpFixturesToLive();
        $this->moveTmpLeagueFixtureToLive();
    }

    public function createLeagueFixtures($season_id, $teams, $divisionId) {
        $fixtures = array();
        
        $numPlayers = count($teams);
        $ghost = false;
        if ($numPlayers % 2 == 1) {
            $numPlayers++;
            $ghost = true;
            $byeTeam = new TeamModel($this->db);
            $byeTeam->teamId = 0;
            array_push($teams, $byeTeam);
        }

        $aTeam = array();
        foreach ($teams as $teamId => $team) {
            $aTeam[] = $teamId;
        }


        $totalRounds = ($numPlayers - 1) * 2;
        $halfWay = $totalRounds / 2;
        $matchesPerRound = $numPlayers / 2;
        $rounds = array();

        for ($i = 0; $i < ($totalRounds); $i++) {
            $rounds[$i] = array();
        }

        for ($round = 1; $round < $totalRounds + 1; $round++) {
                
            for ($match = 0; $match < $matchesPerRound; $match++) {
                //die($startingFixtureId);
                
                $home = ($round + $match) % ($numPlayers - 1);
                $away = ($numPlayers - 1 - $match + $round) % ($numPlayers - 1);
                if ($match == 0) {
                    $away = $numPlayers - 1;
                }

                $fixtures[$round . '-' . $match] = new FixturesModel($this->db);
                //$fixtures[$round . '-' . $match]->fixtureId = ($startingFixtureId + $tmpFixtureCounter);
                $fixtures[$round . '-' . $match]->competitionId = 1;
                $fixtures[$round . '-' . $match]->seasonId = $season_id;
                $fixtures[$round . '-' . $match]->divisionId = $divisionId;
                $fixtures[$round . '-' . $match]->gw = $round;

                $fixtures[$round . '-' . $match]->homeTeamId = $teams[$aTeam[$home]]->teamId;
                $fixtures[$round . '-' . $match]->awayTeamId = $teams[$aTeam[$away]]->teamId;


//echo $round;
//echo $halfWay;
                if ($round % 2 == 1) {
                    $this->swapLeagueFixtureHomeAndAway($fixtures[$round . '-' . $match]);
                }
            }
        }

        foreach ($fixtures as $fixture) {
            if ($fixture->homeTeamId != 0 && $fixture->awayTeamId != 0) {
                $tmpFixtureId = $fixture->saveFixtureToTmp();
                $LeagueFixtureModel = new LeagueFixtureModel($this->db);
                $LeagueFixtureModel->saveLeagueFixtureToTmp($fixture->seasonId, $tmpFixtureId, $fixture->gw, $divisionId);
            }
        }
    }

    private function swapLeagueFixtureHomeAndAway($fixture) {
        $tmp = $fixture->homeTeamId;
        $fixture->homeTeamId = $fixture->awayTeamId;
        $fixture->awayTeamId = $tmp;
    }

    public function getTmpFixtures() {
        $sql = "SELECT LD.leagueFixtureId, 
            LD.gameweek, LD.divisionId, D.divisionName, F.statusId, F.fixtureId,T1.teamName as homeTeamName, 
            T2.teamName as awayTeamName, competitionId, homeTeamId, awayTeamId
                FROM fixtureTmp F
                JOIN leagueFixtureTmp LD ON LD.fixtureId = F.fixtureId
                
                JOIN division D ON D.divisionId = LD.divisionId
                join team T1 on T1.teamId = homeTeamId
                join team T2 on T2.teamId = awayTeamId
                WHERE statusId = 1 ORDER BY LD.gameweek ASC, D.divisionOrder ASC
                ";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            //$fixtures = new LeagueFixtureModel($this->db);
            $teamModel = new TeamModel($this->db);
            //$arr[$val->fixtureId] = $fixtures->loadTmp($val->leagueFixtureId);
            $arr[$val->fixtureId]['id'] = $val->leagueFixtureId;
            $arr[$val->fixtureId]['divisionId'] = $val->divisionId;
            $arr[$val->fixtureId]['divisionName'] = $val->divisionName;
            $arr[$val->fixtureId]['round'] = $val->gameweek;
            $arr[$val->fixtureId]['status'] = $val->statusId;
            $arr[$val->fixtureId]['competitionId'] = $val->competitionId;
            $arr[$val->fixtureId]['homeTeamName'] = $val->homeTeamName;
            $arr[$val->fixtureId]['awayTeamName'] = $val->awayTeamName;
            $arr[$val->fixtureId]['homeTeam'] = $teamModel->load($val->homeTeamId);
            $arr[$val->fixtureId]['awayTeam'] = $teamModel->load($val->awayTeamId);
            $arr[$val->fixtureId]['homeTeamId'] = $val->homeTeamId;
            $arr[$val->fixtureId]['awayTeamId'] = $val->awayTeamId;
        }
        return $arr;
    }

    public function getAllLeagueResults($seasonId) {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, AwayTeamId
            homeScore, awayScore
		    	FROM fixture F
		    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
		    	WHERE statusId = 2 and seasonId = :seasonId
		    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    public function getAllOpenFixtures() {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, awayTeamId
		    	FROM fixture F
		    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
		    	WHERE statusId = 1
		    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    public function getAllOpenFixturesByTeamId($teamId) {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, awayTeamId
		    	FROM fixture F
		    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
		    	WHERE statusId = 1 AND (homeTeamId = '" . $teamId . "' OR awayTeamId = '" . $teamId . "')
		    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    public function getAllFixturesByTeamIdAndSeasonId($teamId, $seasonId) {
        $sql = "SELECT LD.leagueFixtureId, LD.gameweek, F.statusId, F.fixtureId, competitionId, homeTeamId, awayTeamId
		    	FROM fixture F
		    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
		    	WHERE seasonId = :seasonId AND (homeTeamId = '" . $teamId . "' OR awayTeamId = '" . $teamId . "')
		    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->fixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    private function hasSeasonOpenFixtures() {
        $returnVal = false;
        $sql = "SELECT LD.leagueFixtureId
		    	FROM fixture F
		    	JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
		    	WHERE statusId = 1 AND seasonId = :seasonId
		    	ORDER BY gameweek ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $this->seasonId));
        if ($query->rowCount() > 0) {
            $returnVal = true;
        }
        return $returnVal;
    }

    /**
     * returns the total amount of league games that are till open upto
     * and inclusive for the gameweek passed in for the season pass in
     * 
     */
    public function getTotalOpenFixturesUpToGameweekForSeason($seasonId, $gameweek) {
        $returnVal = 0;
        $sql = "select count(LF.fixtureId) as gamesRemaining from leagueFixture LF 
                join fixture F ON F.fixtureId = LF.fixtureId
                where seasonId = :seasonId and gameweek <= :gameweek and statusId = 1";
        $query = $this->db->prepare($sql);
        $res = $query->execute(array(':gameweek' => $gameweek, ':seasonId' => $seasonId));
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $returnVal = $res[0]->gamesRemaining;
        }
        return $returnVal;
    }

    public function calcLeaguePoints() {
        if ($this->homeScore > $this->awayScore) {
            $this->homeWinPts = LEAGUE_WIN_PTS;
            $this->awayWinPts = 0;

            if ($this->homeScore == $this->awayScore + CLOSE_GAME_GOALS) {
                $this->homeWinPts = LEAGUE_WIN_PTS_CLOSE_GAME;
                $this->awayLosePts = LEAGUE_CLOSE_LOSE_PTS;
            }

            if ($this->awayScore == 0 && $this->homeScore == FIRST_TO_GOALS) {
                $this->awayGrannyPts = 0;
                $this->homeGrannyPts = LEAGUE_WIN_GRANNY_PTS;
            }
        } else {
            $this->homeWinPts = 0;
            $this->awayWinPts = LEAGUE_WIN_PTS;

            if ($this->awayScore == $this->homeScore + CLOSE_GAME_GOALS) {
                $this->homeLosePts = LEAGUE_CLOSE_LOSE_PTS;
                $this->awayWinPts = LEAGUE_WIN_PTS_CLOSE_GAME;
            }

            if ($this->homeScore == 0 && $this->awayScore == FIRST_TO_GOALS) {
                $this->homeGrannyPts = 0;
                $this->awayGrannyPts = LEAGUE_WIN_GRANNY_PTS;
            }
        }
    }

    private function validate() {
        $returnval = true;

        if (count($this->errors) > 0) {
            $returnval = false;
        }
        return $returnval;
    }

    private function validateResult() {
        $returnVal = true;

        if (FIRST_TO_GOALS_ACTIVE) {
            if (!($this->homeScore == FIRST_TO_GOALS || $this->awayScore == FIRST_TO_GOALS)) {
                $this->errors['feedback']['no_winner'] = $this->homeScore . "-" . $this->awayScore . ". Invalid score. A player must reach " . FIRST_TO_GOALS . " goals to win. Please enter a valid score.";
            }
        }

        if (count($this->errors) > 0) {
            $returnVal = false;
        }
        return $returnVal;
    }

    private function validateResultFromUser() {
        $returnVal = true;

        if (FIRST_TO_GOALS_ACTIVE) {
            if (!($this->team_a_provisional_score == FIRST_TO_GOALS || $this->team_b_provisional_score == FIRST_TO_GOALS)) {
                $this->errors['feedback']['no_winner'] = $this->team_a_provisional_score . "-" . $this->team_b_provisional_score . " is an invalid score. A player must reach " . FIRST_TO_GOALS . " goals to win. Please <a href=" . URL . "/fixtures/myresults/>go back</a> and enter a valid score.";
            }
        }
        
        if (count($this->errors) > 0) {
            $returnVal = false;
        }
        return $returnVal;
    }

    /**
     * generates a league table for the current season for all league games
     * @param type $seasonId
     * @return type
     */
    public function generateLeagueTable($seasonId, $divisionId) {
        $sql = "SELECT
              T.teamId as teamId,
              T.teamName AS teamName,
              Sum(homePlayed) AS homePlayed,
              Sum(awayPlayed) AS awayPlayed,
              Sum(P) AS P,
              Sum(W) AS W,
              Sum(L) AS L,
              Sum(wonHome) AS wonHome,
              Sum(lostHome) AS lostHome,
              Sum(wonAway) AS wonAway,
              Sum(lostAway) AS lostAway,
              Sum(homeFor) AS homeFor,
              Sum(homeAgainst) AS homeAgainst,
              Sum(awayFor) AS awayFor,
              Sum(awayAgainst) AS awayAgainst,
              COALESCE(SUM(totalFor), 0) AS totalFor,
              COALESCE(SUM(totalAgainst), 0) AS totalAgainst,
              COALESCE(SUM(homeGoalDiff), 0) AS homeGoalDiff,
              COALESCE(SUM(awayGoalDiff), 0) AS awayGoalDiff,
              COALESCE(SUM(totalGoalDiff), 0) AS totalGoalDiff,
              SUM(homeWinPts) AS homeWinPts,
              SUM(awayWinPts) AS awayWinPts,
              SUM(WinPts) AS WinPts,
              SUM(GranPts) AS GranPts,
              SUM(LosePts) AS LosePts,
              
              SUM(totalHomePoints) AS totalHomePoints,
              SUM(totalAwayPoints) AS totalAwayPoints,
              SUM(totalPoints) AS totalPoints
              
            FROM(
                  SELECT
                    homeTeamId as team,
                    
                    IF(F.statusId = 2,1,0) homePlayed,
                    0 awayPlayed,
                    
                    IF(F.statusId = 2,1,0) P,
                    IF(homeScore > awayScore,1,0) W,
                    IF(homeScore < awayScore,1,0) L,
                    
                    IF(homeScore > awayScore,1,0) wonHome,
                    IF(homeScore < awayScore,1,0) lostHome,
                    IF(homeScore < awayScore,0,0) wonAway,
                    IF(homeScore > awayScore,0,0) lostAway,
                    
                    homeScore homeFor,
                    awayScore homeAgainst,
                    0 awayFor,
                    0 awayAgainst,
                    
                    homeScore totalFor,
                    awayScore totalAgainst,
                    homeScore-awayScore homeGoalDiff,
                    0 awayGoalDiff,
                    homeScore-awayScore totalGoalDiff,
                    
                    homeWinPts homeWinPts,
                    0 awayWinPts,
                    homeWinPts WinPts,
                    homeGrannyPts GranPts,
                    homeLosePts LosePts,
                    
                    (homeWinPts + homeGrannyPts + homeLosePts) totalHomePoints,
                    0 totalAwayPoints,
                	(homeWinPts + homeGrannyPts + homeLosePts) totalPoints
                  
                    FROM leagueFixture LF
                    JOIN fixture F ON F.fixtureId = LF.fixtureId
                  WHERE seasonId = :seasonId AND divisionId = :divisionId
                
                  UNION ALL
                  SELECT
                    awayTeamId,
                    
                    0,
                    IF(F.statusId = 2,1,0),
                    
                    IF(F.statusId = 2,1,0),
                    IF(homeScore < awayScore,1,0),
                    IF(homeScore > awayScore,1,0),
                
                    IF(homeScore < awayScore,0,0),
                    IF(homeScore > awayScore,0,0),
                    IF(homeScore < awayScore,1,0),
                    IF(homeScore > awayScore,1,0),
                	
                    0,
                    0,
                    awayScore,
                    homeScore,
                
                    awayScore,
                    homeScore,
                    0,
                    awayScore-HomeScore,
                    awayScore-HomeScore,
                    
                    0,
                    awayWinPts,
                    awayWinPts,
                    awayGrannyPts,
                    awayLosePts,
                    
                    0,
                    (awayWinPts + awayGrannyPts + awayLosePts),
                (awayWinPts + awayGrannyPts + awayLosePts)
                    
                  FROM leagueFixture LF
                    JOIN fixture F ON F.fixtureId = LF.fixtureId
                  WHERE seasonId = :seasonId AND divisionId = :divisionId
                ) as tot
              JOIN team T ON T.teamId = tot.team
  GROUP BY T.teamName          
ORDER BY totalPoints DESC, totalGoalDiff DESC, totalFor DESC
";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':divisionId' => $divisionId));
        $res = $query->fetchAll();
        return $res;
        
    }

    /**
     * generates a league table upto a certain gameweek
     * including that gameweek
     * @param type $seasonId
     * @return type
     */
    public function generateLeagueTableUpToGameweek($seasonId, $uptoIncludingGameweek, $limitPlayers) {
        $sql = "SELECT
              T.teamId as teamId,
              T.teamName AS teamName,
              Sum(P) AS P,
              Sum(W) AS W,
              Sum(L) AS L,
              COALESCE(SUM(F), 0) as F,
              COALESCE(SUM(A), 0) as A,
              COALESCE(SUM(GD), 0) as GD,
              SUM(WinPts) AS WinPts,
              SUM(GranPts) AS GranPts,
              SUM(LosePts) AS LosePts,
              SUM(WinPts + GranPts + LosePts) as total
            FROM(
                  SELECT
                    homeTeamId as team,
                    IF(F.statusId = 2,1,0) P,
                    IF(homeScore > awayScore,1,0) W,
                    IF(homeScore < awayScore,1,0) L,
                    homeScore F,
                    awayScore A,
                    homeScore-awayScore GD,
                    homeWinPts WinPts,
                    homeGrannyPts GranPts,
                    homeLosePts LosePts
                  FROM leagueFixture LF

                    JOIN fixture F ON F.fixtureId = LF.fixtureId
                  WHERE seasonId = :seasonId AND gameweek <= :gameweek
                  UNION ALL
                  SELECT
                    awayTeamId,
                    IF(F.statusId = 2,1,0),
                    IF(homeScore < awayScore,1,0),
                    IF(homeScore > awayScore,1,0),
                    awayScore,
                    homeScore,
                    awayScore-HomeScore GD,
                    awayWinPts,
                    awayGrannyPts,
                    awayLosePts
                  FROM leagueFixture LF
                    JOIN fixture F ON F.fixtureId = LF.fixtureId
                  WHERE seasonId = :seasonId AND gameweek <= :gameweek
                ) as tot
              JOIN team T ON T.teamId = tot.team
            GROUP BY T.teamName
            ORDER BY SUM(WinPts + GranPts + LosePts) DESC, GD DESC LIMIT " . $limitPlayers;
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':gameweek' => $uptoIncludingGameweek));
        $res = $query->fetchAll();
        return $res;
    }

    public function getTeamsParticipatingInSeasonAndDivisionId($seasonId, $divisionId) {
        $sql = "SELECT DISTINCT T.teamId as TeamId
                FROM fixture F
                  JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
                  JOIN team T ON T.teamId = F.homeTeamId
                  WHERE seasonId = :seasonId AND divisionId = :divisionId
                UNION
                SELECT DISTINCT T.teamId
                FROM fixture F
                  JOIN leagueFixture LD ON LD.fixtureId = F.fixtureId
                  JOIN team T ON T.teamId = F.awayTeamId
                  WHERE seasonId = :seasonId AND divisionId = :divisionId";
        $query = $this->db->prepare($sql);
        $query->execute(array('seasonId' => $seasonId, 'divisionId' => $divisionId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $team = new TeamModel($this->db);
            $arr[] = $team->load($val->TeamId);
        }
        return $arr;
    }

    public function getGameWeekByHomeTeamIdAndAwayTeamIdAndSeasonId($homeTeamId, $awayTeamId, $seasonId) {
        $returnVal = '';
        $sql = "SELECT LF.gameweek as gameweek
        FROM fixture F
        JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
        WHERE F.homeTeamId = :homeTeamId AND F.awayTeamId = :awayTeamId
         AND seasonId = :seasonId";
        $query = $this->db->prepare($sql);
        $query->execute(array('homeTeamId' => $homeTeamId, 'awayTeamId' => $awayTeamId, ':seasonId' => $seasonId));
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $returnVal = $res[0]->gameweek;
        }
        return $returnVal;
    }

    public function getFixtureByHomeTeamIdAndAwayTeamIdAndSeasonIdAndDivisionId($homeTeamId, $awayTeamId, $seasonId, $divisionId) {
        $sql = "SELECT F.fixtureId
        FROM fixture F
        JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
        WHERE F.homeTeamId = :homeTeamId AND F.awayTeamId = :awayTeamId
         AND seasonId = :seasonId AND divisionId = :divisionId";
        $query = $this->db->prepare($sql);
        $query->execute(array('homeTeamId' => $homeTeamId, 'awayTeamId' => $awayTeamId, ':seasonId' => $seasonId, ':divisionId' => $divisionId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixture = new LeagueFixtureModel($this->db);
            $arr[] = $fixture->loadByFixtureId($val->fixtureId);
        }
        return $arr;
    }

    public function getRecentDivisionResultsByCompetitionIdAndSeasonId($competitionId, $seasonId, $numResults) {
        $sql = sprintf("SELECT F.fixtureId, LF.leagueFixtureId
	    	FROM fixture F
	    	JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
                JOIN division D ON D.divisionId = LF.divisionId
	    	WHERE F.competitionId = :competitionId AND LF.seasonId = :seasonId
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

    public function getDivisionIdByTeamIdAndSeasonId($teamId, $seasonId) {
        $sql = "SELECT LF.divisionId
        FROM leagueFixture LF
        JOIN fixture F ON LF.fixtureId = F.fixtureId
        WHERE LF.seasonId = :seasonId AND homeTeamId = :homeTeamId";

        $query = $this->db->prepare($sql);
        $query->execute(array(':homeTeamId' => $teamId, ':seasonId' => $seasonId));
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $returnVal = $res[0]->divisionId;
        } else {
            $returnVal = false;
            //$divisionModel = new DivisionModel($this->db);
            //$divObj = $divisionModel->getLowestDivision();
            //$returnVal = $divObj[0]->divisionId;
        }
        return $returnVal;
    }

    public function loadAllDivisionsBySeasonId($seasonId) {
        $sql = "SELECT D.divisionId, divisionName, divisionOrder FROM division D "
                . "JOIN leagueFixture LF ON LF.divisionId = D.divisionId "
                . "JOIN fixture F ON LF.fixtureId = F.fixtureId "
                . "WHERE LF.seasonId = :seasonId GROUP BY D.divisionId ORDER BY D.divisionOrder ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId));

        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $division = new DivisionModel($this->db);
            $arr[$val->divisionId]['divisionId'] = $val->divisionId;
            $arr[$val->divisionId]['divisionName'] = $val->divisionName;
            $arr[$val->divisionId]['divisionOrder'] = $val->divisionOrder;
            $arr[$val->divisionId]['seasonId'] = $seasonId;
        }
        return $arr;
    }

    public function getAllTeamsByDivisionIdAndSeasonId($divisionId, $seasonId) {
        $sql = "SELECT D.divisionId, divisionName, divisionOrder FROM division D "
                . "JOIN leagueFixture LF ON LF.divisionId = D.divisionId "
                . "JOIN fixture F ON LF.fixtureId = F.fixtureId "
                . "WHERE LF.seasonId = :seasonId AND LF.seasonId = :seasonId "
                . "GROUP BY D.divisionId ORDER BY D.divisionOrder ASC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':divisionId' => $divisionId, ':seasonId' => $seasonId));

        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $teams = new TeamModel($this->db);
            $arr[$val->teamId] = $teams->load($val->teamId);
        }
        return $arr;
    }

}
