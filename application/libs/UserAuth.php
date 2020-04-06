<?php

/**
 * Class Auth
 * Simply checks if user is logged in. In the app, several controllers use Auth::handleLogin() to
 * check if user if user is logged in, useful to show controllers/methods only to logged-in users.
 */
class UserAuth
{
    public static function handleLogin()
    {
        // initialize the session
        //Session::init();

        
        UserAuth::checkSessionStillAlive();
        
        // if user is still not logged in, then destroy session, handle user as "not logged in" and
        // redirect user to login page
        if (!isset($_SESSION['user_logged_in'])) {
            Session::destroy();
            header('location: ' . URL . 'login');
        } else {
        	$controller = new Controller();
        }
    }
    
    /**
     * checkSessionStillAlive()
     * When a user makes a page request, they are given a last_active session
     * timestamp. Hre we check if the time now is within X minutes since the last
     * request. If no activity within X minutes, then we send them to the login
     */
    public static function checkSessionStillAlive() {
    	
        
    	if (isset($_SESSION['LAST_ACTIVITY']) && 
    		(time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT_IN_SECONDS)) {
		    // last request was more than X minutes ago
		    session_unset();     // unset $_SESSION variable for the run-time 
		    session_destroy();   // destroy session data in storage
		    
		    header('location: ' . URL);
		}
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    }
    
    /**
     * adminProtectedPage()
     * Check that the user has admin privalaedges to access the page. 
     * To protect a page, simply add 
     * UserAuth::adminProtectedPage() a the base of the controller method
     */
    public static function adminProtectedPage() {
    	if(isset($_SESSION['user_logged_in']))
    	{
    		global $user;
    		if(!$user->is_admin())
    		{
    			#TODO
    			//There needs to be an log which records events. Such as
    			//a user trying to access admin areas.
    			//log table should have id, event, user, severity_level, ip
    			header('location: ' . URL . 'error/_403'); exit();
    		}
    	}else {
    		header('location: ' . URL . 'error/_403'); exit();
    	}
    }
}
