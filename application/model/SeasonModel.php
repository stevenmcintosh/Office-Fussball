<?php

class SeasonModel {

    public $seasonId;
    public $seasonName;
    public $statusId;
    public $errors = array();
    
    public $status; //obj

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
            $sql = "SELECT seasonId, seasonName, statusId FROM season 
	    	WHERE seasonId = :id";
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
        
        $statusModel = new StatusModel($this->db);
        $this->status = $statusModel->load($this->statusId);
    }

    public function loadAllSeasons() {
        $sql = "SELECT seasonId, seasonName, statusId FROM season ORDER BY seasonId DESC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $seasons = new SeasonModel($this->db);
            $arr[$val->seasonId] = $seasons->load($val->seasonId);
        }
        return $arr;
    }

    public function loadAllPastSeasons() {
        $currentSeason = $this->getCurrentSeason();
        $sql = "SELECT seasonId, seasonName, statusId FROM season
        WHERE statusId = 2 ORDER BY seasonId DESC";

        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $seasons = new SeasonModel($this->db);
            $arr[$val->seasonId] = $seasons->load($val->seasonId);
        }
        return $arr;
    }

    public function setStatusToClosed() {
        $sql = "UPDATE season SET statusId = '2' WHERE seasonId = :seasonId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $this->seasonId));
    }

    public function getCurrentSeason() {
        $sql = "SELECT DISTINCT Max(seasonId) as currentSeason FROM season WHERE statusId = '1' LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        return $res[0]->currentSeason;
    }

    public function getPreviousSeason() {
        $sql = "SELECT DISTINCT Max(seasonId) as previousSeason FROM season WHERE statusId = '2' LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        return $res[0]->previousSeason;
    }

    public function getNextSeason() {
        $sql = "SELECT Max(seasonId) as seasonId FROM season LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        return $res[0]->seasonId + 1;
    }

    public function doesSeasonExist() {
        $sql = "SELECT seasonId FROM season";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }
    
    
    public function redirectIfSeasonDoesNotExist() {
        if (!$this->doesSeasonExist()) {
            header('location: ' . URL . "season/missing");
        } 
    }

    public function doesSeasonExistById($seasonId) {
        $sql = "SELECT seasonId FROM season WHERE seasonId = :seasonId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId));
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function createNewSeason() {
        $returnVal = false;
        $sql = "SELECT seasonId FROM leagueFixtureTmp LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        if ($query->rowCount() > 0) {
            $newSeason = $res[0]->seasonId;
        }

        $leagueFixtureModel = new LeagueFixtureModel($this->db);
        $leagueFixtureModel->moveAllTmpToLive();


        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            $leagueFixtureModel->removeTmpFixtures();
            $leagueFixtureModel->removeTmpLeagueFixture();
            $returnVal = $this->addNewSeason($newSeason);
        }
        return $returnVal;
    }

    public function addNewSeason($newSeason) {
        $returnVal = false;
        $sql = "INSERT INTO season (seasonId, seasonName, statusId) VALUES (:seasonId, :seasonName, :statusId)";
        $query = $this->db->prepare($sql);
        $sql_array = array(':seasonId' => $newSeason, ':seasonName' => $newSeason, ':statusId' => 1);
        if ($query->execute($sql_array)) {
            $returnVal = true;
        }
        return $returnVal;
    }

    public function isSeasonOpenById($seasonId) {
        $returnVal = false;
        $sql = "SELECT statusId
    	FROM season
    	WHERE seasonId = :seasonId LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array('seasonId' => $seasonId));
        $res = $query->fetchAll();
        if ($res[0]->statusId == 1) {
            $returnVal = true;
        }
        return $returnVal;
    }

    public function isSeasonStillOpen() {
        $returnVal = false;
        $sql = "SELECT seasonId 
    	FROM season
    	WHERE statusId = 1 LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        if ($query->rowCount() > 0) {
            $returnVal = true;
        }
        return $returnVal;
    }

    public function getTotalFixtures($seasonToView, $divisionId) {
        $sql = "SELECT count(LD.fixtureId) as totalFixtures
                FROM leagueFixture LD
                JOIN fixture F ON LD.fixtureId = F.fixtureId
                WHERE LD.seasonId = :seasonId AND LD.divisionId = :divisionId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonToView, ':divisionId' => $divisionId));
        $res = $query->fetchAll();
        return $res[0]->totalFixtures;
    }

    public function getTotalOpenFixtures($seasonToView, $divisionId) {
        $sql = "SELECT count(LD.fixtureId) as totalFixtures
                FROM leagueFixture LD
                JOIN fixture F ON LD.fixtureId = F.fixtureId
                WHERE LD.seasonId = :seasonId AND LD.divisionId = :divisionId
                AND F.statusId = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonToView, ':divisionId' => $divisionId));
        $res = $query->fetchAll();
        return $res[0]->totalFixtures;
    }
    
    public function getTotalClosedFixtures($seasonToView, $divisionId) {
        $sql = "SELECT count(LD.fixtureId) as totalFixtures
                FROM leagueFixture LD
                JOIN fixture F ON LD.fixtureId = F.fixtureId
                WHERE LD.seasonId = :seasonId AND LD.divisionId = :divisionId
                AND F.statusId = 2";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonToView, ':divisionId' => $divisionId));
        $res = $query->fetchAll();
        return $res[0]->totalFixtures;
    }

    public function getSeasonLeaders($seasonId, $divisionId) {
        $sql = "SELECT T.teamName as team,
                SUM(WinPts + GranPts + LosePts) as pts,
                SUM(F-A) as gd
        FROM(
            SELECT
            homeTeamId as Team,
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
          JOIN fixture F ON F.fixtureId = LF.fixtureId AND LF.divisionId = :divisionId
          WHERE seasonId = :seasonId
          UNION ALL
          SELECT
            awayTeamId,
            IF(F.statusId = 2,1,0),
            IF(homeScore < awayScore,1,0),
            IF(homeScore > awayScore,1,0),
            awayScore,
            homeScore,
            awayScore-homeScore GD,
            awayWinPts,
            awayGrannyPts,
            awayLosePts
          FROM leagueFixture LF
            JOIN fixture F ON F.fixtureId = LF.fixtureId AND LF.divisionId = :divisionId
          WHERE seasonId = :seasonId
        ) as tot
          JOIN team T ON T.teamId = tot.Team
         
        GROUP BY T.teamName
        ORDER BY SUM(WinPts + GranPts + LosePts) DESC, GD DESC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':divisionId' => $divisionId));
        $res = $query->fetchAll();
        return $res[0]->team;
    }

    public function getSeasonRunnerUps($seasonId, $divisionId) {
        $sql = "SELECT T.teamName as team,
SUM(WinPts + GranPts + LosePts) as pts,
SUM(F-A) as gd
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

            JOIN fixture F ON F.fixtureId = LF.fixtureId AND LF.divisionId = :divisionId
          WHERE seasonId = :seasonId
          UNION ALL
          SELECT
            awayTeamId,
            IF(F.statusId = 2,1,0),
            IF(homeScore < awayScore,1,0),
            IF(homeScore > awayScore,1,0),
            awayScore,
            homeScore,
            awayScore-homeScore GD,
            awayWinPts,
            awayGrannyPts,
            awayLosePts
          FROM leagueFixture LF
            JOIN fixture F ON F.fixtureId = LF.fixtureId AND LF.divisionId = :divisionId
          WHERE seasonId = :seasonId
        ) as tot
          JOIN team T ON T.teamId = tot.Team
        GROUP BY T.teamName
        ORDER BY SUM(WinPts + GranPts + LosePts) DESC, GD DESC LIMIT 1,1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId, ':divisionId' => $divisionId));
        $res = $query->fetchAll();
        return $res[0]->team;
    }

    /*
     * public function getFullLeagueTable($seasonId) {
        $sql = "SELECT
  T.teamId as teamId, T.teamName AS teamName,
  Sum(P) AS P,
  Sum(W) AS W,
  Sum(L) AS L,
  COALESCE(SUM(F), 0) as F,
  COALESCE(SUM(A), 0) as A,
  COALESCE(SUM(GD), 0) as GD,
  SUM(WinPts) AS WinPts,
  SUM(GranPts) AS GranPts,
  SUM(LosePts) AS LosePts,
  SUM(WinPts + GranPts + LosePts) as total,

  (
    SELECT
      Sum(IF(F.statusId = 2,1,0))
    FROM leagueFixture LF
    JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as playedHome,
  (
    SELECT
      Sum(IF(homeScore > awayScore,1,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as wonHome,
  (
    SELECT
      Sum(IF(homeScore < awayScore,1,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as lostHome,
  (
    SELECT
      Sum(COALESCE(homeScore,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as forHome,
  (
    SELECT
      Sum(COALESCE(awayScore,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as againstHome,
  (
    SELECT
      Sum(COALESCE(homeScore-awayScore,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as goaldiffHome,
  (
    SELECT
      Sum(homeWinPts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as winPtsHome,
  (
    SELECT
      Sum(homeGrannyPts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as granHome,
  (
    SELECT
      Sum(homeLosePts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as losePtsHome,
  (
    SELECT
  SUM(homeWinPts + homeGrannyPts + homeLosePts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND homeTeamId = tot.team
    GROUP BY T.teamName
  ) as totalPtsHome,
  (
SELECT
  Sum(IF(F.statusId = 2,1,0))
FROM leagueFixture LF
JOIN fixture F ON F.fixtureId = LF.fixtureId
WHERE seasonId = :seasonId AND awayTeamId = tot.team
GROUP BY T.teamName
) as playedAway,
(
SELECT
Sum(IF(homeScore < awayScore,1,0))
FROM leagueFixture LF
JOIN fixture F ON F.fixtureId = LF.fixtureId
WHERE seasonId = :seasonId AND awayTeamId = tot.team
GROUP BY T.teamName
) as wonAway,
(
  SELECT
    Sum(IF(homeScore > awayScore,1,0))
  FROM leagueFixture LF
    JOIN fixture F ON F.fixtureId = LF.fixtureId
  WHERE seasonId = :seasonId AND awayTeamId = tot.team
  GROUP BY T.teamName
) as lostAway,
  (
    SELECT
      Sum(COALESCE(awayScore,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as forAway,
  (
    SELECT
      Sum(COALESCE(homeScore,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as againstAway,
  (
    SELECT
      Sum(COALESCE(awayScore-homeScore,0))
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as goaldiffAway,
  (
    SELECT
      Sum(awayWinPts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as winPtsAway,
  (
    SELECT
      Sum(awayGrannyPts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as granAway,
  (
    SELECT
      Sum(awayLosePts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF.fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as losePtsAway,
  (
    SELECT
    SUM(awayWinPts + awayGrannyPts + awayLosePts)
    FROM leagueFixture LF
      JOIN fixture F ON F.fixtureId = LF. fixtureId
    WHERE seasonId = :seasonId AND awayTeamId = tot.team
    GROUP BY T.teamName
  ) as totalPtsAway

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
      WHERE seasonId = :seasonId
      UNION
      SELECT
        awayTeamId,
        IF(F.statusId = 2,1,0),
        IF(homeScore < awayScore,1,0),
        IF(homeScore > awayScore,1,0),
        awayScore,
        homeScore,
        awayScore-homeScore GD,
        awayWinPts,
        awayGrannyPts,
        awayLosePts
      FROM leagueFixture LF
        JOIN fixture F ON F.fixtureId = LF.fixtureId
      WHERE seasonId = :seasonId
    ) as tot
  JOIN team T ON T.teamId = tot.team

GROUP BY T.teamName
ORDER BY SUM(WinPts + GranPts + LosePts) DESC, GD DESC";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $seasonId));
        $res = $query->fetchAll();
        return $res;
    } */

    
     public function removeSeason() {
        $returnval = true;
        $sql = "DELETE FROM season WHERE seasonId = :seasonId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $this->seasonId));
        if (!$query->execute()) {
            $returnval = false;
        }

        // sql playing up need to use 3 sql commands.. not nice :(
        $sql = "DELETE FROM fixture WHERE fixtureId IN (select fixtureId from leagueFixture where seasonId = :seasonId)";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $this->seasonId));
        if (!$query->execute()) {
            $returnval = false;
        }

        $sql = "DELETE FROM leagueFixture WHERE seasonId = :seasonId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId' => $this->seasonId));
        if (!$query->execute()) {
            $returnval = false;
        }


        return $returnval;

        //DELETE FROM season, leaguefixture JOIN leaguefixture ON leaguefixture.seasonId = season.seasonId WHERE season.seasonId = 8
    }
    
    
    public function validatePreSeason($teams) {
        $returnVal = true;

        if (count($teams) < 3) {
            $this->errors['feedback']['minTeamsNotReached'] = "The minimum number of teams is 3";
        }

        if (count($this->errors) > 0) {
            $returnVal = false;
        }
        return $returnVal;
    }

}
