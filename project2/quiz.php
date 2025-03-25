<?php
    include 'config.php';

    // If we are starting the quiz, we will set current question to 0. 
    if (!isset($_SESSION['current_question'])) {
        $_SESSION['current_question'] = 0;
    }

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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Which Disease are you?</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Free HTML5 Template by FREEHTML5.CO" />
        <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
        <meta name="author" content="FREEHTML5.CO" />

        <meta property="og:title" content="" />
        <meta property="og:image" content="" />
        <meta property="og:url" content="" />
        <meta property="og:site_name" content="" />
        <meta property="og:description" content="" />
        <meta name="twitter:title" content="" />
        <meta name="twitter:image" content="" />
        <meta name="twitter:url" content="" />
        <meta name="twitter:card" content="" />

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="favicon.ico">

        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,400italic,700' rel='stylesheet'
            type='text/css'>

        <!-- Animate.css -->
        <link rel="stylesheet" href="css/animate.css">
        <!-- Icomoon Icon Fonts-->
        <link rel="stylesheet" href="css/icomoon.css">
        <!-- Simple Line Icons -->
        <link rel="stylesheet" href="css/simple-line-icons.css">
        <!-- Magnific Popup -->
        <link rel="stylesheet" href="css/magnific-popup.css">
        <!-- Bootstrap  -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="css/style.css">
        <!-- Modernizr JS -->
        <script src="js/modernizr-2.6.2.min.js"></script>
        <title>Login - InDiMoMap</title>

        <script>
            let currentQuestion = 0;

            function showNextQuestion() {
                const questions = document.querySelectorAll('.question');
                questions[currentQuestion].style.display = 'none';
                currentQuestion++;
                if (currentQuestion < questions.length) {
                    questions[currentQuestion].style.display = 'block';
                    if (currentQuestion === questions.length - 1) {
                        document.getElementById('next-btn').innerText = 'Submit';
                    }
                } else {
                    document.getElementById('quizForm').submit();
                }
            }

            function enableNextButton(questionIndex) {
                const nextButton = document.getElementById('next' + questionIndex);
                nextButton.disabled = false;  // Enable the button when an option is selected
            }

            window.onload = function() {
                const questions = document.querySelectorAll('.question');
                questions.forEach((q, index) => {
                    if (index !== 0) q.style.display = 'none';
                    
                    // Disable all Next buttons initially
                    const nextButton = q.querySelector('button');
                    if (nextButton) nextButton.disabled = true;
                });
            };
        </script>

        <style>
            
            input[type="radio"] {
                transform: scale(1.5);
                margin-right: 10px; 
            }
            .button-container {
                text-align: center; /* Centers the button */
                margin-top: 20px; /* Adds some space above the button */
            }

           
            .button-container button {
                background-color: #52d3aa; 
                color: white;
                padding: 10px 20px; 
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .button-container button:hover {
                background-color: #52d3aa; 
            }
            
        </style>


    </head>
    <body>
        <header role="banner" id="fh5co-header"  class="navbar-fixed-top fh5co-animated slideInDown">
            <div class="container">
                <nav class="navbar navbar-default">
                    <div class="navbar-header">
                        <!-- Mobile Toggle Menu Button -->
                        <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle" data-toggle="collapse" data-target="#navbar"
                            aria-expanded="false" aria-controls="navbar"><i></i></a>
                        <a class="navbar-brand" href="index.php">InDiMoMap</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="index.php" data-nav-section="home" class="external"><span>Home</span></a></li>
                            <li class="active"><a href="introquiz.php" data-nav-section="quiz" class="external"><span>Quiz</span></a></li>
                            <li><a href="index.php#fh5co-team" data-nav-section="team" class="external"><span>Team</span></a></li>
                            <li><a href="index.php#fh5co-contact" data-nav-section="contact" class="external"><span>Contact</span></a>
                            </li>
                            <?php
								session_start();
								include 'config.php'; 
								
								// Check if user is logged in by verifying the session
								if (isset($_SESSION['email'])) {
									
									// echo '<li><a href="personal.php" class="external"><span>User</span></a></li>';
									$email = $_SESSION['email'];

									// Now we get the name from the user 
									$userQuery = $conn->prepare("SELECT first_name FROM users WHERE email = ?");
									$userQuery->bind_param("s", $email);
									$userQuery->execute();
									$userResult = $userQuery->get_result();

									if ($userResult->num_rows > 0) {
										$user = $userResult->fetch_assoc();
										$name = $user['first_name'];
										
										// Display user's name and link to personal.php
										echo '<li><a href="personal.php"  class="external"><span>' . $name . '</span></a></li>';
									} 
									// In case something didn't work we will show the log in page. 
									else {
										echo '<li><a href="login.php" data-nav-section="login" class="external"><span>Login</span></a></li>';
									}

									$userQuery->close();
									$conn->close();
								} else {
									// If not logged in, show the login option
									echo '<li><a href="login.php" data-nav-section="login" class="external"><span>Login</span></a></li>';
								}
							?>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <section id="quiz" data-section="quiz" data-stellar-background-ratio="0.5">
            <div class="gradient-overlay"></div>
            <div class="quiz-container">
                <h1>Which Disease Are You?</h1>
                <form id="quizForm" class="quizForm" action="quiz_results.php" method="POST">
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
                                echo "<input type='radio' name='q$question_id' value='$answer_id' onclick='enableNextButton($index)'> $answer_text<br>";
                            }
                            echo "<div class='button-container'>";
                            if ($index < $num_questions - 1) {
                                echo "<button type='button' id='next$index' onclick='showNextQuestion()'>Next</button>";                            } 
                            else {
                                echo "<button type='submit' id='next$index'>Submit</button>";
                            }
                            echo "</div>"; 
                            echo "</div>";  
                        }
                    ?>                        
                </form>
            </div>
        </section>

        

        <!-- jQuery -->
        <script src="js/jquery.min.js"></script>
        <!-- jQuery Easing -->
        <script src="js/jquery.easing.1.3.js"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js"></script>
        <!-- Waypoints -->
        <script src="js/jquery.waypoints.min.js"></script>
        <!-- Stellar Parallax -->
        <script src="js/jquery.stellar.min.js"></script>
        <!-- Counter -->
        <script src="js/jquery.countTo.js"></script>
        <!-- Magnific Popup -->
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/magnific-popup-options.js"></script>
        <!-- Main JS (Do not remove) -->
        <script src="js/main.js"></script>
    </body>
</html>

<?php
$conn->close();
?>