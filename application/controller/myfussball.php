<?php
class MyFussball extends Controller
{
    public function index()
    {
        global $user;
        require APP . 'view/_templates/header.php';
        require APP . 'view/myfussball/index.php';
        require APP . 'view/_templates/footer.php';
    }

}
