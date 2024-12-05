<?php
include 'db.php'; // Include your database connection

// Check if the 'title' is provided in the query string
if (isset($_GET['title'])) {
    $title = $conn->real_escape_string($_GET['title']);
    $sql = "SELECT * FROM livres WHERE titre LIKE '%$title%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch book details
        $book = $result->fetch_assoc();
        $status = $book['statut'] ? 'Read' : 'Unread';
        $rating = $book['note'];
        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
    } else {
        echo "No book found with the title '$title'.";
        exit();
    }
} else {
    echo "No title provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .button a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            background-color: blue;
            border-radius: 5px;
        }
        .delete a {
            background-color: red;
        }
    </style>
</head>
<body>
    <h2>Book Details</h2>
    <table>
        <tr>
            <th>Title</th>
            <td><?php echo htmlspecialchars($book['titre']); ?></td>
        </tr>
        <tr>
            <th>Author</th>
            <td><?php echo htmlspecialchars($book['auteur']); ?></td>
        </tr>
        <tr>
            <th>Year of Publication</th>
            <td><?php echo htmlspecialchars($book['annee_publication']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo $status; ?></td>
        </tr>
        <tr>
            <th>Rating</th>
            <td><?php echo $stars; ?></td>
        </tr>
    </table>

</body>
</html>


