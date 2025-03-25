<?php
    // Basically we will destroy all sessions and go back to the index. 
    session_start();
    session_destroy(); 
    header("Location: index.php"); 
    exit();
?>
