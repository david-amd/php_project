<?php
    // Aici incepe sesiunea
    session_start();

    // Aici se opreste sesiunea
    session_destroy();

    // Face redirect catre pagina de login
    header("Location: index.php?form=login");
    exit();
?>
