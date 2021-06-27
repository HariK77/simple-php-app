<?php include "helperFunctions.php" ?>
<?php include "templates/header.php" ?>
<?php include "templates/navbar.php" ?>
<?php include "database.php" ?>


<?php

$id = $_GET['id'];
$sql = "SELECT * FROM gallery WHERE id='$id' LIMIT 1";

$result = $conn->query($sql);
$image = $result->fetch_assoc();

// sql to delete a record
$sql = "DELETE FROM gallery WHERE id=$id";

if ($conn->query($sql) === TRUE) {

    if (file_exists($image['image_path'])) {
        unlink($image['image_path']);
    }

    $_SESSION['success'] = 'Image and Description deleted Successfully';

} else {
    $_SESSION['error'] = "Error deleting record: <br>" . $conn->error;
}


header('Location: ' . baseUrl('gallery.php'));

?>