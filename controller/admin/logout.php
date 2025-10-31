<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to Admin Login Page
header("Location: /HM1/view/admin/admin_login.php?logout=success");
exit;
?>
