<?php
session_start();
/* initialisation des fichiers TWIG */
require_once '../lib/vendor/autoload.php';
require_once '../config/parametres.php';
require_once '../config/connexion.php';
require_once '../src/controller/_controller.php';
require_once '../src/model/_classes.php';
require_once '../config/routes.php';
$loader = new \Twig\Loader\FilesystemLoader('../src/view/');
$twig = $twig = new \Twig\Environment($loader, []);
$db = connect($config);
$content = getPage($db);
$content($twig, $db);
?>