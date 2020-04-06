<?php
class Rules extends Controller
{

    function __construct()
    {
        $this->activeNav = 'rules';
        parent::__construct();

        if(!MENU_HELP) {
            //header('location: ' . URL . "error");
        }
    }

    public function index()
    {
    	require APP . 'view/_templates/header.php';
        require APP . 'view/rules/index.php';
        require APP . 'view/_templates/footer.php';
    }
}