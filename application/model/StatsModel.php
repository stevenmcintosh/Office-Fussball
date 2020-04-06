<?php

class StatsModel {

    public $errors = array();

    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function loadStatsForUser($UserId) {
        $this->matchesPlayed['totalHomeLeagueMatchesPlayed'] = (int) $this->getTotalHomeMatchesPlayedByCompetitionAndUserId($UserId, 1);
        $this->matchesPlayed['totalAwayLeagueMatchesPlayed'] = (int) $this->getTotalAwayMatchesPlayedByCompetitionAndUserId($UserId, 1);
        $this->matchesPlayed['totalHomeLeagueMatchesWon'] = (int) $this->getTotalHomeMatchesWonByCompetitionAndUserId($UserId, 1);
        $this->matchesPlayed['totalAwayLeagueMatchesWon'] = (int) $this->getTotalAwayMatchesWonByCompetitionAndUserId($UserId, 1);
        $this->matchesPlayed['homeLeagueMatchesWonPercentage'] = (int) Utils::getPercentageFromTwoAmounts($this->matchesPlayed['totalHomeLeagueMatchesWon'], $this->matchesPlayed['totalHomeLeagueMatchesPlayed'], 2);
        $this->matchesPlayed['awayLeagueMatchesWonPercentage'] = (int) Utils::getPercentageFromTwoAmounts($this->matchesPlayed['totalAwayLeagueMatchesWon'], $this->matchesPlayed['totalAwayLeagueMatchesPlayed'], 2);
    }

    public function getFixturesWithBiggestWinBySeasonId($seasonId, $divisionId) {
        $sql = "SELECT leagueFixtureId
                FROM leagueFixture
                WHERE seasonId = :seasonId1 AND divisionId = :divisionId1
                AND (homeScore = (SELECT least(homeScore, awayScore) as least
                FROM leagueFixture LF
                JOIN fixture F ON F.fixtureId = LF.fixtureId
                where seasonId = :seasonId2 and statusId = 2 AND divisionId = :divisionId2
                ORDER BY least ASC LIMIT 1)
                OR awayScore = (SELECT least(homeScore, awayScore) as least
                FROM leagueFixture LF
                JOIN fixture F ON F.fixtureId = LF.fixtureId
                where seasonId = :seasonId3 and statusId = 2 AND divisionId = :divisionId3
                ORDER BY least ASC LIMIT 1))";
        $query = $this->db->prepare($sql);

        //echo Helper::debugPDO($sql,array(':seasonId1' => $seasonId,':seasonId2' => $seasonId,':seasonId3' => $seasonId));
        $query->execute(array(':seasonId1' => $seasonId, ':seasonId2' => $seasonId, ':seasonId3' => $seasonId,
            ':divisionId1' => $divisionId, ':divisionId2' => $divisionId, ':divisionId3' => $divisionId));
        $arr = array();
        foreach ($query->fetchAll() as $key => $val) {
            $fixtures = new LeagueFixtureModel($this->db);
            $arr[$val->leagueFixtureId] = $fixtures->load($val->leagueFixtureId);
        }
        return $arr;
    }

