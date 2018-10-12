<?php
/**
 * Created by PhpStorm.
 * @version 7.3
 * User: wilder7
 * Date: 11/10/18
 * Time: 10:14
 */

$extension = ['png','jpg','gif'];

$FileInfo = new FilesystemIterator('images/');

if ($_FILES) {
    for ($i = 0; $i < count($_FILES['fichier']['name']); $i++) {
        $length = filesize($_FILES['fichier']['tmp_name'][$i]);
        $ext = explode('.', $_FILES['fichier']['name'][$i]);
        if ($length > 1048576) {
            throw new LogicException('Vôtre fichier ne peut exceder 1Mo');
        } elseif (!in_array($ext[1], $extension)) {
            throw new LogicException('Votre fichier doit être soit un .png, soit un .jpg, soit un .gif');
        } else {
            $rename = pathinfo($_FILES['fichier']['name'][$i], PATHINFO_EXTENSION);
            $_FILES['fichier']['name'][$i] = 'image'.uniqid() . '.' .$rename;
            $uploadDir = 'images/';
            $uploadFile = $uploadDir . basename($_FILES['fichier']['name'][$i]);
            move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $uploadFile);
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="fichier[]" multiple />
        <input type="submit" value="Send" />
    </form>
    <div class="row justify-content-around">
            <?php
                foreach ($FileInfo as $file) { ?>
                    <div class="card col-3">
                        <img src="images/<?php echo $file->getFilename() ?>" class="img-thumbnail">
                        <?php echo $file->getFilename() ?>
                        <form method="POST">
                            <input type="hidden" value="<?php echo $file->getFilename() ?>" name="delete"/>
                            <input type="submit" value="Delete" name="del"/>
                        </form>
                    </div>
                    <?php
                    if (!empty($_POST['delete'])) {
                        if ($_POST['delete'] == $file->getFilename()) {
                            if (file_exists('images/'.$file->getFilename()) === true) {
                                unlink('images/' . $file->getFilename());
                                header('location:index.php');
                            }
                        }
                    }
                }
                ?>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

