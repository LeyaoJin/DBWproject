<?php
    include 'config.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $total_score = 0;
        foreach ($_POST as $answer_id){
            $stmt = $conn->prepare("SELECT score FROM answers WHERE idAnswer = ?");
            $stmt->bind_param("i", $answer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()){
                $total_score += $row['score'];
            }
        }
    }
    else {
        die("No questions submited.");
    }
    $stmt = $conn->prepare("SELECT ncbi_taxon_id, result_description FROM results_mapping WHERE ? BETWEEN min_score AND max_score");
    $stmt->bind_param("i", $total_score);
    $stmt->execute();
    $result = $stmt->get_result();
    $pathogen_data = null;
    if ($row = $result->fetch_assoc()) {
        $ncbi_taxon_id = $row['ncbi_taxon_id'];
        $result_description = $row['result_description'];

        // Fetch pathogen details using ncbi_taxon_id
        $stmt = $conn->prepare("SELECT pathogen_name FROM pathogen WHERE ncbi_taxon_id = ?");
        $stmt->bind_param("s", $ncbi_taxon_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($pathogen = $result->fetch_assoc()) {
            $pathogen_data = [
                "name" => $pathogen['pathogen_name'],
                "description" => $result_description
            ];
        }
    }

    $conn->close();

?> 

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Which Disease Are You?</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f8ff;
                padding: 20px;
            }
            .quiz-container {
                background-color: white;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                margin: auto;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            .question {
                font-size: 1.2em;
                margin: 10px 0;
            }
            .answers {
                margin: 10px 0;
            }
            button {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px;
                font-size: 1em;
                cursor: pointer;
                width: 100%;
            }
            button:hover {
                background-color: #45a049;
            }
            .result {
                font-size: 1.5em;
                margin-top: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <p><strong>Total Score:</strong> <?php echo $total_score; ?></p>
        <?php if ($pathogen_data): ?>
            <h2>You are associated with:</h2>
            <p><strong><?php echo $pathogen_data['name']; ?></strong></p>
            <p><?php echo $pathogen_data['description']; ?></p>
        <?php else: ?>
            <p>No matching pathogen found for your score.</p>
        <?php endif; ?>
        </div>
    </body>
</html>
