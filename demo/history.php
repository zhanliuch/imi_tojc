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
            $dochtml->loadHTMLFile("history.php");
            $sort = $dochtml->getElementById('dateSort')->nodeValue;

            if($sort  == "Antichrono")
                $articles = Article::getByLoggedUserIdHistory("antichrono");
            else
                $articles = Article::getByLoggedUserIdHistory("chrono");
            ?>

            <form class="article-form validate-form">
					<span class="login100-form-title">
                        Here are all the articles you've submitted for review
					</span>


                <div class="progress-table-wrap" id="table">
                    <a id="dateSort" class="date" href="javascript:sort()" hidden>Antichrono</a>
                    <div class="progress-table">
                        <div class="table-head">
                            <div class="code">Code</div>
                            <div class="date">Date</div>
                            <div class="title">Title</div>
                            <div class="status">Status</div>
                        </div>

                        <?php
                        if(sizeof($articles)==0)
                        {?>
                            <p>No articles submitted yet</p>
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
                                    <div class="status">
                                        <?php
                                        switch($articles[$x]->article_status_id) {
                                            case(2):
                                                ?>
                                                <img height="35px" width="35px" src="./images/waiting.png" alt="Waiting approval">
                                            <?php
                                                break;
                                            case(3):
                                                ?>
                                                <img height="35px" width="35px" src="./images/accepted.png" alt="Accepted">
                                            <?php
                                                break;
                                            case(4):
                                                ?>
                                                <img height="35px" width="35px" src="./images/denied.png" alt="Denied">
                                            <?php
                                                break;                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php }} ?>
                    </div>
                </div>


                <div class="text-center p-t-80">
                    <a class="txt2" href="./list-articles.php">
                        Return to your article list
                        <i class="fa fa-list m-l-5" aria-hidden="true"></i>
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
    <!-- refresh table every 2 seconds -->
    setInterval(function(){
        $("#table").load( "history.php #table" );
    }, 5000); //refresh every 5 seconds

</script>
<script>
    function sort() {
        <?php
        $dochtml = new DOMDocument();
        $dochtml->loadHTMLFile("history.php");
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

        $("#table").load( "history.php #table" );
    }
</script>
</body>
</html>