<?php include "../helperFunctions.php" ?>
<?php include "../templates/header.php" ?>
<?php include "../templates/navbar.php" ?>
<?php include "../database.php" ?>

<?php

// Checking if it is post request

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // define variables and set to empty values
    $name = $email = $password = $confirm_password = '';

    $name = sanitizeInput($_POST["name"]);
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);
    $confirm_password = sanitizeInput($_POST["confirm_password"]);

    // Validate name
    if (empty($name)) {
        $errors['name'] = 'Name field is required.';
    } elseif (strlen($name) < 3) {
        $errors['name'] = 'Name field should be atleast 3 characters long.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email field is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email address in not valid.';
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password field is required.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password field should be atleast 6 characters long.';
    }

    // Validate confirm password
    if (!array_key_exists('password', $errors)) {
        if ($password !== $confirm_password) {
            $errors['password'] = 'Password and Confirm password fields should match.';
        }
    }

    if (!count($errors)) {
        // inserting data into db

        // hashing password before insrting into db
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {

            $conn->close();

            $_SESSION['success'] = 'Registration is successful. Please login to continue';

            header('Location: '. baseUrl('auth/login.php'));

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            die('Insert erro');
        }
    }
}

?>

<div class="container">

    <div class="text-center">
        <h1>Register</h1>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <?php
                    if (array_key_exists('name', $errors)) {
                        echo '<span class="error">'. $errors['name'] .'</span>';
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <?php
                    if (array_key_exists('email', $errors)) {
                        echo '<span class="error">'. $errors['email'] .'</span>';
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <?php
                    if (array_key_exists('password', $errors)) {
                        echo '<span class="error">'. $errors['password'] .'</span>';
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php include "../templates/footer.php" ?>