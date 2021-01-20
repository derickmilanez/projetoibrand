<?php
    session_start();
    $senha = $_POST['senha'];
    if(empty($senha)){
        header("Location:verification.php");
    }else{
        $_SESSION['senha'] = $senha;
        header("Location:admin-map.php");
    }
?>