<?php
session_start();
ob_start();
unset($_SESSION['id'],  $_SESSION['nome'], $_SESSION['acesso']);
header("Location: ../index.php");