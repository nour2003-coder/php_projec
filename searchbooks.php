<?php
include 'db.php'; // Include your database connection

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT id, titre FROM livres WHERE titre LIKE '%$query%' LIMIT 10"; // Modify table/column names as needed
    $result = $conn->query($sql);

    $titles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Store both the id and titre in the array
            $titles[] = ['id' => $row['id'], 'titre' => $row['titre']];
        }
    }
    echo json_encode($titles); // Return both id and titre in the response
} else {
    echo json_encode([]);
}
?>


