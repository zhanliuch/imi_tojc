<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Traceability</title>
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
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-article">
				
			<?php
				$username='';
				$datePublication='';
				$url='';
				$title='';
				$summary='';

				if (isset($_SESSION['userName'])){
					$username = $_SESSION['userName'];
					$datePublication = $_SESSION['userName'];
				}

				if (isset($_SESSION['datePublication'])){
					$datePublication = $_SESSION['datePublication'];
				}

				if (isset($_SESSION['url'])){
					$url = $_SESSION['url'];
				}

				if (isset($_SESSION['title'])){
					$title = $_SESSION['title'];
				}

				if (isset($_SESSION['summary'])){
					$summary = $_SESSION['summary'];
				}
			?>

				<form class="article-form validate-form" method="GET" target="_blank" action="<?php echo $url ?>">
					<span class="login100-form-title">
						Article Traceability
					</span>

					<div class="mt-10">
						<tracklabel>Author:</tracklabel>
						<input type="text" name="Author" value="<?php echo $username ?>" disabled class="single-input">
					</div>
				
					<div class="mt-10">
						<tracklabel>Publication Date</tracklabel>
						<input type="text" name="pubdate" value="<?php echo $datePublication ?>" disabled class="single-input">
					</div>

					<div class="mt-10">
						<tracklabel>URL:</tracklabel>
						<input type="text" name="Address" value="<?php echo $url ?>" disabled class="single-input">
					</div>

					<div class="mt-10">
						<tracklabel>Title:</tracklabel>
						<input type="text" name="Title" value="<?php echo $title ?>" disabled class="single-input">
					</div>

					<div class="mt-10">
						<tracklabel>Summary:</tracklabel>
						<textarea name="Summary" class="single-textarea" disabled><?php echo $summary ?></textarea>
					</div>
					</br>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Go to the official article page
						</button>
					</div>
		

				<div class="text-center p-t-36">
							<a class="txt2" href="searchLabel.php">
						Go back
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
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