<?php
session_start();
if (isset($_SESSION['nombres'])) {
    session_destroy();
}
header('Location: ../index.php');

