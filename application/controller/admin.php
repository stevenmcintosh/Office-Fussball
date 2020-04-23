<?php

class Admin extends Controller {

    function __construct() {
        $this->activeNav = 'admin';
        parent::__construct();

        UserAuth::adminProtectedPage();
    }

    public function index() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/admin/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function viewUsers() {
        UserAuth::adminProtectedPage();
        $userModel = new UserModel($this->db);
       
        $allUsers = $userModel->getAllActiveUsers();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/users.php';
        require APP . 'view/_templates/footer.php';

    }

    public function editUser($userId) {
        UserAuth::adminProtectedPage();
        $userModel = new UserModel($this->db);
        $userToEdit = $userModel->load($userId);

        if(!$userToEdit->userId) {
            header('location: ' . URL . 'admin'); exit();
        }

        require APP . 'view/_templates/header.php';
        require APP . 'view/user/editUser.php';
        require APP . 'view/_templates/footer.php';
    }

    public function addUser() {
        UserAuth::adminProtectedPage();
        $tmpUser = new UserModel($this->db);
        require APP . 'view/_templates/header.php';
        require APP . 'view/user/addUser.php';
        require APP . 'view/_templates/footer.php';
    }


    public function insertUser() {
        $tmpUser = new userModel($this->db);
        $tmpUser->nickname = stripslashes(htmlspecialchars($_POST['nickname']));
        $tmpUser->email = stripslashes(htmlspecialchars($_POST['email']));
        $tmpUser->firstName = stripslashes(htmlspecialchars($_POST['firstName']));
        $tmpUser->lastName = stripslashes(htmlspecialchars($_POST['lastName']));
        $tmpUser->ldapUsername = stripslashes(htmlspecialchars($_POST['ldapUsername']));
        $tmpUser->admin = stripslashes(htmlspecialchars($_POST['isAdmin']));

        if($tmpUser->saveUsers(true)) {
            $_SESSION['feedback_positive']['saved'] = "The user has been saved";
            header('location: ' . URL . 'admin/viewUsers'); exit();
        } else {
            foreach($tmpUser->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
        }
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/user/addUser.php';
        require APP . 'view/_templates/footer.php';
    }



    public function saveUser() {
        $tmpUserId = stripslashes(htmlspecialchars($_POST['userId']));
        $userModel = new userModel($this->db);
        $userModel->load($tmpUserId);

        if(!$userModel->userId) {
            header('location: ' . URL . 'admin'); exit();
        }

        $userModel->nickname = $_POST['nickname'];
        $userModel->email = $_POST['email'];
        $userModel->firstName = $_POST['firstName'];
        $userModel->lastName = $_POST['lastName'];
        $userModel->ldapUsername = $_POST['ldapUsername'];
        $userModel->admin = $_POST['isAdmin'];
        

        if($userModel->saveUsers()) {
            $_SESSION['feedback_positive']['saved'] = "The user has been saved";
            header('location: ' . URL . 'admin/viewUsers'); exit();
        } else {
            foreach($userModel->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
        }

        $allUsers = $userModel->getAllActiveUsers();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/users.php';
        require APP . 'view/_templates/footer.php';
    } 

    public function adminSettings() {
        $adminSettingsModel = new AdminSettingsModel($this->db);
        $allAdminSettings = $adminSettingsModel->getAllAdminSettings();
        

        if (isset($_POST['adminSetting'])) {
            foreach ($_POST['adminSetting'] as $settingId => $val) {
                $settingHasChanged = false;
                $setting = $adminSettingsModel->load($settingId);


                if (isset($val['array-active-only']) && $setting->active != $val['active']) {

                    $setting->active = $val['active'];
                    $setting->value = $val['active'];
                    $settingHasChanged = true;
                }
                if (isset($val['active']) && $setting->active != $val['active']) {
                    $setting->active = $val['active'];
                    $settingHasChanged = true;
                }

                if (isset($val['value']) && $setting->value != $val['value']) {
                    $setting->value = $val['value'];
                    $settingHasChanged = true;
                }

                if ($settingHasChanged) {
                    if ($setting->saveAdminSetting()) {
                        $_SESSION['feedback_positive']['adminSetting-' . $setting->name] = $setting->name . ' was successfully updated. Press refresh to activate your changes.';
                    } else {
                        foreach ($setting->errors['feedback'] as $key => $feeback) {
                            $_SESSION['feedback_negative'][$key] = $feeback;
                        }
                    }
                }
            }
        }

        $allAdminSettings = $adminSettingsModel->getAllAdminSettings();

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/adminSettings.php';
        require APP . 'view/_templates/footer.php';
    }

    public function databaseBackup() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/admin/databaseBackup.php';
        require APP . 'view/_templates/footer.php';
    }

    public function createDatabaseBackup() {
        $databaseModel = $this->loadModel('DatabaseModel');
        $databaseModel->backUpDatabase();
        die();

        header('location: ' . URL . 'admin/databaseBackup');
        exit();
    }

    public function divisions() {
        $divisionModel = $this->loadModel('DivisionModel');
        $allDivisions = $divisionModel->loadAllDivisions();

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/divisions.php';
        require APP . 'view/_templates/footer.php';
    }

    public function editDivision($divisionId) {
        $divisionModel = $this->loadModel('DivisionModel');
        $division = $divisionModel->load($divisionId);

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/editDivision.php';
        require APP . 'view/_templates/footer.php';
    }

    public function removeDivision($divisionId) {
        $divisionModel = $this->loadModel('DivisionModel');
        $division = $divisionModel->load($divisionId);

        if ($division->deleteDivision()) {
            $_SESSION['feedback_positive']['divisionremoved'] = $division->divisionName . ' was successfully removed';
        } else {
            $_SESSION['feedback_negative']['divisionremoved'] = $division->divisionName . ' can not be removed as the divison has been active with fixtures and statistics';
        }

        header('location: ' . URL . 'admin/divisions/');
        exit();
    }

    public function saveDivision($divisionId) {
        $divisionModel = $this->loadModel('DivisionModel');
        $division = $divisionModel->load($divisionId);

        $division->divisionName = $_POST['divisionName'];
        $division->divisionShortName = $_POST['divisionShortName'];
        $division->divisionOrder = $_POST['divisionOrder'];

        if (!$division->saveDivisionFromAdminEdit()) {
            foreach ($division->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/feedback.php';
            require APP . 'view/admin/editDivision.php';
            require APP . 'view/_templates/footer.php';
        } else {
            header('location: ' . URL . 'admin/divisions/');
            exit();
        }
    }

    public function addSeason() {
        global $user;
        $page = 'addSeason';

        $teamModel = new TeamModel($this->db);
        $activeSingleTeams = $teamModel->loadSingleTeams();
        $activeDoubleTeams = $teamModel->loadDoubleTeams();

        $seasonModel = $this->loadModel('SeasonModel');
        if (!$seasonModel->doesSeasonExist()) {
            $newSeason = 1;
            $previousSeason = 0;
        } else {
            $newSeason = $seasonModel->getNextSeason();
            $previousSeason = $seasonModel->getPreviousSeason();
        }
        if ($seasonModel->isSeasonStillOpen()) {
            $page = 'blockAddSeason';
        }

        $division = new DivisionModel($this->db);
        $divisions = $division->loadAllDivisions();

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/' . $page . '.php';
        require APP . 'view/_templates/footer.php';
    }

    public function createSeason() {
        $seasonModel = $this->loadModel('SeasonModel');
        $seasonModel->createNewSeason();
        header('location: ' . URL);
    }

    public function addSeasonPreview() {

        $seasonModel = $this->loadModel('SeasonModel');
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');

        if (isset($_POST['teams']) && !$seasonModel->validatePreSeason($_POST['teams'])) {

            foreach ($seasonModel->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
            header('location: ' . URL . 'admin/addSeason/');
            exit();
        }

        $leagueFixtureModel->removeTmpFixtures();
        $leagueFixtureModel->removeTmpLeagueFixture();

        if (!$seasonModel->doesSeasonExist()) {
            $newSeason = 1;
        } else {
            $newSeason = $seasonModel->getNextSeason();
        }




        $divisionModel = $this->loadModel('DivisionModel');
        $allDivisions = $divisionModel->loadAllDivisions();
        foreach ($allDivisions as $division) {
            foreach ($_POST['teams'] as $teamId) {
                $divisionId = $_POST['teamIdDivisionId'][$teamId];
                if ($division->divisionId == $divisionId) {
                    $teamModel = new TeamModel($this->db);
                    $setUpDivisions[$division->divisionId][$teamId] = $teamModel->load($teamId);
                }
            }
    
        }


        foreach ($setUpDivisions as $div => $teamObj) {
            $teams = array();
            foreach ($teamObj as $teamId => $teamObj) {
                $teams[$teamId] = $teamObj;
                shuffle($teams);

            }

            $leagueFixtureModel->createLeagueFixtures($newSeason, $teams, $div);
        }

        $tmpFixtures = $leagueFixtureModel->getTmpFixtures();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/addSeasonPreview.php';
        require APP . 'view/_templates/footer.php';
    }

    public function editResult() {
        $seasonModel = $this->loadModel('SeasonModel');
        $currentSeason = $seasonModel->getCurrentSeason();

        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $leagueResults = $leagueFixtureModel->getAllLeagueResults($currentSeason);

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/editResult.php';
        require APP . 'view/_templates/footer.php';
    }

    public function addResult() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $leagueFixtures = $leagueFixtureModel->getAllOpenFixtures();

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/addResult.php';
        require APP . 'view/_templates/footer.php';
    }

    public function cancelGame() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $leagueFixtures = $leagueFixtureModel->getAllOpenFixtures();

        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/cancelGame.php';
        require APP . 'view/_templates/footer.php';
    }

    public function confirmResult() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $leagueFixtures = $leagueFixtureModel->getAllOpenFixtures();
//print_r($_POST);

        if (isset($_POST['fixId']) && isset($_POST['resultHome'][$_POST['fixId']]) && $_POST['resultHome'][$_POST['fixId']] != '' && $_POST['resultAway'][$_POST['fixId']] != '') {
            $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixId']);
            $fixture->homeScore = $_POST['resultHome'][$fixture->fixtureId];
            $fixture->awayScore = $_POST['resultAway'][$fixture->fixtureId];
        } else {
            header('location: ' . URL . 'error/_403/');
            exit();
        }
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/confirmResult.php';
        require APP . 'view/_templates/footer.php';
    }

