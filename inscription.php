<?php
require_once 'inc/header.php';

if (isset($_SESSION['utilisateur'])){

    header('location:./');
    exit();

}

if (!empty($_POST)){
    $erreur="";
    // verification si un utilisateur a déjà un compte à cette adresse mail
    $verif=executeRequete("SELECT * FROM utilisateur WHERE email=:email", array(
        ':email'=>$_POST['email']
    ));
    $resultat=$verif->fetch(PDO::FETCH_ASSOC);

    if ($resultat){ // si il y a un retour de la requête on stop l'inscription
        $_SESSION['messages']['danger'][]='Un compte existe déjà à cette adresse mail, veuillez procéder à une demande de réinitialisation de mot de passe';
        header('location:./inscription.php');
        exit();

    }else{

        if ($_POST['mdp'] !== $_POST['confirmPassword']){

            $erreur.='Les mots de passe ne correspondent pas';
        }

        if (empty($erreur)){
            $mdp=password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            //die(var_dump($_POST));
            //  var_dump($_POST, $mdp);

            $requete=executeRequete("INSERT INTO utilisateur ( mdp, pseudo, role, email) VALUES ( :m , :p , :r, :e )", array(

                ':m'=>$mdp,
                ':p'=>$_POST['pseudo'],
                ':r'=>'ROLE_USER',
                ':e'=>$_POST['email']
            ));

            $_SESSION['messages']['info'][]='Merci pour votre inscription, connectez vous à présent &#128522';
            header('location:./connexion.php');
            exit();
        }



    }




}




?>


<form method="post" action="">

    <section class="vh-100 bg-image" style="background-color: #C7C8C9;">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Inscription</h2>

                                <label for="inputEmail">Email</label>

                                <input type="text" name="email" id="inputEmail"
                                       class="form-control" autocomplete="email">
                                <label for="inputPassword" class="mt-3">Mot de passe</label>
                                <input type="password" name="mdp" id="inputPassword" class="form-control"
                                       autocomplete="current-password">

                                <small class="text-danger"><?=  $erreur ?? ''; ?></small>
                                <label for="inputPassword" class="mt-3">Confirmation de mot de passe</label>
                                <input type="password" name="confirmPassword" id="inputPassword" class="form-control"
                                       autocomplete="current-password">
                                <small class="text-danger"><?=  $erreur ?? ''; ?></small>

                                <label for="inputPassword" class="mt-3">Pseudo</label>
                                <input type="text" name="pseudo" id="inputPassword" class="form-control"
                                       autocomplete="current-password">


                                <button class="btn mb-2 mt-3 mb-md-0 btn-outline-secondary btn-block" type="submit">
                                    Valider
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</form>


<?php require_once 'inc/footer.php' ?>
