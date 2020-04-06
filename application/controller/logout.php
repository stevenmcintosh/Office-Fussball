<?php
class Logout extends Controller
{
	function __construct()
    {
        parent::__construct();
        if (!MENU_LOGOUT) {
            //header('location: ' . URL . "error");
        }
    }
    
    public function index()
    {
        global $user;
        $user->logout();
        
        $_SESSION['feedback_positive']['saved'] = "Thanks for playing PS Fussball. You have logged out";
        header('location: ' . URL); exit();
    }
}