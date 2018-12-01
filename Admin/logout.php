<?php
    session_start();
    unset($_SESSION['adminusername']);
    session_abort();
    header("Location: index.php");
?>