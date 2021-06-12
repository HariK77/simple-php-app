<?php include "../helperFunctions.php" ?>
<?php include "../templates/header.php" ?>
<?php include "../templates/navbar.php" ?>
<?php include "../database.php" ?>

<?php

// Checking if it is post request

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // define variables and set to empty values
    $email = $password = '';

    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);


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

    if (!count($errors)) {
        // getting user from db

        $sql = "SELECT '*' FROM users WHERE email='$email' LIMIT 1";

        $result = $conn->query($sql);

        $data = mysqli_fetch_assoc($result);

        jsAlert($data['email']);

        if(!password_verify($password, $data['password'])) {
            $errors['check_fail'] = 'Either email/password is wrong.';
        } 

        if (!count($errors)) {

            $_SESSION['success'] = 'Login Successful';

            header('Location: '. baseUrl());
    
        }
    }
}


?>

<div class="container">

    <div class="text-center">
        <h1>Login</h1>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email">
                    <?php
                    if (array_key_exists('email', $errors)) {
                        echo '<span class="error">'. $errors['email'] .'</span>';
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                    <?php
                    if (array_key_exists('password', $errors)) {
                        echo '<span class="error">'. $errors['password'] .'</span>';
                    }
                    ?>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

</div>

<?php include "../templates/footer.php" ?>