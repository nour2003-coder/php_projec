<?php
include 'db.php'; 
$message_erreur = "";


if (isset($_POST['add'])) {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $annee_publication = $_POST['annee_publication'];
    $statut = isset($_POST['statut']) ? 1 : 0;

    // Automatically set the rating to 1 if the book is unread
    $note = $statut ? $_POST['note'] : 1;

    // Validate year of publication
    if ($annee_publication > date('Y')) {
        $message_erreur = "Publication year cannot be greater than the current year.";
    } else {
        $sql = "INSERT INTO Livres (titre, auteur, annee_publication, statut, note) 
                VALUES ('$titre', '$auteur', $annee_publication, $statut, $note)";

        if ($conn->query($sql) === TRUE) {
            // Redirect after successful insertion
            echo "New book added successfully!";
            header("Location: index.php");
            exit(); // Make sure to stop script execution after redirect
        } else {
            $message_erreur = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : '';


$sql = "SELECT * FROM livres";

// Apply filtering
if ($filter_status === 'read') {
    $sql .= " WHERE statut = 1";
} elseif ($filter_status === 'unread') {
    $sql .= " WHERE statut = 0";
}

// Apply sorting
if ($sort_order === 'rating_desc') {
    $sql .= " ORDER BY note DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css" />
    <title>Library Management</title>
    <link rel="icon" href="img/bk.png" type="image/x-icon" />
    <script src="script.js" defer></script>
</head>
<body>
    <div class="titre">
        <img width=50 src="img/bk.png" alt="bookimg">
        <h2>Library Collection</h2>
    </div>
    <div class="add">
    <h3 class="titreadd">Add a New Book</h3>
    
    <form method="POST" action="">
        <label>Title:</label><br>
        <input type="text" name="titre" required><br>

        <label>Author:</label><br>
        <input type="text" name="auteur" required><br>

        <label>Year of Publication:</label><br>
        <input type="number" name="annee_publication" required><br>

        <label>Status (Read/Unread):</label><br>
        <input type="checkbox" id="statut" name="statut"><br>

        <label>Rating (1-5):</label><br>
        <div class="stars">
            <input type="radio" id="star5" name="note" value="5">
            <label for="star5">★</label>
            <input type="radio" id="star4" name="note" value="4">
            <label for="star4">★</label>
            <input type="radio" id="star3" name="note" value="3">
            <label for="star3">★</label>
            <input type="radio" id="star2" name="note" value="2">
            <label for="star2">★</label>
            <input type="radio" id="star1" name="note" value="1">
            <label for="star1">★</label>
        </div>
        <br><br>

        <button class="submit" type="submit" name="add">Add Book</button>
    </form>
</div>

    <div class="erreur">
        <?php echo $message_erreur; ?>
    </div>

<div class="filtersearch">
    <div class="controls position">
        <form method="GET" action="">
            <label for="filter_status">Filter by Status:</label>
            <select name="filter_status" id="filter_status">
                <option value="">All</option>
                <option value="read" <?php echo $filter_status === 'read' ? 'selected' : ''; ?>>Read</option>
                <option value="unread" <?php echo $filter_status === 'unread' ? 'selected' : ''; ?>>Unread</option>
            </select>

            <label for="sort_order">Sort by:</label>
            <select name="sort_order" id="sort_order">
                <option value="">Default</option>
                <option value="rating_desc" <?php echo $sort_order === 'rating_desc' ? 'selected' : ''; ?>>Rating (Descending)</option>
            </select>

            <button type="submit">Apply</button>
        </form>
    </div>
    <div class="search">
       
        <label for="book-search">Search for a Book:</label><br>
        <div class="searchinline">
        <input type="text" id="book-search" placeholder="Start typing a book title...">
        <button id ="search">search</button>
        </div>
        <ul id="search-results"></ul>
    </div>
    
    </div>
    <!-- Display Books -->
    <div class="aff">
        <table cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year of Publication</th>
                    <th>Status</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>{$row['titre']}</td>
                        <td>{$row['auteur']}</td>
                        <td>{$row['annee_publication']}</td>
                        <td>" . ($row['statut'] ? 'Read' : 'Unread') . "</td>
                        <td >";
                        // Generate stars based on the rating
                        $rating = $row['note'];
                        echo "<div class='stars-display'>";
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? "<span class='star filled'>★</span>" : "<span class='star empty'>☆</span>";
                        }
                        echo "</div>";
                        
 
                    echo "</td>
                        <td>
                            <button class='button details'><a href='edit.php?id={$row['id']}'>Edit</a></button> |
                            <button class='button delete'><a href='delete.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\");'>Delete</a></button>
                        </td>
                    </tr>";
                    
                    }
                } else {
                    echo "<tr><td colspan='6'>No books found</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
