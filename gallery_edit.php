<?php include "helperFunctions.php" ?>
<?php include "templates/header.php" ?>
<?php include "templates/navbar.php" ?>
<?php include "database.php" ?>


<?php

// Checking if it is post request

$id = $_GET['id'];
$sql = "SELECT * FROM gallery WHERE id='$id' LIMIT 1";

$result = $conn->query($sql);
$image = $result->fetch_assoc();

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // print_r($_SERVER["REQUEST_METHOD"]);

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
            $errors['image'] = 'Please upload a image file.';
        } elseif ($_FILES["image"]["size"] > 2097152) {
            // Check file size
            $errors['image'] = 'Sorry, your file is too large.';
        } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            // Check image types
            $errors['image'] = 'Sorry, only JPG, JPEG & PNG files are allowed.';
        } elseif ($imageWidth < 1000 && $imageHeight < 800) {
            $errors['image'] = 'Sorry, width should be greater than 1000 <br>and height should be greater than 800';
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $newTargetFile)) {
            $errors['image'] = 'Sorry, there was an error uploading your file.';
        }

        if (!array_key_exists('image', $errors)) {
            $imageUploaded = true;
        }
    }

    $description = sanitizeInput($_POST["description"]);

    // Validation for description
    if (empty($description)) {
        $errors['description'] = 'Decription field is required.';
    } elseif (strlen($description) < 15) {
        $errors['description'] = 'Decription field should be atleast 15 characters long.';
    }

    if (!count($errors)) {

        $timestamp = date("Y-m-d H:i:s");

        if (isset($imageUploaded) && $imageUploaded) {
            $sql = 'UPDATE gallery SET image_path = "' . $newTargetFile .'", description = "' . $description . '", updated_at = "' . $timestamp . '" WHERE id= '.$id .';';
        } else {
            $sql = 'UPDATE gallery SET description = "' . $description . '", updated_at = "' . $timestamp . '" WHERE id= '.$id .';';
        }

        if ($conn->query($sql) === TRUE) {
            if (file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }

            $_SESSION['success'] = 'Updated successful.';
            header('Location: ' . baseUrl('gallery.php'));
        } else {
            $errors['db_error'] = "Error: " . $sql . "<br> <br>" . $conn->error;
            // die('Insert erro');
        }
    }
}

?>

<div class="container">
    <div class="text-center">
        <h1>Edit Gallery</h1>
        <hr>
    </div>

    <div class="text-center mt-5">
        <h3>Upload Image</h3>
        <hr>
    </div>
    <?php
    if (array_key_exists('db_error', $errors)) {
        echo '<span class="error">' . $errors['db_error'] . '</span>';
    }
    ?>
    <form class="mt-5 mb-5" action="<?= baseUrl('gallery_edit.php') ?>?id=<?= $image['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <div class="row">
                <div class="col-6">
                    <label for="formFile" class="form-label">Upload a image</label>
                    <input class="form-control" type="file" name="image" id="formFile">
                    <?php
                    if (array_key_exists('image', $errors)) {
                        echo '<span class="error">' . $errors['image'] . '</span>';
                    }
                    ?>
                </div>
                <div class="col-6">
                    <h4>Old Image</h4>
                    <img src="<?= baseUrl() . $image['image_path'] ?>" class="img-fluid" alt="">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Enter Description</label>
            <textarea class="form-control" name="description" id="description" rows="3"><?= (isset($description) ? $description : $image['description']) ?></textarea>
            <?php
            if (array_key_exists('description', $errors)) {
                echo '<span class="error">' . $errors['description'] . '</span>';
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>

<?php include "templates/footer.php" ?>