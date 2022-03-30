<?php
function getPage($db){
    $lesPages['home'] = "homeController;0";

    $lesPages['recherche'] ="rechercheController2";
    /* PAGE GENERAL DU SITE */
    $lesPages['contact'] = "contactController;0";
    $lesPages['about'] = "aboutController;0";
    $lesPages['mentions'] = "mentionsController;0";
    $lesPages['pageuser'] = "productUserController;0";
    $lesPages['maintenance'] = "maintenanceController;0";
    /*inscription, connect, disconect*/
    $lesPages['inscrire'] = "inscrireController;0";
    $lesPages['connecter'] = "connecterController;0";
    $lesPages['disconnect'] = "disconnectionController;2";

    /**** Administration des utilisateurs ****/
    $lesPages['admin'] = "userController;1";
    $lesPages['user-modif'] = "userModifController;1";

    /**** Gestion et affichage des produits et leurs type ****/
    $lesPages['ajout-type'] = "typeInsertController;1";
    $lesPages['admin-type'] = "typeController;1";
    $lesPages['modif-type'] = "typeModifController;1";
    $lesPages['delete-type'] = 'typeDeleteController;1';

    $lesPages['admin-produit'] = "productController;1";
    $lesPages['ajout-produit'] = "productInsertController;1";
    $lesPages['modif-produit'] = "productModifController;1";

    $lesPages['shop'] = "productUserController;0";
    $lesPages['vue-produit'] = "productViewController;0";

    $lesPages['panier'] = "panierController;0";

    if ($db!=NULL){
        if (isset($_GET['page'])){
            $page = $_GET['page'];  
        }else {
            $page = 'home'; 
            }
        if (!isset($lesPages[$page])){
            // On rentre ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la home
            $page = 'home';
        }
            //découpage de la ligne sur le caractère ";' $explose stock le resultat en tableau
        $explose = explode(";",$lesPages[$page]);

        $role = $explose[1];
        if($role != 0){
            if(isset($_SESSION['login'])){
                if(isset($_SESSION['role'])){
                    if($role != $_SESSION['role']){
                        $contenu = 'homeController';
                    }else{
                        $contenu = $explose[0]; // on récup le nom du controller car le role est bon
                    }
                }else{
                    $contenu = 'homeController';
                }
            }else {
                $contenu = 'homeController'; //il n'es pas authentifié
            }
        }else {
            $contenu = $explose[0]; // je récup le contrôleur, car il n'a pas besoin d'un rôle
        }
    }else {
        $contenu = $lesPages['maintenance'];
    }
    return $contenu;
}
?>