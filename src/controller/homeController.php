<?php
function homeController($twig, $db){
    $inf=0;
    $limite =3;
    $product = new Product($db);
    $liste = $product->selectLimit($inf, $limite);
    $produit = $product->lastProduct();

    echo $twig->render('home.html.twig', array('produit'=>$produit, 'liste'=>$liste));
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