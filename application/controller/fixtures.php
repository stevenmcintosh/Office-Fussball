<?php

class fixtures extends Controller {

    function __construct() {
        $this->activeNav = 'fixtures';
        parent::__construct();

        if (!MENU_FIXTURES) {
            //header('location: ' . URL . "error");
        }
    }

    public function index() {
        $seasonModel = $this->loadModel('SeasonModel');
        $divisionModel = $this->loadModel('DivisionModel');
        $leagueFixturesModel = $this->loadModel('LeagueFixtureModel');

        if (!$seasonModel->doesSeasonExist()) {
            header('location: ' . URL . "season/missing");
        } else {
            $allSeasons = $seasonModel->loadAllSeasons();

            $seasonToView = $seasonModel->getCurrentSeason();
            $allPastSeasons = $seasonModel->loadAllPastSeasons();


            $allDivisions = $leagueFixturesModel->loadAllDivisionsBySeasonId($seasonToView);
            $leagueFixtures = array();
            foreach ($allDivisions as $division) {
                $divisionId = $division['divisionId'];
                $leagueFixtures[$divisionId] = $leagueFixturesModel->loadAllFixturesByCompetitionIdAndSeasonIdAndDivisionId(1, $seasonToView, $division['divisionId']);
            }

            /* $leagueFixtures = $leagueFixtureModel->loadAllFixturesByCompetitionIdAndSeasonId(1, $seasonToView);
              $teamsInSeason = $leagueFixturesModel->getTeamsParticipatingInSeason($seasonToView);
              $numTeamsInSeason = count($teamsInSeason);
             */
        }

        require APP . 'view/_templates/header.php';
        require APP . 'view/fixtures/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function firstTo3cup() {
        $season = 1;
        $gameweek = 2;
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $seasonModel = $this->loadModel('SeasonModel');
        $currentSeasonId = $seasonModel->getCurrentSeason();
        $leagueTable = $leagueFixtureModel->generateLeagueTableUpToGameweek($currentSeasonId, $gameweek, '3');
        if (!empty($leagueTable)) {
            foreach ($leagueTable as $key => $tableRow) {
                $teamModel = new TeamModel($this->db);
                $leagueTable[$key]->team = $teamModel->load($tableRow->teamId);
            }
        }

        $remainingGamesUntilCupDraw = $leagueFixtureModel->getTotalOpenFixturesUpToGameweekForSeason($season, $gameweek);
        if ($remainingGamesUntilCupDraw <= 0) {
            $pageToShow = 'firstTo3Cup-started';
        } else {
            $pageToShow = 'firstTo3Cup-not-started';
        }


        require APP . 'view/_templates/header.php';
        require APP . 'view/fixtures/' . $pageToShow . '.php';
        require APP . 'view/_templates/footer.php';
    }

    public function past($seasonToView) {
        $seasonModel = $this->loadModel('SeasonModel');
        $divisionModel = $this->loadModel('DivisionModel');
        $leagueFixturesModel = $this->loadModel('LeagueFixtureModel');

        if (!$seasonModel->doesSeasonExist()) {
            header('location: ' . URL . "season/missing");
        } else {
            $allSeasons = $seasonModel->loadAllSeasons();

            //$seasonToView = $seasonModel->getCurrentSeason();
            $allPastSeasons = $seasonModel->loadAllPastSeasons();


            $allDivisions = $leagueFixturesModel->loadAllDivisionsBySeasonId($seasonToView);
            $leagueFixtures = array();
            foreach ($allDivisions as $division) {
                $divisionId = $division['divisionId'];
                $leagueFixtures[$divisionId] = $leagueFixturesModel->loadAllFixturesByCompetitionIdAndSeasonIdAndDivisionId(1, $seasonToView, $division['divisionId']);
            }
        }

        require APP . 'view/_templates/header.php';
        require APP . 'view/fixtures/past.php';
        require APP . 'view/_templates/footer.php';
    }

