<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

function is_admin(){
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_admin(){
    if(!is_admin()){
        $redirect = urlencode($_SERVER['REQUEST_URI']);
        header("Location: login.php?redirect=".$redirect);
        exit;
    }
}
