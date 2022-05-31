<?php
function productUserController($twig, $db){
    $form = array();
    $product = new Product($db);
    $liste = $product->select();
    
    if(isset($_POST['btAjoutP'])){
        if(isset($_POST['id'])){
            $form['valideAjout']=true;
            $unProduit = $product->selectById($_POST['id']);
            if(!$unProduit){
                $form['valideAjout']=false;
                $form['message'] = "Le produit n'existe pas";
            }else{
                if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
                    if (array_key_exists($unProduit['id'], $_SESSION['panier'])) {
                        // Le produit existe dans le panier, il suffit de mettre à jour la quantité.
                        $_SESSION['panier'][$unProduit['id']] ++;
                    } else {
                        // Le produit n'est pas dans le panier, ajoutez-le
                        $_SESSION['panier'][$unProduit['id']] = 1;
                    }
                } else {
                    //Il n'y a aucun produit dans le panier, ceci ajoutera le premier produit au panier.
                    $_SESSION['panier'] = array($unProduit['id'] => 1);
                }
                $form['message'] = "Le produit a bien été ajouté";
            }
        }else{
            $form['valideAjout']=false;
            $form['message'] = "Vous n'avez pas sélectionner de produit";
        }
    }

    $limite=4;
    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }else{
        $nopage = $_GET['nopage'];
        $inf = $nopage * $limite;
    } 
    $r = $product->selectCount();
    $nb = $r['nb'];

    $liste = $product->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;

   
    echo $twig->render('produituser.html.twig', array('form'=>$form,'produit'=>$liste));
}

function productController($twig, $db){
    $form = array();
    $product = new Product($db);
    $liste = $product->select();
    if(isset($_GET['id'])){
        $exec=$product->delete($_GET['id']);
        if (!$exec){
            $etat = false;
        }else{
            $etat = true;
        }
        header('Location: index.php?page=product&etat='.$etat);
        exit;
    }
    if(isset($_GET['etat'])){
        $form['etat'] = $_GET['etat'];
    }
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        $etat=true;
        foreach ($cocher as $id){
            $exec=$product->delete($id);
            if (!$exec){
                $etat = false;
            }
        }
        header('Location: index.php?page=product&etat='.$etat);
        exit;
    }
    $limite=3;
    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }else{
        $nopage = $_GET['nopage'];
        $inf = $nopage * $limite;
    } 
    $r = $product->selectCount();
    $nb = $r['nb'];

    $liste = $product->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;

   
    echo $twig->render('produit.html.twig', array('form'=>$form,'produit'=>$liste));
}

function productInsertController($twig, $db){
    $form = array();
    $type = new Type($db);
    $listeT = $type->select();
    if (isset($_POST['btInserProduct'])){
        $photo = NULL;
        $idType = $_POST['type'];
        $designation = $_POST['inputDesignation'];
        $description = $_POST['inputDescription'];
        $prix =$_POST['inputPrix'];
        $form['valide'] = true;

        $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg'), 'images', 500000, $designation);
        $photo = $upload->enregistrer('photo');

        $produit = new Product($db);
        $exec = $produit->insert($designation, $description, $prix, $photo['nom'], $idType);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème dans l\'ajout du produit';
        }else{
            $form['valide'] = true;
        }
    }
    echo $twig->render('ajoutProduit.html.twig', array('form'=>$form, 'listeT'=>$listeT));
}

function productModifController($twig, $db){
    $form = array(); 
    if(isset($_GET['id'])){
        $produit = new Product($db);
        $unProduit = $produit->selectById($_GET['id']);
        $photo = $unProduit['photo'];
        if ($unProduit != null){
            $form['produit'] = $unProduit;
            $type = new Type($db);
            $liste = $type->select();
            $form['types']=$liste;
        }else{
            $form['message'] = 'produit incorrect';
        }
    }else{
        if(isset($_POST['btModifier'])){
            $produit = new Product($db);
            $unProduit = $produit->selectById($_GET['id']);
            $designation = $_POST['inputDesignation'];
            $description = $_POST['inputDescription'];
            $prix = $_POST['inputPrix'];
            $maphoto = $unProduit['photo'];
            $id = $_POST['id'];
            $idType= $_POST['type'];
            $form['valide'] = true;
            
            $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg'), 'images', 500000, $designation);
            if($maphoto['nom']==NULL){
                $maphoto = $upload->enregistrer('photo');
            }
            
            $exec=$produit->update($id, $designation, $description, $prix, $maphoto['nom'], $idType);

            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
            }
        }else{
            $form['message'] = 'porduit non précisé';
        }
    }
    echo $twig->render('produit-modif.html.twig', array('form'=>$form));
}

function productViewController($twig, $db){
    $form = array(); 
    if(isset($_GET['id'])){
        $produit = new Product($db);
        $unProduit = $produit->selectById($_GET['id']);
        $photo = $unProduit['photo'];
        if ($unProduit != null){
            $form['produit'] = $unProduit;
            $type = new Type($db);
            $liste = $type->select();
            $form['types']=$liste;
        }else{
            $form['message'] = 'produit incorrect';
        }
        if(isset($_POST['btAjoutP'])){
            if(isset($_POST['id'])){
                $form['valideAjout']=true;
                $unProduit = $produit->selectById($_POST['id']);
                if(!$unProduit){
                    $form['valideAjout']=false;
                    $form['message'] = "Le produit n'existe pas";
                }else{
                    if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
                        if (array_key_exists($unProduit['id'], $_SESSION['panier'])) {
                            // Le produit existe dans le panier, il suffit de mettre à jour la quantité.
                            $_SESSION['panier'][$unProduit['id']] ++;
                        } else {
                            // Le produit n'est pas dans le panier, ajoutez-le
                            $_SESSION['panier'][$unProduit['id']] = 1;
                        }
                    } else {
                        //Il n'y a aucun produit dans le panier, ceci ajoutera le premier produit au panier.
                        $_SESSION['panier'] = array($unProduit['id'] => 1);
                    }
                    $form['message'] = "Le produit a bien été ajouté";
                }
            }else{
                $form['valideAjout']=false;
                $form['message'] = "Vous n'avez pas sélectionner de produit";
            }
        }
    }
    echo $twig->render('produitVue.html.twig', array('form'=>$form));
}