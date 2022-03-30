<?php
function connectController($twig){
    $form = array();
    if (isset($_POST['btConnect'])){
        $form['valide'] = true;
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];
        $utilisateur = new User($db);
        $unUtilisateur = $utilisateur->connect($inputEmail);
        if($unUtilisateur != null){
            if(!password_verify($inputPassword, $unUtilisateur['mdp'])){
                $form['valide'] = false;
                $form['message'] = 'Login ou mot de passe erroné';
            }
            else{
                $_SESSION['login'] = $inputEmail;
                $_SESSION['role'] = $unUtilisateur['idRole']; 
                header("Location:/index.php");
            }
        }
        else{
            $form['valide'] = false;
            $form['message'] = "Login ou mot de passe erroné";
        }
    }
    echo $twig->render('connect.html.twig', array('form'=>$form));
}


?>