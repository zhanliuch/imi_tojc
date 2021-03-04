<?php
session_start();
if($_SESSION["loggedIn"] != true) {
    echo("Access denied!");
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Articles Evaluation</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
    <style>
        .hover{
            cursor: pointer;
        }

    </style>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

    <script>


        /* used : https://github.com/t4t5/sweetalert/issues/740 */
        function toggleDiv(id)
        {
            var x = document.getElementById(id);
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-article-evaluation">
                <form onsubmit="return confirm('Are you sure you want to finish the distribution?');" class="article-form validate-form" method="POST" action="label_distribution_save.php">

                    <?php
                    require_once("model/Article.php");
                    require_once("model/Entity.php");
                    require_once("model/SelfEvaluationScore.php");
                    require_once("model/SelfEvaluation.php");
                    require_once("model/Indicator.php");
                    require_once("model/User.php");
                    require_once("config.php");
                    require_once("model/ConnectionManager.php");
                    require_once("log.php");
                    require_once("downloadPlagiarismResults.php");


                    //display onyl the article after the self-assessment
                    $articles = Article::getArticlesByStatus(2);
                    ?>
                        <span class="login100-form-title">
                            Labelling distribution
                        </span>

                        <?php
                        if(sizeof($articles)==0)
                        {?>
                            <p>No articles to evaluate</p>
                            <?php
                        }
                        else
                        {
                        // loop through all articles and show each one of them
                        for ($x = 0; $x < sizeof($articles); $x++) {
                            $user = User::getUserById($articles[$x]->user_id);
                            $firstname = $user->firstname;
                            $lastname = $user->lastname;
                            $score = SelfEvaluationScore::getScoreArticle($articles[$x]->id);
                            ?>

                            <div class="single-element-widget mt-30">
                                <div class="switch-wrap d-flex justify-content-between">
                                    <h4 class="mb-10">Article <?php echo $articles[$x]->code?></h4>
                                    <div class="primary-checkbox">
                                        <input type="checkbox" id="choice<?php echo $articles[$x]->id ?>" name="choice<?php echo $articles[$x]->id ?>" value="1">
                                        <label for="choice<?php echo $articles[$x]->id ?>"></label>
                                    </div>
                                </div>
                            <p>Author: <checklabel><?php echo $firstname . " " . $lastname; ?></checklabel></p>
                            
                            <p>Title: <checklabel><?php echo $articles[$x]->title?></checklabel></p>

                                <?php
                                $title = "Self Assessment results of article " . $articles[$x]->code;
                                $indicators = Indicator::getAll();
                                $selfEvaluation = SelfEvaluation::getAllByArticleId($articles[$x]->id);
                                $text = "";                           

                                for($z=0;$z<sizeof($indicators);$z++)
                                {
                                    
                                    $text .= $indicators[$z]->title . "&nbsp;&nbsp;";
                                    $check = '<img src="images/check.png" width="20" height="20"/>';
                                    $uncheck = '<img src="images/uncheck.png" width="20" height="20"/>';

                                    $icon = $uncheck ;
                                

                                    for($y=0;$y<sizeof($selfEvaluation);$y++){
                                        if($indicators[$z]->id == $selfEvaluation[$y]->indicator_id){
                                            $icon = $check;
                                        }
                                    }
                                    $text .= $icon;
                                    
                                    $text .= "<br>";
                                }
                                ?>

                                <p>
                                    <img class="hover" onclick="toggleDiv('info<?php echo $articles[$x]->code ?>')" src="../images/info.png" height="18" width="18"/>

                                    <?php
                                        if($score>12)
                                        {
                                    ?>
                                            Self-evaluation: <span style="color: green; font-weight: bold;">Positive</span>
                                    <?php
                                        }
                                        else
                                        {
                                    ?>
                                            Self-evaluation: <span style="color: red; font-weight: bold;">Negative</span>
                                    <?php
                                        }
                                    ?>
                                </p>

                                <div id="info<?php echo $articles[$x]->code ?>" style="display:none;"><?php echo $text ?></div>
                            </p>

                            <p>Address: <a class="txt3" target="_blank" href="<?php echo $articles[$x]->url?>">
                                Link</a></p>
                            <p>Number of Click: <checklabel><?php echo $articles[$x]->nbClick; ?></checklabel></p>
                            <p>Number of Share: <checklabel><?php echo $articles[$x]->nbShare; ?></checklabel></p>
                        </div>

                        <?php }} ?>

                        <div class="container-login100-form-btn">
                            <button class="login100-form-btn">
                                Distribute
                            </button>
                        </div>

                        <div class="text-center p-t-80">
                            
                            <a class="txt2" href="logout.php">
                                Logout
                                <i class="fa fa-sign-out m-l-5" aria-hidden="true"></i>
                            </a>
                        </div>

				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
    <script>
        function getText()
        {
            return <?php echo "hey" ?>
        }
    </script>

</body>
</html>