<?php
session_start();            // Starts the current session or resumes an existing one.
session_unset();            // Removes all session variables, clearing stored data.
session_destroy();          // Completely destroys the session, removing data from the server.

header("Location: login.php"); // Redirects the user to the login page.
exit();                     // Stops script execution to ensure the redirection.
?>
