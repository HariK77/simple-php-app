<?php
include "../helperFunctions.php";

session_unset();
session_destroy();

session_start();

$_SESSION['success'] = 'Logged out successfully';

header('Location: '. baseUrl());

?>