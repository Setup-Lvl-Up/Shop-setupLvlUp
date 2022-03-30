<?php
function typeController($twig, $db){
    $form = array();
    $type = new Type($db);
    $liste = $type->select();
    echo $twig->render('type.html.twig', array('form'=>$form,'type'=>$liste));
}

function typeInsertController($twig, $db){
    $form = array();
    if (isset($_POST['btInserType'])){
        $libelle = $_POST['libelle'];
        $form['valide'] = true;
        $type = new Type($db);
        $exec = $type->insert($libelle);
        
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème dans l\'ajout';
        }
        
    }
    echo $twig->render('ajoutType.html.twig', array('form'=>$form));
}

function typeModifController($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
        $type = new Type($db);
        $unType = $type->selectById($_GET['id']);
        if ($unType != null){
            $form['type'] = $unType;
        }
        else{
            $form['message'] = 'type incorrect';
        }
    }else{
        if(isset($_POST['btModifier'])){
            $type = new Type($db);
            $libelle = $_POST['libelle'];
            $id = $_POST['id'];

            $exec=$type->update($id, $libelle);
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
            }
        }else
        {
            $form['message'] = 'type non précisé';
        }
    }
    echo $twig->render('type-modif.html.twig', array('form'=>$form));
}

function typeDeleteController($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
        $type = new Type($db);
        $libelle = $_POST['libelle'];
        $id = $_POST['id'];

        $delete=$type->delete($id, $libelle);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Echec de la suppression';
        }else{
            $form['valide'] = true;
            $form['message'] = 'vous avez supprimer un type';
        }
    }
    echo $twig->reder('type.html.twig', array('form'=>$form));
}