<?php
class Help extends Controller
{

    function __construct()
    {
        $this->activeNav = 'help';
        parent::__construct();

    }

    public function index()
    {
        
        $userModel = new UserModel($this->db);
        $teamModel = new TeamModel($this->db);
        $allActiveTeams = $teamModel->loadSingleTeams();
        $allUsersWaitingToJoin = $userModel->getAllUsersWaitingToJoin();
    	require APP . 'view/_templates/header.php';
        require APP . 'view/help/index.php';
        require APP . 'view/_templates/footer.php';
    }
}