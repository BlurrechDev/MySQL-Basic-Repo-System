<?php
ob_start(); // Turns on output buffering to enable header redirection.
session_start(); // Resumes the login session, to ensure that it is destroyed properly.
session_destroy(); // Destroys the login session.
header('Location: ../'); // Adjusts the header mode allowing PHP code triggered navigations/redirects to occur below.
?>
