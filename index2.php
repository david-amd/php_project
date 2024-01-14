<?php
    include("database.php");

    // Aici incepe sesiunea
    session_start();

    // Verifica daca user-ul este logat
    if (!isset($_SESSION['username'])) {
        header("Location: index.php?form=login");
        exit();
    }

    // Adaugare carte
    if (isset($_POST['add_book'])) {
        $bookName = filter_input(INPUT_POST, 'book_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $authors = filter_input(INPUT_POST, 'authors', FILTER_SANITIZE_SPECIAL_CHARS);
        $availableStock = filter_input(INPUT_POST, 'available_stock', FILTER_VALIDATE_INT);

        if ($bookName && $authors && $availableStock !== false) {
            $sql = "INSERT INTO books (book_name, authors, available_stock) VALUES ('$bookName', '$authors', $availableStock)";

            if ($conn->query($sql) === TRUE) {
                echo "Book added successfully!";
            } else {
                echo "Error adding book: " . $conn->error;
            }
        } else {
            echo "Invalid input for adding a book!";
        }
    }

    // Delete Book
    elseif (isset($_POST['delete_book'])) {
        $bookId = filter_input(INPUT_POST, 'delete_book_id', FILTER_VALIDATE_INT);

        if ($bookId !== false) {
            $sql = "DELETE FROM books WHERE id = $bookId";

            if ($conn->query($sql) === TRUE) {
                echo "Book deleted successfully!";
            } else {
                echo "Error deleting book: " . $conn->error;
            }
        } else {
            echo "Invalid input for deleting a book!";
        }
    }

    // Modificare stoc
    elseif (isset($_POST['modify_stock'])) {
        $bookId = filter_input(INPUT_POST, 'modify_stock_id', FILTER_VALIDATE_INT);
        $newStock = filter_input(INPUT_POST, 'new_stock', FILTER_VALIDATE_INT);

        if ($bookId !== false && $newStock !== false) {
            $sql = "UPDATE books SET available_stock = $newStock WHERE id = $bookId";

            if ($conn->query($sql) === TRUE) {
                echo "Stock modified successfully!";
            } else {
                echo "Error modifying stock: " . $conn->error;
            }
        } else {
            echo "Invalid input for modifying stock!";
        }
    }

        // Se preiau informatiile despre carti din baza de date
        $bookSql = "SELECT * FROM books";
        $bookResult = mysqli_query($conn, $bookSql);
    

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index2</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

    <!-- De aici se pot adauga carti in baza de date -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Add Book</h3>
        Book Name: <input type="text" name="book_name" required><br>
        Authors: <input type="text" name="authors" required><br>
        Available Stock: <input type="number" name="available_stock" required><br>
        <input type="submit" name="add_book" value="Add Book">
    </form>

    <!-- De aici se poate sterge o carte dupa id -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Delete Book</h3>
        Book ID to delete: <input type="number" name="delete_book_id" required><br>
        <input type="submit" name="delete_book" value="Delete Book">
    </form>

    <!-- De aici se poate modifica stocul de carti -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Modify Stock</h3>
        Book ID to modify: <input type="number" name="modify_stock_id" required><br>
        New Stock: <input type="number" name="new_stock" required><br>
        <input type="submit" name="modify_stock" value="Modify Stock">
    </form>

        <!-- Afiseaza stocul de carti -->
        <h3>Available Books:</h3>
    <table border="1">
        <tr>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>Authors</th>
            <th>Available Stock</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($bookResult)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['book_name']; ?></td>
                <td><?php echo $row['authors']; ?></td>
                <td><?php echo $row['available_stock']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="logout.php">Logout</a>

</body>
</html>