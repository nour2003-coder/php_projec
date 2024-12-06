<?php 
include 'db.php'; 

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT id, titre FROM livres WHERE titre LIKE '%$query%' LIMIT 10"; 
    $result = $conn->query($sql);

    $titles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            
            $titles[] = ['id' => $row['id'], 'titre' => $row['titre']];
        }
    }
    echo json_encode($titles); // Return both id and titre in the response
} else {
    echo json_encode([]);
}
?>


