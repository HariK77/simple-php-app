<?php include "helperFunctions.php" ?>
<?php include "templates/header.php" ?>
<?php include "templates/navbar.php" ?>
<?php include "database.php" ?>

<?php

// Checking if it is post request

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    $description = sanitizeInput($_POST["description"]);

    // Validation for description
    if (empty($description)) {
        $errors['description'] = 'Decription field is required.';
    } elseif (strlen($description) < 15) {
        $errors['description'] = 'Decription field should be atleast 15 characters long.';
    }

    if (!count($errors)) {

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $newTargetFile)) {
            $timestamp = date("Y-m-d H:i:s");

            $sql = "INSERT INTO gallery (image_path, description, created_at, updated_at) VALUES ('$newTargetFile', '$description', '$timestamp', '$timestamp');";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = 'Upload successful.';
                header('Location: ' . baseUrl('gallery.php'));
            } else {
                $errors['db_error'] = "Error: " . $sql . "<br>" . $conn->error;
                // die('Insert erro');
            }
        } else {
            $errors['image'] = 'Sorry, there was an error uploading your file.';
        }
    }
}

$sql = "SELECT * FROM gallery;";

$images = $conn->query($sql);

$conn->close();

?>

<div class="container">
    <div class="text-center">
        <h1>Gallery</h1>
        <hr>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php while ($image = mysqli_fetch_object($images)) { ?>
            <div class="col">
                <div class="card shadow-sm">
                    <img class="bd-placeholder-img card-img-top" src="<?= baseUrl() . $image->image_path ?>" width="100%" height="225"></img>
                    <div class="card-body">
                        <p class="card-text"><?= $image->description ?>.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="<?= baseUrl() . $image->image_path ?>" target="_blank"><button type="button" class="btn btn-sm btn-outline-success">View</button></a>
                                <a href="<?= baseUrl('gallery_edit.php') . '?id=' . $image->id; ?>"><button type="button" class="btn btn-sm btn-outline-primary">Edit</button></a>
                                <a href="<?= baseUrl('gallery_delete.php') . '?id=' . $image->id; ?>" onclick="confirm('Are you sure ?')"><button type="button" class="btn btn-sm btn-outline-danger">Delete</button></a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if (isset($_SESSION['is_logged']) && $_SESSION['is_logged']) : ?>
        <div class="text-center mt-5">
            <h3>Upload Image</h3>
            <hr>
        </div>
        <?php
        if (array_key_exists('db_error', $errors)) {
            echo '<span class="error">' . $errors['db_error'] . '</span>';
        }
        ?>
        <form class="mt-5 mb-5" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="formFile" class="form-label">Upload a image</label>
                <input class="form-control" type="file" name="image" id="formFile">
                <?php
                if (array_key_exists('image', $errors)) {
                    echo '<span class="error">' . $errors['image'] . '</span>';
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Enter Description</label>
                <textarea class="form-control" name="description" id="description" rows="3"><?= (isset($description) ? $description : '') ?></textarea>
                <?php
                if (array_key_exists('description', $errors)) {
                    echo '<span class="error">' . $errors['description'] . '</span>';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    <?php endif ?>
</div>

<?php include "templates/footer.php" ?>