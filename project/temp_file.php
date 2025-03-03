<!-- This is just a temporary file to check that the search engine works properly -->
<?php
    include 'config.php';

    if (!isset($_GET['query']) || !isset($_GET['type'])) {
        echo "Invalid request. Please provide a valid search query.";
        exit();
    }

    $query = htmlspecialchars($_GET['query']);
    $searchTerm = "%$query%";
    $type = $_GET['type'];

    if ($type === "disease") {
        $stmt = $conn->prepare("SELECT * FROM diseases WHERE disease_name LIKE ?");
    } elseif ($type === "pathogen") {
        $stmt = $conn->prepare("SELECT * FROM pathogen WHERE pathogen_name LIKE ?");
    } else {
        echo "Invalid type specified.";
        exit();
    }

    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Search Results for '$query' ($type)</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . " - Name: " . ($type === "disease" ? $row['disease_name'] : $row['pathogen_name']) . "<br>";
        }
    } else {
        echo "<p>No results found for '$query'.</p>";
    }
?>
