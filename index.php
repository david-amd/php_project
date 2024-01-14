<?php
    include("database.php");

    // Incepe sesiunea
    session_start();

    $formState = 'register'; // Starea implicita a form-ului

    // Check if the form parameter is set and valid
    if (isset($_GET['form']) && ($_GET['form'] == 'login' || $_GET['form'] == 'register')) {
        $formState = $_GET['form'];
    }

    // Verifica daca user-ul a mai fost logat
    if (isset($_SESSION['username'])) {
        header("Location: index2.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if ($formState == 'register' && isset($_POST["submit"])) {
            // Logica pentru inregistrare
            if (empty($username)) {
                echo "Please enter a username";
            } elseif (empty($password)) {
                echo "Please enter a password";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (user, password) VALUES ('$username', '$hash')";
                try {
                    mysqli_query($conn, $sql);
                    echo "You are now registered!";
                } catch (mysqli_sql_exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        } elseif ($formState == 'login' && isset($_POST["submit"])) {
            // Logica pentru login
            $sql = "SELECT user, password FROM users WHERE user='$username'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $storedPasswordHash = $row['password'];

                if (password_verify($password, $storedPasswordHash)) {
                    echo "Login successful!";
                    // De aici incepe sesiunea cu user
                    $_SESSION['username'] = $username;
                    header("Location: index2.php");
                    exit(); 
                } else {
                    echo "Invalid password";
                }
            } else {
                echo "User not found";
            }
        }
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php if ($formState == 'register'): ?>
        <!-- Form-ul de inregistrare -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?form=register'; ?>" method="post">
            <h2>REGISTER</h2>
            username:<br>
            <input type="text" name="username"><br>
            password:<br>
            <input type="password" name="password"><br>
            <input type="submit" name="submit" value="Register"><br>
        </form>
        <p>Already have an account? <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?form=login'; ?>">Login</a></p>

    <?php elseif ($formState == 'login'): ?>
        <!-- Form-ul de logare -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?form=login'; ?>" method="post">
            <h2>LOGIN</h2>
            username:<br>
            <input type="text" name="username"><br>
            password:<br>
            <input type="password" name="password"><br>
            <input type="submit" name="submit" value="Login"><br>
        </form>
        <p>Don't have an account? <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">Register</a></p>

    <?php endif; ?>

</body>
</html>