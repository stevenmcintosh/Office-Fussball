<?php
class Sportsbook extends Controller
{

    function __construct()
    {
        $this->activeNav = 'sportsbook';
        parent::__construct();

        if(!MENU_SPORTSBOOK) {
            //header('location: ' . URL . "error");
        }
    }

    public function index()
    {
        $sportsbookModel = $this->loadModel('SportsbookModel');
        if($sportsbookModel->validate()) {
            $sportsbookModel->setMaxCellsForCsv();
            $sportsbookMaxCells = $sportsbookModel->maxCells;
            $sportsbookData = $sportsbookModel->getDataFromCsv();
            $csvErrors = $sportsbookModel->errors;
        }
        if(!empty($sportsbookModel->errors)) {
            foreach($sportsbookModel->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
        }

    	require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/sportsbook/index.php';
        require APP . 'view/_templates/footer.php';
    }
}