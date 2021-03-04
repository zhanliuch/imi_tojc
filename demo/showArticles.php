<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Articles with trust certification</title>
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

            require_once("model/Article.php");
            require_once("model/Entity.php");
			require_once("model/Similarity.php");
			require_once("model/Label.php");
			require_once("model/User.php");
			require_once("model/Company.php");
            require_once("config.php");
            require_once("model/ConnectionManager.php");
            require_once("log.php");

           //display all article with label, status = 3
            $articles = Article::getArticlesByStatus(3);

			$pdf_folder = 'article_pdf';

            ?>

            <form class="article-form validate-form">
					<span class="login100-form-title">
                        Show articles with trust certification
					</span>


                <div class="progress-table-wrap" id="table">
                    <div class="progress-table">
                        <div class="table-head">
                            <div class="code">Code</div>
                            <div class="date">Date</div>
                            <div class="title">Title</div>
                            <div class="status">Download</div>
                        </div>

                        <?php
                        if(sizeof($articles)==0)
                        {?>
                            <p>No article is certificated</p>
                            <?php
                        }
                        else
                        {
                            // loop through all articles and show each one of them
                            for ($x = 0; $x < sizeof($articles); $x++) { 
                                //get company code
								$user_id = $articles[$x]->user_id;
								$user = User::getUserById($user_id);
								$company_id = $user->company_id;
								$company_code = Company::getCompanyCodebyId($company_id);

								$article_code = $articles[$x]->code;
								$pdf_file = './' . $pdf_folder . '/' . $company_code . '/' . $article_code . '.pdf';	
								?>
                                <div class="table-row">
                                    <div class="code"><?php echo $article_code ?></div>
                                    <div class="date"><?php echo $articles[$x]->dateCreation ?></div>
									<div class="title"><?php echo $articles[$x]->title ?></div>
								
								<div class="status">
								<?php
									if (file_exists($pdf_file)) {
								?>									
										<a download="<?php echo $article_code . '.pdf' ?>" href="<?php echo $pdf_file ?>">
											<img height="40px" width="40px" src="./images/pdf.png" alt="PDF">
										</a>	
								<?php		
									}
								?>		

								</div>     
                                </div>
                            <?php }} ?>
                    </div>
                </div>

                <div class="text-center p-t-80">
                    <a class="txt2" href="searchLabel.php">
                        Check a trust label
                        <i class="fa fa-search m-l-5" aria-hidden="true"></i>
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

</body>
</html>