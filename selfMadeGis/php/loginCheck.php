   <?php
   //session_start(); //session_start should be in an application wide global file

   //this code should only be in pages where you want to have login enabled
    if(!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
       header("location: ../index.php?msg=$msg");
       exit();
    }
   	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 2*3600)) {
	    // last request was more than 30 minutes ago
	    session_unset();     // unset $_SESSION variable for the run-time 
	    session_destroy();   // destroy session data in storage
	    $msg = 'please relogin';
	    header("location: ../index.php?msg=$msg");
	    exit();
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
	} else if (time() - $_SESSION['CREATED'] > 1800) {
	    // session started more than 30 minutes ago
	    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
	    $_SESSION['CREATED'] = time();  // update creation time
	}
   ?>