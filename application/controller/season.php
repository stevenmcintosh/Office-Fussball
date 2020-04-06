<?php

class Season extends Controller {

    function __construct() {
        $this->activeNav = 'season';
        parent::__construct();

        if (!MENU_SEASON) {
            //header('location: ' . URL . "error");
        }
       
    }

    public function index() {
        header('location: ' . URL . "error");
    }

    // used to show the user that no seasons exist. Handy when trying to view 
    //a page beore the first seaosn is created.
    public function missing() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/season/no_season_exists.php';
        require APP . 'view/_templates/footer.php';
    }

}
