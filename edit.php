<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM Livres WHERE id = $id";
    $result = $conn->query($sql);
    $book = $result->fetch_assoc();

    if (!$book) {
        die("Book not found");
    }
}

if (isset($_POST['update'])) {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $annee_publication = $_POST['annee_publication'];
    $statut = isset($_POST['statut']) ? 1 : 0;
    $note = $_POST['note'];

    $sql = "UPDATE Livres SET titre = '$titre', auteur = '$auteur', annee_publication = $annee_publication, 
            statut = $statut, note = $note WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating book: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="icon" href="img/bk.png" type="image/x-icon" />
</head>
<body>
    
    <form class="edit" method="POST" action="">
    <h2>Edit Book</h2>
        <label>Title:</label><br>
        <input type="text" name="titre" value="<?php echo $book['titre']; ?>" required><br>

        <label>Author:</label><br>
        <input type="text" name="auteur" value="<?php echo $book['auteur']; ?>" required><br>

        <label>Year of Publication:</label><br>
        <input type="number" name="annee_publication" value="<?php echo $book['annee_publication']; ?>" required><br>

        <label>Status (Read/Unread):</label><br>
        <input type="checkbox" name="statut" <?php echo $book['statut'] ? 'checked' : ''; ?>><br>

        <label>Rating (1-5):</label><br>
        <div class="stars">
        <input type="radio" id="star5" name="note" value="5" <?php echo $book['note'] == 5 ? 'checked' : ''; ?>>
        <label for="star5">★</label>
        <input type="radio" id="star4" name="note" value="4" <?php echo $book['note'] == 4 ? 'checked' : ''; ?>>
        <label for="star4">★</label>
        <input type="radio" id="star3" name="note" value="3" <?php echo $book['note'] == 3 ? 'checked' : ''; ?>>
        <label for="star3">★</label>
        <input type="radio" id="star2" name="note" value="2" <?php echo $book['note'] == 2 ? 'checked' : ''; ?>>
        <label for="star2">★</label>
        <input type="radio" id="star1" name="note" value="1" <?php echo $book['note'] == 1 ? 'checked' : ''; ?>>
        <label for="star1">★</label>
    </div>
        <br><br>

        <button type="submit" name="update">Update Book</button>
    </form>
</body>
</html>
