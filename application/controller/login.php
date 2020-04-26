<?php
class Login extends Controller
{
    public function index()
    {
		$loadLoginView = (LDAP_ACTIVE) ? 'index' : 'index_local';
    	
    	$pageTitle = "Login";
    	if(isset($_POST['ldapUsername']) && isset($_POST['password'])) {
			
			$userModel = new UserModel($this->db);
    		
    		$userModel->ldapUsername = $_POST['ldapUsername'];
    		$userModel->password = $_POST['password'];
    			
    		if($userModel->login_user(LDAP_ACTIVE)) {
					
				Session::set('user_logged_in', 'true');
    			header('location: ' . URL);
        		exit();	
    		}else{
    			$_SESSION['feedback_negative']['login_denied'] = "Your login details are not correct.";
    			require APP .'view/_templates/feedback.php';
				require APP .'view/login/'.$loadLoginView.'.php';
    		}
    	} else {
			require APP . 'view/_templates/feedback.php';
        	require APP . 'view/login/'.$loadLoginView.'.php';
    	}
    	
    	
    }
    
    
    
}
