<?php
class Error extends Controller
{
    function __construct()
    {
        $this->activeNav = 'error';
        parent::__construct();
    }

    public function index()
    {
        $errorType = "404";
        require APP . 'view/_templates/header.php';
        require APP . 'view/error/index.php';
        require APP . 'view/_templates/footer.php';
    }
    
	public function _403()
    {
    	$errorType = "403";
        require APP . 'view/_templates/header.php';
        require APP . 'view/error/index.php';
        require APP . 'view/_templates/footer.php';
    }
}