    public function confirmCancel() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $leagueFixtures = $leagueFixtureModel->getAllOpenFixtures();

        if (isset($_POST['fixId']) && isset($_POST['cancelGame']) && ($_POST['cancelGame'] == $_POST['fixId'])) {
            $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixId']);
        } else {
            header('location: ' . URL . 'error/_403/');
            exit();
        }
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/confirmCancellation.php';
        require APP . 'view/_templates/footer.php';
    }

    public function insertResult() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixtureId']);

        $fixture->homeScore = $_POST['homeScore'];
        $fixture->awayScore = $_POST['awayScore'];
        $fixture->calcLeaguePoints();


        if (!$fixture->saveResultFromAdminInput()) {
            foreach ($fixture->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
        }

        header('location: ' . URL . 'admin/addResult');
        exit();
    }

    public function cancelResult() {
        $leagueFixtureModel = $this->loadModel('LeagueFixtureModel');
        $fixture = $leagueFixtureModel->loadByFixtureId($_POST['fixtureId']);

        if (!$fixture->saveCancelMatchFromAdminInput()) {
            foreach ($fixture->errors['feedback'] as $key => $feeback) {
                $_SESSION['feedback_negative'][$key] = $feeback;
            }
        }

        header('location: ' . URL . 'admin/cancelGame');
        exit();
    }

    public function emails() {
        $userModel = new UserModel($this->db);
        $allCurrentActiveLeagueUserEmails = $userModel->getAllCurrentActiveLeagueUserEmails();
        $allUserEmails = $userModel->getAllUserEmails();
        $allUsersWaitingToJoinEmails = $userModel->getAllUserWaitingToJoinEmails();
        $allAdminUserEmails = $userModel->getAllAdminUserEmails();
        $allCommitteeUserEmails = $userModel->getAllCommitteeUserEmails();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/emails.php';
        require APP . 'view/_templates/footer.php';
    }

    
    public function removeSeason() {
        $seasonModel = $this->loadModel('SeasonModel');
        $allSeasons = $seasonModel->loadAllSeasons();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/removeSeason.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function removeSeasonConfirm() {
        $seasonModel = $this->loadModel('SeasonModel');

        if (isset($_POST['seasonId']) && isset($_POST['removeSeason'])) {
            $seasonId = $_POST['seasonId'];

            $seasonModel->load($seasonId);
            if ($seasonModel->removeSeason()) {
                $_SESSION['feedback_posative']['seasonremoved'] = "Season '" . $seasonModel->seasonName . "' was successfully removed";
            } else {
                $_SESSION['feedback_negative']['seasonremoved'] = "Season '" . $seasonModel->seasonName . "' was not removed, there was an unknown error, please contact support for further information. Frusrating eh? but really, we dont know why your season could not be removed.";
            }
        } else {
            header('location: ' . URL . 'error/_403/');
            exit();
        }

        $allSeasons = $seasonModel->loadAllSeasons();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/feedback.php';
        require APP . 'view/admin/removeSeason.php';
        require APP . 'view/_templates/footer.php';
    }

}
