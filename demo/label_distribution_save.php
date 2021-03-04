<?php
session_start();
error_reporting(0);
/*
// Authors: 	Zhan Liu
// Created: 	2020/05/15
// Last update:	2020/09/08
// Steps: 

1) Save the label the database - table "LABEL", and set Article Status; 
2) Create the qrcode and store to the folder; 
3) Generate the PDF with article content and qrcode, store to the pdf folder
*/


require_once("config.php");
require_once("model/ConnectionManager.php");
require_once("model/Entity.php");
require_once("model/Article.php");
require_once("model/Similarity.php");
require_once("log.php");
require_once("createPlagiarismReport.php");
//require_once("label_distribution.php");

require_once("model/Entity.php");
require_once("model/User.php");
require_once("model/Article.php");
require_once("model/Label.php");
require_once("model/Company.php");

require('fpdf/fpdf.php');

//Step 1: Save the label the database - table "LABEL", and set Article Status 
//(only the selected articles will be stored in the _POST, we set all selected article to lablled, others to un-lebelled);
//(In this prototype, we suppose that all journalists submit their articles to the same editor, and the editor validate the labels once a day) 

$values = array_values($_POST);
$article_id = $values[0];
//print_r(array_slice($_POST, 0));

foreach (array_slice($_POST, 0)as $key => $value) {
    //key = choice + id of indicator
    //value = value of the input (selected value of user, here the value is 1)
    
    //get id of article
    $article_id = ltrim($key,"choice");


    if (isset($article_id)){

        $article = Article::getArticleById($article_id);
        $code = $article->code;
        $title = $article->title;
        $summary = $article->summary;
        $content = $article->content;
        $user_id = $article->user_id;
        
        $user = User::getUserById($user_id);
        $user_name = $user->firstname . ' ' . $user->lastname;
        $company_id = $user->company_id;
    
        $company_code = Company::getCompanyCodebyId($company_id);
        $label_code = $company_code . '/' . $code;
    
        $label_qrcode = 'qrcode/'. $company_code . '/' . $code . '.png';

        //Insert into Lable table only the values we have selected
        //if the article is not in the table, we know it's value is zero)
        if($value != 0) {
            $label = new Label();
            $label->code = $label_code;
            $label->qrcode = $label_qrcode;
            $label->article_id = $article_id;
            $label->user_id = $_SESSION["userid"];

            $label->save();

            //change the status of the selected articles 
            $article->changeStatus($article_id, 3);

            //Step 2: Create the qrcode image and store to the qrcode folder; 
            $folder = 'qrcode';
            $companyID = $company_code;
            $articleCode = $code;
            createQRCode($folder, $companyID, $articleCode);

            //Step 3: Generate the PDF with article content and qrcode, store to the pdf folder

            class PDF extends FPDF
            {

            // Page header
            function Header()
            {
                global $label_qrcode;
                global $title;
                // Logo
                $this->Image('images/logo_ln.jpg',45,4,100);
                $this->Image($label_qrcode,155,2,45);
                $this->Ln(40);
                // Arial bold 16
                $this->SetFont('Arial','B',16);
                // Title
                $this->MultiCell(0,7,utf8_decode($title),0,'C');

                // Line break
                $this->Ln(5);

            }

            // Page footer
            function Footer()
            {
                // Position at 1.5 cm from bottom
                $this->SetY(-15);
                // Arial italic 8
                $this->SetFont('Arial','I',8);
                // Page number
                $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            }
            }

        
            // Instanciation of inherited class
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();

            //Author
            $pdf->SetFont('Arial','I',12);
            $pdf->SetTextColor(128,128,128);
            $pdf->MultiCell(0,5, 'Par '.utf8_decode($user_name),0,'L');
            $pdf->Ln(5);

            $pdf->SetFont('Arial','B',12);
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(0,5,utf8_decode($summary),0,'L');
            $pdf->Ln(3);

            $pdf->SetFont('Arial','',12);

            $pdf->MultiCell(0,5,utf8_decode($content),0,'L');

            $pdf_folder = 'article_pdf';
            $article_code = $articleCode;
            //$filename="article_pdf/123456.pdf";
            $filename=$pdf_folder . '/' . $companyID . '/' . $article_code . '.pdf';
            $pdf->Output($filename,'F');
        
        }
        
    }
        
        
}
//change the status of the unselected articles: put all articles which has the status 2 to 4 (self-assessment done, but not selected by editor)
$articles = Article::getArticlesByStatus(2);
for($x = 0; $x < sizeof($articles); $x++) {
    $article = $articles[$x];
    $article->changeStatus($article->id, 4);
}

header("Location: label_distribution.php");



//Step 2: Create the qrcode image and store to the qrcode folder; 
function createQRCode($folder, $companyID, $articleCode)
	{
	require_once("phpqrcode/qrlib.php");

    $company_folder = $folder . '/' . $companyID; 
    if (!file_exists($company_folder)) {
        mkdir($company_folder, 0777, true);
    }

    // Path where the images will be saved
    $filepath = $folder . '/' . $companyID . '/' . $articleCode . '.png';
    //$filepath = $folder . '/' . $articleCode . '.png';

    // Image (logo) to be drawn
    $logopath = 'images/logo_qrcode.png';
    // qr code content
    $codeContents = $companyID . '/' . $articleCode ;
    // Create the file in the providen path
    // Customize how you want
    QRcode::png($codeContents,$filepath , QR_ECLEVEL_H, 20);

    // Start DRAWING LOGO IN QRCODE

    $QR = imagecreatefrompng($filepath);

    // START TO DRAW THE IMAGE ON THE QR CODE
    $logo = imagecreatefromstring(file_get_contents($logopath));

    /**
     *  Fix for the transparent background
     */
    imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 127));
    imagealphablending($logo , false);
    imagesavealpha($logo , true);

    $QR_width = imagesx($QR);
    $QR_height = imagesy($QR);

    $logo_width = imagesx($logo);
    $logo_height = imagesy($logo);

    //echo($QR_width);
    //echo($logo_height);

    // Scale logo to fit in the QR Code
    $logo_qr_width = $QR_width/5;
    $scale = $logo_width/$logo_qr_width;
    $logo_qr_height = $logo_height/$scale;

    imagecopyresampled($QR, $logo, $QR_width/2.5, $QR_height/2.4, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);


    // Save QR code again, but with logo on it
    imagepng($QR,$filepath);

    // End DRAWING LOGO IN QR CODE

    // Ouput image in the browser
    //echo '<img src="'.$filepath.'" />';
	}



?>
