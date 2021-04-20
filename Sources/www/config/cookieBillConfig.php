<?php

    setcookie("idOrder", $_GET['idOrder'], time() + 3600, "/");

    header('Location: ./../en/payment');

?>
