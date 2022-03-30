<?php
function userController($twig, $db){
    $form = array();
    $user = new User($db);
    $liste = $user->select();
    if(isset($_GET['id'])){
        $exec=$user->delete($_GET['id']);
        if(!$exec){
            $etat = false;
        }else{
            $etat = true;
        }
        header('Location: index.php?page=admin$etat='.$etat);
        exit;
    }
    if(isset($_GET['etat'])){
        $form['etat'] = $_GET['etat'];
    }
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        $etat=true;
        foreach($cocher as $id){
            $exec = $user->delete($id);
            if(!$exec){
                $etat = false;
            }
        }
        header('Location: index.php?page=admin$etat='.$etat);
        exit;    
    }
    
    echo $twig->render('admin.html.twig', array('form'=>$form,'liste'=>$liste));
} 

function userModifController($twig, $db){
    $form = array(); 
    if(isset($_GET['id'])){
        $utilisateur = new User($db);
        $unUtilisateur = $utilisateur->selectById($_GET['id']); 
        if ($unUtilisateur != null){
            $form['utilisateur'] = $unUtilisateur;
            $role = new Role($db);
            $liste = $role->select();
            $form['roles']=$liste;
        }else{
            $form['message'] = 'Utilisateur incorrect';
        }
    }else{
        if(isset($_POST['btModifier'])){
            $utilisateur = new User($db);
            $unUtilisateur = $utilisateur->selectById($_GET['id']);
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $role = $_POST['role'];
            $id = $_POST['id'];
            $inputPassword= $_POST['inputPassword'];
            $inputPassword2= $_POST['inputPassword2'];
            $inputEmail = $_POST['inputEmail'];
            $exec=$utilisateur->update($id, $role, $nom, $prenom);
            $form['valide'] = true;
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
            }
            if(!empty($inputPassword) && !empty($inputPassword2)){
                if($inputPassword!=$inputPassword2){
                    $form['valide'] = false;
                    $form['message'] = "Les mot de passe sont différents";
                }else{
                    $exec2=$utilisateur->updadeMdp($id, password_hash($inputPassword,PASSWORD_DEFAULT));
                    if(!$exec2){
                        $form['valide'] = false;
                        $form['message'] = "échec de la modification du mot de passe";
                    }else{
                        $form['valide'] = true;
                        $form['message'] = "Mot de passe changé !";
                    }

                }

            }
            if(!empty($inputEmail)){
                $exec3=$utilisateur->updateMail($id, $email);
                if(!$exec2){
                    $form['valide'] = false;
                    $form['message'] = "échec de la modification du mail";
                }else{
                    $form['valide'] = true;
                    $form['message'] = "Votre adresse mail à changé !";
                }
            }
        }else{
            $form['message'] = 'Utilisateur non précisé';
        }
    }
    echo $twig->render('user-modif.html.twig', array('form'=>$form));
}
