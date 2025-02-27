<?php
    include 'config.php';
    // This returns a result_Set if the query is successful. 
    $questionQuery = "SELECT idQuestion, question_text FROM questions";
    $questionResult = $conn -> query($questionQuery);
    // Here we check if the query was successful, handling the error if it is not
    if (!$questionResult) {
        die("Error");
    }
    // Here we get all the questions and store them in an associative array.  
    $questions = $questionResult->fetch_all(MYSQLI_ASSOC);
?> 

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Which Disease Are You?</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f0f8ff; padding: 20px; }
            .quiz-container { background-color: white; border-radius: 10px; padding: 20px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1); max-width: 600px; margin: auto; text-align: center; }
            .question { display: none; }
            .question.active { display: block; } 
            .button-container {display: flex; justify-content: center; margin-top: 15px;}
            button { background-color: #4CAF50; color: white; border: none; padding: 10px; font-size: 1em; cursor: pointer; margin-top: 15px; display: none; }
            button:hover { background-color: #45a049; }
        </style>
    </head>

    <body>
        <h1>Which infectious disease are you?</h1>
        <div class="quiz-container">
            <form id="quizForm" action="quiz_results.php" method="POST">
                <?php
                    $num_questions = count($questions);
                    foreach ($questions as $index => $question) {
                        $question_id = $question['idQuestion'];
                        $question_text = htmlspecialchars($question['question_text']);

                        $answerQuery = "SELECT idAnswer, answer_text FROM answers WHERE idQuestion = $question_id";
                        $answerResult = $conn->query($answerQuery);

                        if (!$answerResult) {
                            die("Error fetching answers: " . $conn->error);
                        }

                        $answers = $answerResult->fetch_all(MYSQLI_ASSOC);

                        if ($index == 0){
                            $class = "question active";
                        }
                        else{
                            $class = "question";
                        }

                        echo "<div class='$class' id='question$index'>";
                        echo "<h2>Question $question_id</h2>";
                        echo "<h2>$question_text</h2>";

                        foreach ($answers as $answer) {
                            $answer_id = $answer['idAnswer'];
                            $answer_text = htmlspecialchars($answer['answer_text']);
                            echo "<input type='radio' name='q$question_id' value='$answer_id' onclick='showNextButton($index)'> $answer_text<br>";
                        }
                        echo "<div class='button-container'>";
                        if ($index < $num_questions - 1) {
                            echo "<button type='button' id='next$index' onclick='nextQuestion($index)'>Next</button>";
                        } 
                        else {
                            echo "<button type='submit' id='next$index'>Submit</button>";
                        }
                        echo "</div>"; 
                        echo "</div>";  
                    }
                ?>
            </form>
        </div>
        <script>
            function showNextButton(index) {
                document.getElementById('next' + index).style.display = "block";
            }

            function nextQuestion(currentIndex) {
                document.getElementById('question' + currentIndex).classList.remove('active');
                document.getElementById('question' + (currentIndex + 1)).classList.add('active');
            }
        </script>
    </body>
</html>

<?php
$conn->close();
?>