    /**
     * @param $userId
     * @param $competitionId
     * @return number of matches played at home
     * returns the number of matches played for a competition (ie. league) by the user
     */
    public function getTotalHomeMatchesPlayedByCompetitionAndUserId($userId, $competitionId) {
        $sql = "SELECT count(LF.leagueFixtureId) as totalMatchesPlayed
                FROM fixture F
              JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
              JOIN team T1 ON T1.teamId = F.homeTeamId
              JOIN teamUser TU ON TU.teamId = T1.teamId
              JOIN user U ON U.userId = TU.userId
              WHERE F.competitionId = :competitionId
              AND U.userId = :userId AND teamType = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':userId' => $userId, ':competitionId' => $competitionId));
        $res = $query->fetchAll();
        $returnVal = $res[0]->totalMatchesPlayed;
        return $returnVal;
    }

    /**
     * @param $userId
     * @param $competitionId
     * @return number of matches played away
     * returns the number of matches played for a competition (ie. league) by the user
     */
    public function getTotalAwayMatchesPlayedByCompetitionAndUserId($userId, $competitionId) {
        $sql = "SELECT count(LF.leagueFixtureId) as totalMatchesPlayed
                FROM fixture F
              JOIN leaguefixture LF ON LF.fixtureId = F.fixtureId
              JOIN team T1 ON T1.teamId = F.awayTeamId
              JOIN teamUser TU ON TU.teamId = T1.teamId
              JOIN user U ON U.userId = TU.userId
              WHERE F.competitionId = :competitionId
              AND U.userId = :userId AND TeamType = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':userId' => $userId, ':competitionId' => $competitionId));
        $res = $query->fetchAll();
        $returnVal = $res[0]->totalMatchesPlayed;
        return $returnVal;
    }

    public function getTotalHomeMatchesWonByCompetitionAndUserId($userId, $competitionId) {
        $sql = "SELECT count(LF.leagueFixtureId) as totalMatchesWon
                FROM fixture F
              JOIN leagueFixture LF ON LF.fixtureId = F.fixtureId
              JOIN team T1 ON T1.teamId = F.homeTeamId
              JOIN teamUser TU ON TU.teamId = T1.teamId
              JOIN user U ON U.userId = TU.userId
              WHERE F.competitionId = :competitionId
              AND U.userId = :userId AND teamType = 1
              AND LF.homeScore > LF.awayScore";
        $query = $this->db->prepare($sql);
        $query->execute(array(':userId' => $userId, ':competitionId' => $competitionId));
        $res = $query->fetchAll();
        $returnVal = $res[0]->totalMatchesWon;
        return $returnVal;
    }

    public function getTotalAwayMatchesWonByCompetitionAndUserId($userId, $competitionId) {
        $sql = "SELECT count(LF.leagueFixtureId) as totalMatchesWon
                FROM fixture F
              JOIN leaguefixture LF ON LF.fixtureId = F.fixtureId
              JOIN team T1 ON T1.teamId = F.awayTeamId
              JOIN teamUser TU ON TU.teamId = T1.teamId
              JOIN user U ON U.userId = TU.userId
              WHERE F.competitionId = :competitionId
              AND U.userId = :userId AND teamType = 1
              AND LF.homeScore > LF.awayScore";
        $query = $this->db->prepare($sql);
        $query->execute(array(':userId' => $userId, ':competitionId' => $competitionId));
        $res = $query->fetchAll();
        $returnVal = $res[0]->totalMatchesWon;
        return $returnVal;
    }

    /**
     * get the top average goalscorers of a season
     * returns an array of the results
     * @param int $seasonId the season to search
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getTopAverageGoalScorers($seasonId = 0, $limit = 0) {
        $sql = "SELECT
          teamName,
          SUM(games) as totalGames,
          SUM(goals) as totalGoals,
          FORMAT(SUM(goals)/sum(games),2) as avgGoals
          FROM (
                 SELECT
                   homeTeamId theTeamId,
                   SUM(homeScore) goals,
                   count(homeTeamId) as games
                 FROM leagueFixture LF
                 JOIN fixture F ON F.fixtureId = LF.fixtureId
                 WHERE seasonId = :seasonId AND statusId = 2
                 GROUP BY homeTeamId
                 UNION ALL

                 SELECT
                   awayTeamId,
                   SUM(awayScore),
                   count(awayTeamId)
                 FROM leagueFixture LF
                 JOIN fixture F ON F.fixtureId = LF.fixtureId
                 WHERE seasonId = :seasonId AND statusId = 2
                 GROUP BY awayTeamId
               ) totals
        JOIN team T ON T.teamId = totals.theTeamId
        GROUP BY teamName
        ORDER BY SUM(goals)/SUM(games) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $sql_array[':seasonId'] = $seasonId;

        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    /**
     * get the top average goal conceeder of a season
     * returns an array of the results
     * @param int $seasonId the season to search
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getTopAverageGoalConceeded($seasonId = 0, $limit = 0) {
        $sql = "SELECT
          teamName,
          SUM(games) as totalGames,
          SUM(goals) as totalGoals,
          FORMAT(SUM(goals)/sum(games),2) as avgGoals
          FROM (
                 SELECT
                   homeTeamId theTeamId,
                   SUM(awayScore) goals,
                   count(homeTeamId) as games
                 FROM leagueFixture LF
                 JOIN fixture F ON F.fixtureId = LF.fixtureId
                 WHERE seasonId = :seasonId AND statusId = 2
                 GROUP BY homeTeamId
                 UNION ALL

                 SELECT
                   awayTeamId,
                   SUM(homeScore),
                   count(awayTeamId)
                 FROM leagueFixture LF
                 JOIN fixture F ON F.fixtureId = LF.fixtureId
                 WHERE seasonId = :seasonId AND statusId = 2
                 GROUP BY awayTeamId
               ) totals
        JOIN team T ON T.teamId = totals.theTeamId
        GROUP BY teamName
        ORDER BY SUM(goals)/SUM(games) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $sql_array[':seasonId'] = $seasonId;

        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    public function getAverageGoalsPerGameBySeasonId($seasonId, $divisionId) {
        $sql = "select sum((LF.homeScore + LF.awayScore)/
            (select count(LF.fixtureId) from fsbl.leagueFixture LF 
            join fsbl.fixture F on F.fixtureId = LF.fixtureId
            where LF.seasonId = :seasonId1 AND LF.divisionId = :divisionId1 AND F.statusId = 2)
            ) as avgTotalGoals from fsbl.leagueFixture LF 
            join fsbl.fixture F on F.fixtureId = LF.fixtureId
            where LF.seasonId = :seasonId2 AND LF.divisionId = :divisionId2 AND F.statusId = 2";
        $query = $this->db->prepare($sql);
        $query->execute(array(':seasonId1' => $seasonId, ':seasonId2' => $seasonId, ':divisionId1' => $divisionId, ':divisionId2' => $divisionId));
        $res = $query->fetchAll();
        return $res;
    }

    public function getPercentageHomeWins($seasonId, $divisionId) {
        $sql = "select T.total/count(*)*100 AS percent
                from fsbl.leagueFixture LF
                join fsbl.fixture F on F.fixtureId = LF.fixtureId,

                (select count(*) as total from fsbl.leagueFixture LF 
                join fsbl.fixture F on F.fixtureId = LF.fixtureId
                where LF.divisionId = :divisionId1 AND F.statusId = 2 AND seasonId = :seasonId1 AND homeScore > awayScore) AS T

                where LF.divisionId = :divisionId2 AND F.statusId = 2 AND seasonId = :seasonId2";
        $query = $this->db->prepare($sql);
        $query->execute(array(':divisionId1' => $divisionId, ':divisionId2' => $divisionId, ':seasonId1' => $seasonId, ':seasonId2' => $seasonId));
        $res = $query->fetchAll();
        return $res;
    }

    public function getTotalGrannies($seasonId, $divisionId) {
        $sql = "select count(homeGrannyPts + awayGrannyPts) as totalGrannies 
                from fsbl.leagueFixture LF
                join fsbl.fixture F on F.fixtureId = LF.fixtureId
                where LF.divisionId = :divisionId AND F.statusId = 2 AND seasonId = :seasonId 
                AND (homeGrannyPts > 0 OR awayGrannyPts > 0)";
        $query = $this->db->prepare($sql);
        $query->execute(array(':divisionId' => $divisionId, ':seasonId' => $seasonId));
        $res = $query->fetchAll();
        return $res;
    }

    public function getCloseGames($seasonId, $divisionId) {
        $sql = "select count(homeLosePts + awayLosePts) as totalCloseGames 
                from fsbl.leagueFixture LF
                join fsbl.fixture F on F.fixtureId = LF.fixtureId
                where LF.divisionId = :divisionId AND F.statusId = 2 AND seasonId = :seasonId 
                AND (homeLosePts > 0 OR awayLosePts > 0)";
        $query = $this->db->prepare($sql);
        $query->execute(array(':divisionId' => $divisionId, ':seasonId' => $seasonId));
        $res = $query->fetchAll();
        return $res;
    }
    /**
     * get the top goalscorers of all time
     * returns an array of the teamnames
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getAllTimeTopGoalScorers($limit = 0) {
        $sql = "SELECT
        teamName,
        SUM(games) as totalGames,
        SUM(goals) as totalGoals,
        FORMAT(SUM(goals)/sum(games),2) as avgGoals
        FROM (
        SELECT
        homeTeamId theTeamId,
        SUM(homeScore) goals,
        count(homeTeamId) as games
        FROM leagueFixture LF
        JOIN fixture F ON F.fixtureId = LF.fixtureId
        WHERE statusId = 2
        GROUP BY homeTeamId
        UNION ALL

        SELECT
        awayTeamId,
        SUM(awayScore),
        count(awayTeamId)
        FROM leagueFixture LF
        JOIN fixture F ON F.fixtureId = LF.fixtureId
        WHERE statusId = 2
        GROUP BY awayTeamId
        ) totals
        JOIN team T ON T.teamId = totals.theTeamId
        GROUP BY teamName
        ORDER BY SUM(goals) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    /**
     * get the highest goalscorers of any single season
     * returns an array of the teamnames
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getAllTimeTopGoalScorersOfASeason($limit = 0) {
        $sql = "SELECT
            teamName,
            SUM(games) as totalGames,
            SUM(goals) as totalGoals,
            FORMAT(SUM(goals)/sum(games),2) as avgGoals,
            seasonId, seasonName
            FROM (
            SELECT
            homeTeamId theTeamId,
            SUM(homeScore) goals,
            count(homeTeamId) as games,
            LF.seasonId, seasonName
            FROM leagueFixture LF
            JOIN fixture F ON F.fixtureId = LF.fixtureId
            JOIN season S ON S.seasonId = LF.seasonId
            WHERE F.statusId = 2
            GROUP BY homeTeamId, seasonId
            UNION ALL

            SELECT
            awayTeamId,
            SUM(awayScore),
            count(awayTeamId),
            LF.seasonId, seasonName
            FROM leagueFixture LF
            JOIN fixture F ON F.fixtureId = LF.fixtureId
            JOIN season S ON S.seasonId = LF.seasonId
            WHERE F.statusId = 2
            GROUP BY awayTeamId, seasonId
            ) totals
            JOIN team T ON T.teamId = totals.theTeamId
            GROUP BY teamName, seasonId
            ORDER BY SUM(goals) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    /**
     * get the top average goalscorers of all time
     * returns an array of the teamnames
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getAllTimeTopAvgGoalScorers($limit = 0) {
        $sql = "SELECT
        teamName,
        SUM(games) as totalGames,
        SUM(goals) as totalGoals,
        FORMAT(SUM(goals)/sum(games),2) as avgGoals
        FROM (
        SELECT
        homeTeamId theTeamId,
        SUM(homeScore) goals,
        count(homeTeamId) as games
        FROM leagueFixture LF
        JOIN fixture F ON F.fixtureId = LF.fixtureId
        WHERE statusId = 2
        GROUP BY homeTeamId
        UNION ALL

        SELECT
        awayTeamId,
        SUM(awayScore),
        count(awayTeamId)
        FROM leagueFixture LF
        JOIN fixture F ON F.fixtureId = LF.fixtureId
        WHERE statusId = 2
        GROUP BY awayTeamId
        ) totals
        JOIN team T ON T.teamId = totals.theTeamId
        GROUP BY teamName
        ORDER BY SUM(goals)/sum(games) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    /**
     * get the highest avg goalscorers of any single season
     * returns an array of the teamnames
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getAllTimeTopAvgGoalScorersOfASeason($limit = 0) {
        $sql = "SELECT
            teamName,
            SUM(games) as totalGames,
            SUM(goals) as totalGoals,
            FORMAT(SUM(goals)/sum(games),2) as avgGoals,
            seasonId, seasonName
            FROM (
            SELECT
            homeTeamId theTeamId,
            SUM(homeScore) goals,
            count(homeTeamId) as games,
            S.seasonId, seasonName
            FROM leagueFixture LF
            JOIN fixture F ON F.fixtureId = LF.fixtureId
            JOIN season S ON S.seasonId = LF.seasonId
            WHERE F.statusId = 2
            GROUP BY homeTeamId, seasonId
            UNION ALL

            SELECT
            awayTeamId,
            SUM(awayScore),
            count(awayTeamId),
            S.seasonId, seasonName
            FROM leagueFixture LF
            JOIN fixture F ON F.fixtureId = LF.fixtureId
            JOIN season S ON S.seasonId = LF.seasonId
            WHERE F.statusId = 2
            GROUP BY awayTeamId, seasonId
            ) totals
            JOIN team T ON T.teamId = totals.theTeamId
            GROUP BY teamName, seasonId
            ORDER BY SUM(goals)/sum(games) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    /**
     * get the best goal difference of all time
     * returns an array of the teamnames
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getAllTimeBestGoalDifference($limit = 0) {
        $sql = "SELECT
          teamName,
          SUM(games) as totalGames,
          SUM(goals) as totalGoals,
          SUM(goals_conceeded) as totalGoalsConceeded,
          SUM(goals)-SUM(goals_conceeded) as goal_diff,
          FORMAT(SUM(goals)/sum(games),2) as avgGoals
          FROM (
           SELECT
             homeTeamId theTeamId,
             SUM(homeScore) goals,
             SUM(awayScore) goals_conceeded,
             count(homeTeamId) as games
           FROM leagueFixture LF
             JOIN fixture F ON F.fixtureId = LF.fixtureId
           WHERE statusId = 2
           GROUP BY homeTeamId
           UNION ALL

           SELECT
             awayTeamId,
             SUM(awayScore),
             SUM(homeScore),
             count(awayTeamId)
           FROM leagueFixture LF
             JOIN fixture F ON F.fixtureId = LF.fixtureId
           WHERE statusId = 2
           GROUP BY awayTeamId
         ) totals
          JOIN team T ON T.teamId = totals.theTeamId
        GROUP BY teamName
        ORDER BY SUM(goals)-SUM(goals_conceeded) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

    /**
     * get the best goal difference of any single season
     * returns an array of the teamnames
     * @param int $limit the number of results, 0 = unlimited
     * @return results
     */
    public function getAllTimeBestGoalDifferenceOfASeason($limit = 0) {
        $sql = "SELECT
            teamName,
            SUM(games) as totalGames,
            SUM(goals) as totalGoals,
            SUM(goals_conceeded) as totalGoalsConceeded,
            SUM(goals)-SUM(goals_conceeded) as goal_diff,
            FORMAT(SUM(goals)/sum(games),2) as avgGoals,
            seasonId, seasonName
            FROM (
                SELECT
                homeTeamId theTeamId,
                SUM(homeScore) goals,
                SUM(awayScore) goals_conceeded,
                count(homeTeamId) as games,
                S.seasonId, seasonName
                FROM leagueFixture LF
                JOIN fixture F ON F.fixtureId = LF.fixtureId
                JOIN season S ON S.seasonId = LF.seasonId
                WHERE F.statusId = 2
                GROUP BY homeTeamId, seasonId
                UNION ALL

                SELECT
                awayTeamId,
                SUM(awayScore),
                SUM(homeScore),
                count(awayTeamId),
                S.seasonId, seasonName
                FROM leagueFixture LF
                JOIN fixture F ON F.fixtureId = LF.fixtureId
                JOIN season S ON S.seasonId = LF.seasonId
                WHERE F.statusId = 2
                GROUP BY awayTeamId, seasonId
                ) totals
            JOIN team T ON T.teamId = totals.theTeamId
            GROUP BY teamName, seasonId
            ORDER BY SUM(goals)-sum(goals_conceeded) DESC";
        $sql_array = array();
        if ($limit > 0) {
            $sql_array[':limit'] = $limit;
            $sql .= " LIMIT " . $limit;
        }
        $query = $this->db->prepare($sql);
        $query->execute($sql_array);
        $res = $query->fetchAll();
        return $res;
    }

}
