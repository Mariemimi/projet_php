<?php require_once 'inc/header.php';


if (!isset($_SESSION['utilisateur']) || (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role']!=='ROLE_ADMIN')){
    header('location:./');
    exit();

}
if (!empty($_POST)){

    if (!empty($_FILES['photoModif']['name'])){

        $photo_bdd= date_format(new DateTime(), 'YmdHis').'-'.uniqid().$_FILES['photoModif']['name'];



        copy($_FILES['photoModif']['tmp_name'], 'upload/'.$photo_bdd );
        unlink('upload/'.$_POST['photo']);


    }else{

        $photo_bdd=$_POST['photo'];

    }// fin de condition !empty($_FILES)
    $requete=executeRequete("UPDATE produit SET prix=:prix, titre=:titre, description=:description, photo=:photo WHERE id=:id", array(
        ':prix'=>$_POST['prix'],
        ':titre'=>$_POST['titre'],
        ':description'=>$_POST['description'],
        ':photo'=>$photo_bdd,
        ':id'=>$_POST['id']

    ) );

    $_SESSION['messages']['success'][]='produit modifié avec succès';


}// fin de condition de soumission du formulaire


if (isset($_GET['id'])){   // on vérifie si il existe un paramètre en get passé dans l'url nommé 'id'
    // un passage en get est défini par soit par un formulaire dont l'action ne serait soit non renseignée, soit défini en get. Sinon on l'utilise pour faire transiter des informations d'une page à une autre via l'url par le biais d'un liens dans lequel on va déclaré notre passage en get avec ? .
    // ainsi l'url aura la forme: https://www.nomdedomaine.fr/gestionProduit?id=2&action=modifier
    // dans cette exemple dans la page gestionProduit.php on aurait de chargé dans $_GET 2 entrée une $_GET['id'] qui aurait pour valeur 2 et la seconde $_GET['action'] qui aurait pour valeur modifier
    $r=executeRequete("SELECT * FROM produit WHERE id=:id", array(
        ':id'=>$_GET['id']
    ));
    $produit=$r->fetch(PDO::FETCH_ASSOC);

    var_dump($produit);


}











?>

<form action="" method="post"  enctype="multipart/form-data">
    <fieldset>

        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">TITRE</label>
            <!-- $produit['titre'] ?? ''    est utilisable depuis PHP7, il signifie , si $produit['titre'] n'existe pas, affiche moi comme valeur par defaut '' (du vide)    -->
            <input name="titre" value="<?=  $produit['titre'] ?? ''; ?>" type="text" class="form-control" id="exampleInputPassword1" placeholder="Titre">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">PRIX</label>
            <input name="prix" value="<?=  $produit['prix'] ?? ''; ?>"  type="number" class="form-control" id="exampleInputPassword1" placeholder="Prix">
        </div>


        <div class="form-group">
            <label for="exampleTextarea" class="form-label mt-4">DESCRIPTION</label>
            <textarea name="description" class="form-control" id="exampleTextarea" rows="3"><?=  $produit['description'] ?? ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="formFile" class="form-label mt-4">PHOTO</label>
            <input name="photoModif" class="form-control" type="file" id="formFile">
            <img src="<?=  'upload/'.$produit['photo']; ?>" width="250" alt="">
        </div>

        <input type="hidden" name="id" value="<?=  $produit['id'] ?? 0; ?>">
        <input type="hidden"  name="photo" value="<?=  $produit['photo']; ?>">
        <button type="submit" class="btn btn-primary">VALIDER</button>
    </fieldset>
</form>



<?php require_once 'inc/footer.php'; ?>
