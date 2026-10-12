<?php
require_once 'funcoes.php';
iniciar_sessao();

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
