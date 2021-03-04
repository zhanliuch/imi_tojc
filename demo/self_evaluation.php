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
        .primary-checkbox{
            padding-right:5%;
        }

    </style>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function info(title,text)
        {
            //swal(String(title),String(text),"info");
            swal(String(title),String(text));
        }

        function swalConfirm()
        {
            $('#btn-submit').on('click',function(e){
                e.preventDefault();
                var form = $(this).parents('form');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function(isConfirm){
                    if (isConfirm) form.submit();
                });
            });


        }
    </script>

</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-article-evaluation">
            <form onsubmit="return confirm('Are you sure you want to finish the assessment?');" class="article-form validate-form" method="POST" action="createSelfEvaluation.php">
					<span class="login100-form-title">
						Article self-assessment : check with the trust indicators</span>
                <p>For more information regarding an indicator, please click the (!) icon.</p>

                <?php
                require_once("model/Article.php");
                require_once("model/Similarity.php");
                require_once("model/Indicator.php");
                require_once("model/IndicatorCategory.php");
                require_once("model/Entity.php");
                require_once("config.php");
                require_once("model/ConnectionManager.php");
                require_once("log.php");

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else
                    $url = "http://";
                // Append the host(domain name, ip) to the URL.
                $url.= $_SERVER['HTTP_HOST'];

                // Append the requested resource location to the URL
                $url.= $_SERVER['REQUEST_URI'];

                // Use parse_url() function to parse the URL
                // and return an associative array which
                // contains its various components
                $url_components = parse_url($url);

                // Use parse_str() function to parse the
                // string passed via URL
                parse_str($url_components['query'], $params);
                $articleId = $params['articleId'];
                $article = Article::getArticleById($articleId);
                $similarity = Similarity::getByArticleId($articleId);

                $indicators = Indicator::getAll();
                $indicators_categories = IndicatorCategory::getAll();
                $y = 0;


                ?>

                <p style="color:black; font-weight: bold;"><?php echo $article->title; ?></p>
                <input type="hidden" name="clientID" value="<?php echo $articleId; ?>" />

                <?php
                for($x=0; $x < sizeof($indicators_categories); $x++)
                {?>
                    <div class="single-element-widget mt-30">
                        <h3 class="mb-20"><?php echo $indicators_categories[$x]->id ?>. <?php echo $indicators_categories[$x]->name ?></h3>
                        <?php
                        while($indicators[$y]->id < (($indicators_categories[$x]->id+1)*10) and $y<sizeof($indicators))
                        {?>
                            <div class="switch-wrap d-flex justify-content-between">
                                <checklabel>
                                    <img class="hover" onclick="info('<?php echo addslashes($indicators[$y]->title) ?>','<?php echo addslashes($indicators[$y]->description)?>')" src="../images/info.png" height="15" width="15"/>
                                    <?php echo $indicators[$y]->title; ?>

                                    <?php if($indicators[$y]->title=="Originalité") { ?>
                                        <a href="<?php echo $similarity->report_url?>"><?php echo $similarity->score; ?>%</a>
                                        de contenu plagié, est-ce acceptable?
                                    <?php } ?>

                                </checklabel>

                                <div class="primary-checkbox">
                                    <?php switch($indicators[$y]->graduation)
                                    {
                                        case "Binary":
                                            ?>
                                                <input type="checkbox" id="choice<?php echo $indicators[$y]->id ?>" name="choice<?php echo $indicators[$y]->id ?>" value="1">
                                                <label for="choice<?php echo $indicators[$y]->id ?>"></label>
                                            <?php
                                            break;

                                        case "0 to 3":
                                            ?>
                                                <select id="choice<?php echo $indicators[$y]->id ?>" name="choice<?php echo $indicators[$y]->id ?>">
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3+</option>
                                                </select>
                                            <?php
                                            break;

                                        case "1 to 3":
                                            ?>
                                                <select id="choice<?php echo $indicators[$y]->id ?>" name="choice<?php echo $indicators[$y]->id ?>">
                                                    <option value="0">1</option>
                                                    <option value="1">2</option>
                                                    <option value="2">3+</option>
                                                </select>
                                            <?php
                                            break;

                                        case "Credibility":
                                            ?>
                                                <select id="choice<?php echo $indicators[$y]->id ?>" name="choice<?php echo $indicators[$y]->id ?>">
                                                    <option value="0">Pas crédible</option>
                                                    <option value="1">Plutôt crédible</option>
                                                    <option value="2">Sérieux</option>
                                                    <option value="3">Indéniable</option>
                                                </select>
                                            <?php
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                            $y++;
                            if($y==sizeof($indicators))
                            {
                            break;
                            }
                        }
                        ?>
                    </div>
                <?php
                }
                ?>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Submit
                    </button>
                </div>

                <div class="text-center p-t-80">
                    <a class="txt2" href="list-articles.php">
                    Return to your article list
                        <i class="fa fa-list m-l-5" aria-hidden="true"></i>
                        &nbsp;&nbsp;&nbsp;
                    </a>
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
</body>
</html>