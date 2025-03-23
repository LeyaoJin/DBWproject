<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'config.php'; // Ensure database connection is included

if (!isset($_GET['disease'])) {
    echo json_encode(["error" => "No disease specified"]);
    exit();
}

// SQL Query to get both mortality data (per year) and pathogen info
$sql = "SELECT diseases.disease_name, 
               country_codes.country_name, 
               mortality_data.mortality_year, 
               IFNULL(mortality_data.deaths_count, 0) AS deaths_count, 
               COALESCE(fun_facts.fun_fact, 'No fun fact available') AS fun_fact,
               pathogen.pathogen_name, pathogen.species, pathogen.family, pathogen.pathogen_type,
               antibiotics.antibiotic_name
        FROM diseases
        JOIN disease_has_cause ON diseases.idDisease = disease_has_cause.idDisease
        JOIN causes ON disease_has_cause.cause = causes.idCause
        LEFT JOIN mortality_data ON causes.idCause = mortality_data.cause
        LEFT JOIN country_codes ON mortality_data.idCountry = country_codes.idCountry
        LEFT JOIN fun_facts ON diseases.idDisease = fun_facts.idDisease
        LEFT JOIN pathogen_has_disease ON diseases.idDisease = pathogen_has_disease.idDisease
        LEFT JOIN pathogen ON pathogen_has_disease.ncbi_taxon_id = pathogen.ncbi_taxon_id
        LEFT JOIN genome_has_antibiotic ON pathogen.ncbi_taxon_id = genome_has_antibiotic.ncbi_taxon_id
        LEFT JOIN antibiotics ON genome_has_antibiotic.antibiotic_name = antibiotics.antibiotic_name
        WHERE diseases.disease_name LIKE ?
        ORDER BY mortality_data.mortality_year ASC"; 

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
}

$search = "%".$_GET['disease']."%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$mortality_data = [];  // This will store deaths per year per country
$disease_info = [];     // This will store pathogen info + fun fact

$seen_pathogens = [];   // To avoid duplicate pathogen entries

while ($row = $result->fetch_assoc()) {
    // Store mortality data (per year per country)
    $mortality_data[] = [
        "disease_name" => $row['disease_name'],
        "country" => $row['country_name'] ?: "Unknown",
        "year" => $row['mortality_year'],
        "deaths_count" => $row['deaths_count']
    ];

    // Store pathogen & fun fact data only once
    $pathogen_name = trim(strtolower($row['pathogen_name'])); // Normalize pathogen name

    if (!isset($disease_info["fun_fact"])) {
        $disease_info["fun_fact"] = $row['fun_fact'];
    }

    // Prevent duplicate pathogens
    if (!empty($pathogen_name) && !isset($seen_pathogens[$pathogen_name])) {
        $disease_info["pathogens"][] = [
            "pathogen_name" => $row['pathogen_name'], // Keep original case for display
            "species" => $row['species'] ?: "Unknown",
            "family" => $row['family'] ?: "Unknown",
            "pathogen_type" => $row['pathogen_type'] ?: "Unknown",
            
        ];
        $seen_pathogens[$pathogen_name] = true;
    }

}

$stmt->close();
$conn->close();

// Output JSON response with separate arrays
echo json_encode([
    "mortality_data" => $mortality_data,
    "disease_info" => $disease_info
]);


