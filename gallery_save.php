<?php include "helperFunctions.php" ?>
<?php include "templates/header.php" ?>
<?php include "templates/navbar.php" ?>
<?php include "database.php" ?>


<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // print_r($_SERVER["REQUEST_METHOD"]);

    $id = $_POST['id'];
    $sql = "SELECT * FROM gallery WHERE id='$id' LIMIT 1";

    $result = $conn->query($sql);
    $image = $result->fetch_assoc();

    if (file_exists($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['image']['tmp_name'])) {

        $targetDirectory = "uploads/";
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $newTargetFile = $targetDirectory . round(microtime(true)) . '.' . $imageFileType;

        // CHeck image or not
        $check = getimagesize($_FILES["image"]["tmp_name"]);

        $imageWidth = $check[0];
        $imageHeight = $check[1];

        // dd(strpos($check['mime'], 'test'));
        if (strpos($check['mime'], 'image') !== 0) {
            $_SESSION['image_error'] = 'Please upload a image file.';
        } elseif ($_FILES["image"]["size"] > 2097152) {
            // Check file size
            $_SESSION['image_error'] = 'Sorry, your file is too large.';
        } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            // Check image types
            $_SESSION['image_error'] = 'Sorry, only JPG, JPEG & PNG files are allowed.';
        } elseif ($imageWidth < 1000 && $imageHeight < 800) {
            $_SESSION['image_error'] = 'Sorry, width should be greater than 1000 <br>and height should be greater than 800';
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $newTargetFile)) {
            $_SESSION['image_error'] = 'Sorry, there was an error uploading your file.';
        }

        if (!array_key_exists('image', $errors)) {
            $imageUploaded = true;
        }
    }

    $description = sanitizeInput($_POST["description"]);

    // Validation for description
    if (empty($description)) {
        $_SESSION['description_error'] = 'Decription field is required.';
    } elseif (strlen($description) < 15) {
        $_SESSION['description_error'] = 'Decription field should be atleast 15 characters long.';
    }

    if (!isset($_SESSION['description_error']) && !isset($_SESSION['image_error'])) {

        $timestamp = date("Y-m-d H:i:s");

        if (isset($imageUploaded) && $imageUploaded) {
            $sql = 'UPDATE gallery SET image_path = "' . $newTargetFile .'", description = "' . $description . '", updated_at = "' . $timestamp . '" WHERE id= '.$id .';';
        } else {
            $sql = 'UPDATE gallery SET description = "' . $description . '", updated_at = "' . $timestamp . '" WHERE id= '.$id .';';
        }

        if ($conn->query($sql) === TRUE) {

            if ($imageUploaded) {
                if (file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                }
            }

            $_SESSION['success'] = 'Update successful.';
            header('Location: ' . baseUrl('gallery.php'));
        } else {
            $_SESSION['db_error'] = "Error: " . $sql . "<br> <br>" . $conn->error;
            // die('Insert erro');
            header('Location: ' . baseUrl('gallery_edit.php?id=' . $id));
        }
    } else {
        header('Location: ' . baseUrl('gallery_edit.php?id=' . $id));
    }
}


?>