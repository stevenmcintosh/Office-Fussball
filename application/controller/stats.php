<?php

class Stats extends Controller {

    function __construct() {
        $this->activeNav = 'stats';
        parent::__construct();

        if (!MENU_STATS) {
            // header('location: ' . URL . "error");
        }
    }

    public function index() {
        
	$seasonModel = $this->loadModel('SeasonModel');
        $seasonModel->redirectIfSeasonDoesNotExist();

        $seasonToView = $seasonModel->getCurrentSeason();

        if (empty($seasonToView)) {
            $seasonToView = $seasonModel->getPreviousSeason();
        }

        $allPastSeasons = $seasonModel->loadAllPastSeasons();
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        
        $statsModel = $this->loadModel('StatsModel');
       
        $allDivisions = $leagueFixtureModel->loadAllDivisionsBySeasonId($seasonToView);
        
        $totalFixtures = array();
        $totalOpenFixtures = array();
        $totalClosedFixtures = array();
        $biggestWins = array();
        $avgGoalsPerGame = array();
        $percentageHomeWins = array();
        $totalGrannies = array();
        $totalCloseGames = array();
        foreach ($allDivisions as $division) {
               $totalFixtures[$division['divisionId']] = $seasonModel->getTotalFixtures($seasonToView, $division['divisionId']);
               $totalOpenFixtures[$division['divisionId']] = $seasonModel->getTotalOpenFixtures($seasonToView, $division['divisionId']);
               $totalClosedFixtures[$division['divisionId']] = $seasonModel->getTotalClosedFixtures($seasonToView, $division['divisionId']);
               $biggestWins[$division['divisionId']] = $statsModel->getFixturesWithBiggestWinBySeasonId($seasonToView, $division['divisionId']);
               $avgGoalsPerGame[$division['divisionId']] = $statsModel->getAverageGoalsPerGameBySeasonId($seasonToView, $division['divisionId']);
               $totalGrannies[$division['divisionId']] = $statsModel->getTotalGrannies($seasonToView, $division['divisionId']);
               $totalCloseGames[$division['divisionId']] = $statsModel->getCloseGames($seasonToView, $division['divisionId']);
               $percentageHomeWins[$division['divisionId']] = $statsModel->getPercentageHomeWins($seasonToView, $division['divisionId']);
               
            }

        $topScorers = $statsModel->getTopAverageGoalScorers($seasonToView, 5);
        $topConceeders = $statsModel->getTopAverageGoalConceeded($seasonToView, 5);

        require APP . 'view/_templates/header.php';
        require APP . 'view/stats/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function past($seasonToView) {
        $seasonModel = $this->loadModel('SeasonModel');
        $seasonModel->redirectIfSeasonDoesNotExist();

        $allPastSeasons = $seasonModel->loadAllPastSeasons();
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        
        $statsModel = $this->loadModel('StatsModel');
        
        $allDivisions = $leagueFixtureModel->loadAllDivisionsBySeasonId($seasonToView);
        
        $totalFixtures = array();
        $totalOpenFixtures = array();
        $totalClosedFixtures = array();
        $biggestWins = array();
        $avgGoalsPerGame = array();
        $percentageHomeWins = array();
        $totalGrannies = array();
        $totalCloseGames = array();
        foreach ($allDivisions as $division) {
               $totalFixtures[$division['divisionId']] = $seasonModel->getTotalFixtures($seasonToView, $division['divisionId']);
               $totalOpenFixtures[$division['divisionId']] = $seasonModel->getTotalOpenFixtures($seasonToView, $division['divisionId']);
               $totalClosedFixtures[$division['divisionId']] = $seasonModel->getTotalClosedFixtures($seasonToView, $division['divisionId']);
               $biggestWins[$division['divisionId']] = $statsModel->getFixturesWithBiggestWinBySeasonId($seasonToView, $division['divisionId']);
               $avgGoalsPerGame[$division['divisionId']] = $statsModel->getAverageGoalsPerGameBySeasonId($seasonToView, $division['divisionId']);
               $totalGrannies[$division['divisionId']] = $statsModel->getTotalGrannies($seasonToView, $division['divisionId']);
               $totalCloseGames[$division['divisionId']] = $statsModel->getCloseGames($seasonToView, $division['divisionId']);
               $percentageHomeWins[$division['divisionId']] = $statsModel->getPercentageHomeWins($seasonToView, $division['divisionId']);
               
            }

        $topScorers = $statsModel->getTopAverageGoalScorers($seasonToView, 5);
        $topConceeders = $statsModel->getTopAverageGoalConceeded($seasonToView, 5);

        require APP . 'view/_templates/header.php';
        require APP . 'view/stats/past.php';
        require APP . 'view/_templates/footer.php';
    }

