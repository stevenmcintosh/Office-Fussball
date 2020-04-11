<?php
class Team extends Controller
{

    function __construct()
    {
        $this->activeNav = 'team';
        parent::__construct();

        if (!MENU_TEAMS) {
            //header('location: ' . URL . "error");
        }

        //UserAuth::adminProtectedPage();
    }

    public function index()
    {
        $teamModel = new TeamModel($this->db);
        $singles = $teamModel->loadSingleTeams();
        $doubles = $teamModel->loadDoubleTeams();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/team/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function addTeam() {
        UserAuth::adminProtectedPage();
        $userModel = new UserModel($this->db);
        $allUsers = $userModel->getAllActiveUsers();
        require APP . 'view/_templates/header.php';
        require APP . 'view/team/addTeam.php';
        require APP . 'view/_templates/footer.php';
    }

    public function saveTeam() {

        $teamModel = new TeamModel($this->db);
        $teamModel->teamName = $_POST['teamName'];


        print_r($_POST['teamMember']); exit();


        foreach($_POST['teamMember'] as $teamMember) {

            if(!empty($teamMember)) {
                $userModel = new UserModel($this->db);
                $teamModel->teamType = count($_POST['teamMember']);
                $teamModel->teamMembers[] = $userModel->load($teamMember);
            }
        }

        if($teamModel->saveTeam()) {
            $_SESSION['feedback_positive']['saved'] = "The team has been saved";
            header('location: ' . URL . 'team'); exit();
        } else {
            foreach($teamModel->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
        }

        $userModel = new UserModel($this->db);
        $allUsers = $userModel->getAllActiveUsers();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/team/addTeam.php';
        require APP . 'view/_templates/footer.php';
    }
}