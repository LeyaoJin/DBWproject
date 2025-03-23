<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'config.php'; // Ensure database connection is included

if (!isset($_GET['ncbi_taxon_id'])) {
    echo json_encode(["error" => "No pathogen specified"]);
    exit();
}

// SQL Query to fetch antibiotics based on pathogen
$sql = "SELECT antibiotics.antibiotic_name, 
               genome_has_antibiotic.resistant_phenotype, 
               genome.genome_id
        FROM genome
        JOIN genome_has_antibiotic ON genome.genome_id = genome_has_antibiotic.genome_id
        JOIN antibiotics ON genome_has_antibiotic.antibiotic_name = antibiotics.antibiotic_name
        WHERE genome.ncbi_taxon_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
}

$ncbi_taxon_id = $_GET['ncbi_taxon_id'];
$stmt->bind_param("i", $ncbi_taxon_id);
$stmt->execute();
$result = $stmt->get_result();

$antibiotics = [];

while ($row = $result->fetch_assoc()) {
    $antibiotics[] = [
        "antibiotic_name" => $row['antibiotic_name'],
        "resistance" => $row['resistant_phenotype'],
        "genome_id" => $row['genome_id']
    ];
}

$stmt->close();
$conn->close();

// Output JSON response
echo json_encode(["antibiotics" => $antibiotics], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
