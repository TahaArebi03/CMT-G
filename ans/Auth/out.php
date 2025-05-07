<?php
class SessionManager {
    public function logoutAndRedirect($redirectTo = 'inout.php') {
        session_start();
        session_unset();
        session_destroy();
        header("Location: $redirectTo");
        exit;
    }
}

$session = new SessionManager();
$session->logoutAndRedirect();
?>