    public function myresults() {
        global $user;
        $seasonModel = $this->loadModel('SeasonModel');
        $divisionModel = $this->loadModel('DivisionModel');
        $leagueFixturesModel = $this->loadModel('LeagueFixtureModel');
        $teamModel = $this->loadModel('TeamModel');

        $teams = $teamModel->getTeamIdsByUserId($user->userId);

        $leagueFixturesAllTeams = array();
        foreach ($teams as $team) {
            //die($team->teamId);
            $leagueFixturesAllTeams[] = $leagueFixturesModel->getAllFixturesByTeamIdAndSeasonId($team->teamId, $seasonModel->getCurrentSeason());
        }

        //$leagueFixtures = $leagueFixturesModel->getAllOpenFixtures($teamId);
        // $leagueFixtures = $leagueFixturesModel->getAllOpenFixtures();

        if (!$seasonModel->doesSeasonExist()) {
            header('location: ' . URL . "season/missing");
        } else {
            $allSeasons = $seasonModel->loadAllSeasons();
            $seasonToView = $seasonModel->getCurrentSeason();
            $allPastSeasons = $seasonModel->loadAllPastSeasons();
        }
        require APP . 'view/_templates/header.php';
        require APP . 'view/fixtures/myresults.php';
        require APP . 'view/_templates/footer.php';
    }

    public function confirmResult() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $leagueFixtures = $leagueFixtureModel->getAllOpenFixtures();

        if (isset($_POST['VerifyByTeamId']) && isset($_POST['fixId']) &&
                isset($_POST['resultHome'][$_POST['fixId']]) &&
                $_POST['resultHome'][$_POST['fixId']] != '' &&
                $_POST['resultAway'][$_POST['fixId']] != '') {
            $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixId']);
            $fixture->provisional_score_verified_by_team_id = $_POST['VerifyByTeamId'];

            if ($_POST['verify'] == 'Undo') {
                $fixture->removeProvisionalResultFromUserInput();
            } else {
                
                $fixture->homeScore = $fixture->team_a_provisional_score;
                $fixture->awayScore = $fixture->team_b_provisional_score;
                $fixture->calcLeaguePoints();
//print_r($fixture);
                if (!$fixture->saveProvisionalResultFromUserVerify()) {
                    foreach ($fixture->errors['feedback'] as $key => $feeback) {
                        $_SESSION['feedback_negative'][$key] = $feeback;
                    }
                }
            }
            
            header('location: ' . URL . 'fixtures/myresults');
            exit();
        } else if (isset($_POST['fixId']) &&
                isset($_POST['resultHome'][$_POST['fixId']]) &&
                $_POST['resultHome'][$_POST['fixId']] != '' &&
                $_POST['resultAway'][$_POST['fixId']] != '') {
            $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixId']);

            $fixture->team_a_provisional_score = $_POST['resultHome'][$fixture->fixtureId];
            $fixture->team_b_provisional_score = $_POST['resultAway'][$fixture->fixtureId];
            $fixture->provisional_score_added_by_team_id = $_POST['InputByTeamId'];

            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/feedback.php';
            require APP . 'view/fixtures/confirmResultByUser.php';
            require APP . 'view/_templates/footer.php';
        } else {
            header('location: ' . URL . 'error/_403/');
            exit();
        }
    }

    public function addProvisionalResult() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixtureId']);

        $fixture->team_a_provisional_score = $_POST['provisionalHomeScore'];
        $fixture->team_b_provisional_score = $_POST['provisionalAwayScore'];
        $fixture->provisional_score_added_by_team_id = $_POST['InputByTeamId'];

        if (!$fixture->saveProvisionalResultFromUserInput()) {
            foreach ($fixture->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/feedback.php';
            require APP . 'view/fixtures/confirmResultByUser.php';
            require APP . 'view/_templates/footer.php';
        } else {
            header('location: ' . URL . 'fixtures/myresults');
            exit();
        }
    }

}
