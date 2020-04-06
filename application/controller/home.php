<?php

class Home extends Controller {

    function __construct() {
        $this->activeNav = 'home';
        parent::__construct();
    }

    public function index() {
        $homePage = 'index';
        $activeNav = 'home';
        $seasonModel = $this->loadModel('SeasonModel');
        $leagueFixturesModel = $this->loadModel('LeagueFixtureModel');
        $divisionModel = $this->loadModel('DivisionModel');
        $lowestDivision = $divisionModel->getLowestDivision();
        $highestDivision = $divisionModel->getHighestDivision();
        $lowestDivisionId = $lowestDivision->divisionId;
        $highestDivisionId = $highestDivision->divisionId;


        if ($seasonModel->doesSeasonExist() && $seasonModel->isSeasonStillOpen()) {
            $currentSeasonId = $seasonModel->getCurrentSeason();

            $allDivisions = $leagueFixturesModel->loadAllDivisionsBySeasonId($currentSeasonId);

            $leagueTables = array();
            
            foreach ($allDivisions as $division) {
                $divisionId = $division['divisionId'];
                $leagueTables[$divisionId] = $leagueFixturesModel->generateLeagueTable($currentSeasonId, $division['divisionId']);
            }

            
            $recentResults = $leagueFixturesModel->getRecentDivisionResultsByCompetitionIdAndSeasonId(1, $currentSeasonId, HOME_PAGE_RECENT_RESULTS);
            //print_r($recentResults);
        } elseif ($seasonModel->doesSeasonExist() && !$seasonModel->isSeasonStillOpen()) {
            $homePage = 'isSeasonEnd';
            $previousSeason = $seasonModel->getPreviousSeason();

            $allDivisions = $leagueFixturesModel->loadAllDivisionsBySeasonId($previousSeason);

            $leagueTables = array();
            $seasonLeaders = array();
            $seasonRunnerUps = array();
            foreach ($allDivisions as $division) {
                $divisionId = $division['divisionId'];
                $leagueTables[$divisionId] = $leagueFixturesModel->generateLeagueTable($previousSeason, $division['divisionId']);
                $seasonLeaders[$division['divisionName']] = $seasonModel->getSeasonLeaders($previousSeason, $division['divisionId']);
                $seasonRunnerUps[$division['divisionName']] = $seasonModel->getSeasonRunnerUps($previousSeason, $division['divisionId']);
            }

            foreach ($seasonLeaders as $divisionName => $user) {
                $league[$divisionName][1] = $user;
            }

            foreach ($seasonRunnerUps as $divisionName => $user) {
                $league[$divisionName][2] = $user;
            }
        } else {
            $homePage = 'createFirstSeason';
        }

        require APP . 'view/_templates/header.php';
        require APP . 'view/home/' . $homePage . '.php';
        require APP . 'view/_templates/footer.php';
    }

}
