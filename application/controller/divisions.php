<?php

class divisions extends Controller {

    function __construct() {
        $this->activeNav = 'divions';
        parent::__construct();

        if (!MENU_FIXTURES) {
            //header('location: ' . URL . "error");
        }
    }

    public function index() {
        
        $seasonModel = $this->loadModel('SeasonModel');
        $seasonIdToView = $seasonModel->getCurrentSeason();
        
        $divsionModel = $this->loadModel('DivisionModel');
        $array = $divsionModel->getLowestDivision();
        echo "<hr><hr>";
        
        
        $divisionTeamSeasonModel = $this->loadModel('DivisionTeamSeasonModel');
        
        //$allDivisions = $divisionUserSeasonModel->loadAllDivisionsBySeasonId($seasonIdToView);
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $allDivisions = $leagueFixtureModel->loadAllDivisionsBySeasonId($seasonIdToView);
        

        $divUsers = $leagueFixtureModel->getDivisionIdByTeamIdAndSeasonId(13,1);
        echo "<pre>";
        print_r($divUsers);
        echo "</pre>";
        
        
        require APP . 'view/_templates/header.php';
        require APP . 'view/division/index.php';
        require APP . 'view/_templates/footer.php';
    }

    

}
