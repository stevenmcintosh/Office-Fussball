<?php
class User extends Controller
{

    function __construct()
    {
        //$this->activeNav = 'user';
        parent::__construct();

        if (!MENU_TEAMS) {
            //header('location: ' . URL . "error");
        }

        UserAuth::adminProtectedPage();
    }

    /*   IS THIS CLASS EVEN NEEDED?


    public function index()
    {
        UserAuth::adminProtectedPage();
        $userModel = new UserModel($this->db);
        $allUsers = $userModel->getAllActiveUsers();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/user.php';
        require APP . 'view/_templates/footer.php';
    }*/


    
    
    
    
}