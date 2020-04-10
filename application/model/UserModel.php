<?php

class UserModel {

    public $userId;
    public $ldapUsername;
    public $email;
    public $firstName;
    public $lastName;
    public $nickname;
    public $admin;
    public $dateLastLoggedIn;
    public $errors = array();

    /**
     * @param object $db A PDO database connection
     */
    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }


    public function load($id) {
        if ($id != 0) {
            $sql = "SELECT userId, ldapUsername, firstName, lastName, nickname, admin, email, dateLastLoggedIn FROM user WHERE userId = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $user = $query->fetchAll();
            $this->loadObjVarsFromSingle($user[0]);
        } else {
            $this->userId = 0;
        }

        return $this;
    }

    private function loadObjVarsFromSingle($user) {
        foreach ($user as $var => $value) {
            $var = lcfirst($var);
            $this->$var = $value;
        }
    }

    public function saveUsers($newUser = false) {
        $returnVal = false;

        if ($this->validate()) {
           
            $sql = "INSERT INTO user (
            userId,
            ldapUsername,
            email,
            firstName,
            lastName,
            nickName,
            admin)
            VALUES (
            :userId,
            :ldapUsername,
            :email,
            :firstName,
            :lastName,
            :nickName,
            :admin
            ) 
            ON DUPLICATE KEY UPDATE
            userId = :userId, 
            ldapUsername = :ldapUsername, 
            email = :email, 
            firstName = :firstName,
            lastName = :lastName,
            nickName = :nickName,
            admin = :admin";
           
           
            /* $sql = "UPDATE user SET 
            ldapUsername = :ldapUsername, 
            email = :email, 
            firstName = :firstName,
            lastName = :lastName,
            nickName = :nickName,
            admin = :admin"; 
            
            if(!$newUser) {
                $sql .= " WHERE userId = :userId";
            } */

            $query = $this->db->prepare($sql);
            $sql_array = array(
                ':userId' => $this->userId, 
                ':ldapUsername' => $this->ldapUsername, 
                ':email' => $this->email, 
                ':firstName' => $this->firstName, 
                ':lastName' => $this->lastName,
                ':nickName' => $this->nickname,
                ':admin' => $this->admin);

                /*if(!$newUser) {
                   $sql_array[':userId'] = $this->userId;
                }*/
                //exit(print_r($query));
            if($query->execute($sql_array)) {
                $returnVal = true;
            }
        }
        return $returnVal;
    }


    private function validate() {
        $returnval = true;

        if (empty($this->ldapUsername) or $this->ldapUsername == '') {
            $this->errors['feedback']['username'] = 'The username name can not be empty';
        }
        if (empty($this->email) or $this->email == '') {
            $this->errors['feedback']['email'] = 'The email can not be empty';
        }
        if (empty($this->firstName) or $this->firstName == '') {
            $this->errors['feedback']['firstName'] = 'The first name short name can not be empty';
        }
        if (empty($this->lastName) or $this->lastName == '') {
            $this->errors['feedback']['lastName'] = 'The last name can not be empty';
        }
        if (empty($this->nickname) or $this->nickname == '') {
            $this->errors['feedback']['nickname'] = 'The nickname can not be empty';
        }
        if (empty($this->admin) or $this->admin == '') {
            $this->errors['feedback']['admin'] = 'The admin can not be empty';
        }
        if ($this->admin != 'y' and $this->admin != 'n') {
            $this->errors['feedback']['admin'] = 'The admin must be a value or "Y" or "N"';
        }

        if (count($this->errors) > 0) {
            $returnval = false;
        }
        return $returnval;
    }

    /**
     * Get all songs from database
     */
    public function getAllActiveUsers() {
        $sql = "SELECT userId, ldapUsername, firstName, lastName, nickname, admin, email FROM user";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
    
    /* all users whi are part of a league **/
    
    public function getAllCurrentActiveLeagueUserEmails() {
        
        $seasonModel = new SeasonModel($this->db);
        $seasonToView = $seasonModel->getCurrentSeason();

        if (empty($seasonToView)) {
            $seasonToView = $seasonModel->getPreviousSeason();
        }
        
        echo $sql = "SELECT email FROM user U "
                . "JOIN teamUser TU on TU.userId = U.userId "
                . "JOIN team T ON T.teamId = TU.teamId "
                . "JOIN fixture F1 ON F1.homeTeamId = T.teamId "
                . "JOIN fixture F2 ON F2.awayTeamId = T.teamId "
                . "JOIN leagueFixture LF ON LF.fixtureId = F1.fixtureId "
                . "WHERE LF.seasonID = " . $seasonToView . " "
                . "GROUP BY U.email";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
    
    public function getAllUserEmails() {
        $sql = "SELECT email FROM user";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
    
    public function getAllAdminUserEmails() {
        $sql = "SELECT email FROM user WHERE admin = 1";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
    
    public function getAllUserWaitingToJoinEmails() {
        $sql = "SELECT email FROM user WHERE waitingToJoin = 1";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
    
    public function getAllCommitteeUserEmails() {
        $sql = "SELECT email FROM user WHERE committee = 1";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
    
    

    public function getAllUsersWaitingToJoin() {
        
        $sql = "SELECT userId, ldapUsername, email, firstName, nickname, admin FROM user WHERE waitingToJoin = 1";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }


    public function is_admin() {
        if ($this->admin == 'y') {
            return true;
        } else {
            return false;
        }
    }

    public function update_user_logged_in($userId) {
        $sql = "UPDATE user SET dateLastLoggedIn = NOW() WHERE userId = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $userId));
    }

    public function login_user($ldap = 1) {

        $returnval = false;



        if ($ldap == 1) {
            $returnval = $this->loginUserWithLdap();
        } else {
            $returnval = $this->loginUserWithOutLdap();
        }

        return $returnval;
    }

    private function loginUserWithLdap() {
        try {
            $adldap = new adLDAP ();
        } catch (adLDAPException $e) {
            // die($e);
            echo $this->auth_failure($e);
        }

        if ($adldap->authenticate($this->ldapUsername, $this->password)) {
            $this->intUserID = mysql_result($result, 0, 0);
            $this->hash = $this->intUserID . "-" . date("U");

            // once ldap logged in, we use the non ldap loggin to match up the 
            // fudssball users table
            return $this->loginUserWithOutLdap();
        } else {
            $this->authFailure();
        }
    }

    private function loginUserWithOutLdap() {
        $returnVal = false;

        $sql = "SELECT userId FROM user WHERE ldapUsername = :username";
        $query = $this->db->prepare($sql);
        $query->execute(array(':username' => $this->ldapUsername));
        $res = $query->fetchAll();

        if ($query->rowCount() == 1) {
            $this->update_user_logged_in($res[0]->userId);
            Session::set('userId', $res[0]->userId);
            $returnVal = true;
        } else {
            
        }
        return $returnVal;
    }

    private function authFailure() {
        header('Location: ' . URL . 'login.php');
        exit();
    }

    public function logout() {
        Session::destroy();
        $_SESSION['feedback_negative']['loggedout'] = "You have logged out";
        header('location: ' . URL . 'login/');
        exit();
    }


    

}