    public function doubles() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/stats/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function hall_of_fame() {
        if (!MENU_HALL_OF_FAME) {
            header('location: ' . URL . "error");
        }
        $league = array();
        $leagueFixturesModel = $this->loadModel('LeagueFixtureModel');
        $seasonModel = $this->loadModel('SeasonModel');


        $this->activeNav = 'halloffame';
        $pastSeasons = $seasonModel->loadAllPastSeasons();

        foreach($pastSeasons as $seasonId => $seasonObj) {
            $allDivisions = $leagueFixturesModel->loadAllDivisionsBySeasonId($seasonId);

            foreach ($allDivisions as $division) {
                $seasonLeaders[$division['divisionName']] = $seasonModel->getSeasonLeaders($seasonId, $division['divisionId']);
                $seasonRunnerUps[$division['divisionName']] = $seasonModel->getSeasonRunnerUps($seasonId, $division['divisionId']);
                
                $league[$division['divisionName']][$seasonId]['winner'] = $seasonLeaders[$division['divisionName']];
                $league[$division['divisionName']][$seasonId]['runnerup'] = $seasonRunnerUps[$division['divisionName']];
            }
        }
        //$seasonModel = $this->loadModel('SeasonModel');
        
        if($seasonModel->doesSeasonExist()) {
            $statsModel = $this->loadModel('StatsModel');
            $allTimeTopScorer = $statsModel->getAllTimeTopGoalScorers(1);
            $allTimeTopAvgScorer = $statsModel->getAllTimeTopAvgGoalScorers(1);
            $allTimeTopAvgScorerOfSingleSeason = $statsModel->getAllTimeTopAvgGoalScorersOfASeason(1);
            $allTimeBestGoalDifference = $statsModel->getAllTimeBestGoalDifference(1);
            $allTimeBestGoalDifferenceSingleSeason = $statsModel->getAllTimeBestGoalDifferenceOfASeason(1);
        } else {
            header('location: ' . URL . "season/missing");    
        }
       
        require APP . 'view/_templates/header.php';
        require APP . 'view/stats/hall-of-fame.php';
        require APP . 'view/_templates/footer.php';
    }

    public function fullLeagueTable($seasonRequested = 0) {
        $seasonModel = $this->loadModel('SeasonModel');

        if($seasonModel->doesSeasonExist()) {
            
        } else {
            header('location: ' . URL . "season/missing");    
        }

        $season = $seasonModel->load($seasonRequested);
        $currentSeason = $season->getCurrentSeason();
        
        if($seasonRequested == 0) {
            $seasonToView = $season->getCurrentSeason();
        } else {
            $seasonToView = $season->seasonId;
        }
      
        
        $allPastSeasons = $seasonModel->loadAllPastSeasons();
        
        $leagueFixturesModel = $this->loadModel('LeagueFixtureModel');
        $divisionModel = $this->loadModel('DivisionModel');
        $lowestDivision = $divisionModel->getLowestDivision();
        $highestDivision = $divisionModel->getHighestDivision();
        $lowestDivisionId = $lowestDivision->divisionId;
        $highestDivisionId = $highestDivision->divisionId;
        
        $allDivisions = $leagueFixturesModel->loadAllDivisionsBySeasonId($seasonToView);

            $leagueTables = array();
            
            foreach ($allDivisions as $division) {
                $divisionId = $division['divisionId'];
                $leagueTables[$divisionId] = $leagueFixturesModel->generateLeagueTable($seasonToView, $division['divisionId']);
            }

        require APP . 'view/_templates/header.php';
        require APP . 'view/stats/fullLeagueTable.php';
        require APP . 'view/_templates/footer.php';
    }

    /**
     * @param $userId
     * AJAX call from the url and returns the userstats
     */
    public function loadStatsForUser($userId) {
        $statsModel = $this->loadModel('StatsModel');
        $statsModel->loadStatsForUser($userId);
        echo json_encode($statsModel);
    }

}
