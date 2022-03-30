<?php
function registerController($twig, $db){
    $form = array();
    if (isset($_POST['btRegister'])){
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];
        $inputPassword2 =$_POST['inputPassword2'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $role = $_POST['role'];
        $form['valide'] = true;
        if ($inputPassword != $inputPassword2){
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        }
        else{
            $utilisateur = new User($db);
            $exec = $utilisateur->insert($inputEmail, password_hash($inputPassword, PASSWORD_DEFAULT), $role, $nom, $prenom);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
            }
        }
        $form['email'] = $inputEmail;
        $form['role'] = $role;
    }
    echo $twig->render('register.html.twig', array('form'=>$form));

}
