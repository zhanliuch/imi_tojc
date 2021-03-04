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
	<title>Article</title>
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
        /* CODE TAKEN FROM https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_loader */
        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 15px;
            height: 15px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-article">
                <?php
                    require_once "plagiarismsearch/init-api.php";
                    require_once "plagiarismsearch/Reports.php";
                    /* @var $api Reports */

                    require_once("model/Article.php");
                    require_once("model/Entity.php");
                    require_once("model/Similarity.php");
                    require_once("config.php");
                    require_once("model/ConnectionManager.php");
                    require_once("log.php");
                    require_once("downloadPlagiarismResults.php");

                    $dochtml = new DOMDocument();
                    $dochtml->loadHTMLFile("list-articles.php");
                    $sort = $dochtml->getElementById('dateSort')->nodeValue;

                    if($sort  == "Antichrono")
                        $articles = Article::getByLoggedUserId("antichrono");
                    else
                        $articles = Article::getByLoggedUserId("chrono");
                ?>

                <form class="article-form validate-form">
                    <span class="login100-form-title">
                        Instruction
                    </span>
                    <h5 style="color:green">1 - Create your article</h5>
                    <h5 style="color:green">2 - Auto-evaluate the quality of your article</h5>
                    <h5 style="color:green">3 - Submit it to the editor to receive your label</h5>
                    <h5>&nbsp;</h5>
    

                    <div>
                        <a class="txt2" href="./article.php">
                            Create a new article
                            <i class="fa fa-edit m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>


                    <div class="progress-table-wrap" id="table">
                        <a id="dateSort" class="date" href="javascript:sort()" hidden>Antichrono</a>
                        <div class="progress-table">
                            <div class="table-head">
                                <div class="code">Code</div>
                                <div class="date">Date</div>
                                <div class="title">Title</div>
                                <div class="evaluation">Evaluation</div>
                                <div class="update">Update</div>
                                <div class="delete">Delete</div>
                            </div>

                            <?php
                            if(sizeof($articles)==0)
                            {?>
                            <p>No articles to evaluate</p>
                            <?php
                            }
                            else
                            {
                                // loop through all articles and show each one of them
                            for ($x = 0; $x < sizeof($articles); $x++) { ?>
                                <div class="table-row">
                                    <div class="code"><?php echo $articles[$x]->code ?></div>
                                    <div class="date"><?php echo $articles[$x]->dateCreation ?></div>
                                    <div class="title"><?php echo $articles[$x]->title ?></div>
                                    <div class="evaluation">
                                        <?php
                                     

                                        $similarity = new Similarity();

                                        if($similarity->checkUrlEmpty($articles[$x]->id) == "not empty") {
                                            ?>
                                            <a href="./self_evaluation.php?articleId=<?php echo $articles[$x]->id; ?>"
                                               class="genric-btn link">Evaluate</a>
                                            <?php
                                        }
                                        else
                                        {
                                               // Retrieve the ID of the created plagiarism report ******************************************************************************
                                        $plagiarismFileR = str_replace('\\', '/',__DIR__) . "/plagiarismsearch/report/plagiarismreport-" . $articles[$x]->code . ".txt";
                                        $handle = fopen($plagiarismFileR, "r");
                                        $contentsReport = fread($handle, filesize($plagiarismFileR));

                                        $values = explode(',', $contentsReport);
                                        $valuesParsed = explode(':', $values[2]);
                                        $reportID = $valuesParsed[2];

                                        fclose($handle);

                                        $data = [];

                                        $plagiarismFileData = fopen("plagiarismsearch/report/plagiarismreportdata-" . $articles[$x]->code  . ".txt", "w") or die("Unable to open file!");
                                        fwrite($plagiarismFileData, $api->viewAction($reportID, $data));
                                        fclose($plagiarismFileData);

                                        $plagiarismFileResults = fopen("plagiarismsearch/report/plagiat-results-" . $articles[$x]->code . ".txt", "w") or die("Unable to open file!");
                                        $plagiarismFileData = str_replace('\\', '/',__DIR__) . "/plagiarismsearch/report/plagiarismreportdata-" . $articles[$x]->code . ".txt";
                                        $handle = fopen($plagiarismFileData, "r");
                                        $contentsResults = fread($handle, filesize($plagiarismFileData));

                                        $contentsParsed = explode(',"plagiat":', $contentsResults);
                                        $contentsParsedTwice = explode(',', $contentsParsed[1]);
                                        $result = $contentsParsedTwice[0];

                                        fwrite($plagiarismFileResults, $result);

                                        fclose($plagiarismFileResults);
                                        fclose($handle);

                                        if($result != "null") // pass value of result AND parse to get URL
                                        {
                                            // GET URL
                                            $plagiarismFileResults = fopen("plagiarismsearch/report/plagiat-results-" . $articles[$x]->code . ".txt", "a") or die("Unable to open file!");

                                            $plagiarismFileData = str_replace('\\', '/', __DIR__) . "/plagiarismsearch/report/plagiarismreportdata-" . $articles[$x]->code . ".txt";
                                            $handle = fopen($plagiarismFileData, "r");
                                            $contentsResults = fread($handle, filesize($plagiarismFileData));

                                            $contentsParsed = explode('{"url":"', $contentsResults);
                                            $contentsParsedTwice = explode('","type":', $contentsParsed[1]);
                                            $url = $contentsParsedTwice[0];
                                            $url = str_replace('\\', '', $url);

                                            fwrite($plagiarismFileResults, $url);

                                            fclose($plagiarismFileResults);
                                            fclose($handle);
                                            downloadPlagiarismResults($articles[$x]->code, $articles[$x]->id, $url, $result);
                                        }

                                        ?>

                                            <div class="loader"></div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="update">
                                        <a href="./article.php?articleId=<?php echo $articles[$x]->id; ?>" class="genric-btn link">Update</a>
                                    </div>
                                    <div class="delete">
                                        <a href="./deleteArticle.php?articleId=<?php echo $articles[$x]->id; ?>" onclick="return confirm('Are you sure you want to delete this article?')" class="genric-btn link">Delete</a>
                                    </div>
                                </div>
                            <?php }} ?>
                        </div>
                    </div>

                    <div class="text-center p-t-80">
                        <a class="txt2" href="./history.php">
                            View your article history
                            <i class="fa fa-history m-l-5" aria-hidden="true"></i>
                            &nbsp;&nbsp;&nbsp;
                        </a>
                        <a class="txt2" href="./logout.php">
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
        <!-- refresh table every 5 seconds -->
        setInterval(function(){
            $("#table").load( "list-articles.php #table" );
        }, 5000); //refresh every 5 seconds

    </script>
    <script>
        function sort() {
            <?php
            $dochtml = new DOMDocument();
            $dochtml->loadHTMLFile("list-articles.php");
            $sort = $dochtml->getElementById('dateSort')->nodeValue;

            if($sort=="Antichrono")
            {
                ?>

            document.getElementById('dateSort').innerText = "Chrono";

        <?php }
            if($sort=="Chrono")
                {
                ?>

            document.getElementById('dateSort').innerText = "Antichrono";
            <?php } ?>

            $("#table").load( "list-articles.php #table" );
    }
    </script>
</body>
</html>