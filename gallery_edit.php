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
    <?php if (isset($_SESSION['db_error']) && !empty($_SESSION['db_error'])) {
        echo '<span class="error">' . $_SESSION['db_error'] . '</span>';
        unset($_SESSION['db_error']);
    } ?>
    <form class="mt-5 mb-5" action="<?= baseUrl('gallery_save.php') ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <div class="row">
                <div class="col-6">
                    <label for="formFile" class="form-label">Upload a image</label>
                    <input class="form-control" type="file" name="image" id="formFile">
                    <?php if (isset($_SESSION['image_error']) && !empty($_SESSION['image_error'])) {
                        echo '<span class="error">' . $_SESSION['image_error'] . '</span>';
                        unset($_SESSION['image_error']);
                    } ?>
                </div>
                <div class="col-6">
                    <h4>Old Image</h4>
                    <img src="<?= baseUrl() . $image['image_path'] ?>" class="img-fluid" alt="">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Enter Description</label>
            <textarea class="form-control" name="description" id="description" rows="3"><?= (isset($_SESSION['description_error']) ? '' : $image['description']) ?></textarea>
            <?php if (isset($_SESSION['description_error']) && !empty($_SESSION['description_error'])) {
                echo '<span class="error">' . $_SESSION['description_error'] . '</span>';
                unset($_SESSION['description_error']);
            } ?>
        </div>
        <input type="hidden" name="id" value="<?= $image['id'] ?>">
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>

<?php include "templates/footer.php" ?>