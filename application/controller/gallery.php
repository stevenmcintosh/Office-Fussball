<?php

class Gallery extends Controller {

    function __construct() {
        $this->activeNav = 'gallery';
        parent::__construct();

        if (!MENU_ADMIN) {
            //header('location: ' . URL . "error");
        }

        //UserAuth::adminProtectedPage();
    }

    public function index() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/gallery/index.php';
        require APP . 'view/_templates/footer.php';
    }

    
}
