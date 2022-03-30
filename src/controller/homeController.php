<?php
function homeController($twig, $db){
    $form = array();
    $product = new Product($db);
    $liste = $product->select();

    echo $twig->render('home.html.twig', array('form'=>$form, 'liste'=>$liste));
}
function aboutController($twig){
    echo 'À propos de nous';
}
function mentionsController($twig){
    echo $twig->render('mention-legal.html.twig', array());
}
function maintenanceController($twig){
    echo $twig->render('maintenance.html.twig', array());
}
function rechercheController($twig, $db){
    $form = array();
    //if(isset($_POST))
    echo $twig->render('recherche.html.twig', array('form'=>$form));
}
?>