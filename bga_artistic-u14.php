<?php
include('inc/dbconfig.php');
include_once('inc/common.php');
include('inc/constants.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$comm = new Common();

//get participants 
require_once('admin/assets/TCPDF-master/tcpdf.php');    
 
// create new PDF document
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    private $eventname;
    private $address;
    private $evdate;
    private $logo;
    private $rptheader;
    
   //Page header
    public function Header() {
        $pageN = $this->PageNo();
        
            $image_file =dirname(__FILE__); 
            if($this->rptheader!='') 
            {
                // $image_file =    $image_file ."/assets/images/".$this->rptheader;
                //  $this->Image($image_file, 1, 1, 20, 25, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            else
            {
                $image_file ='assets/images/bga.jpg';
                // $this->Image($image_file, 20, 10, 15, 15, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                //  // Set font
                // $this->SetFont('helvetica', 'B', 20);
                // // Title
                // $this->Cell(0, 8, $this->eventname, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                // $this->Ln();
                // $this->SetFont('helvetica', '', 10);
                // $this->Cell(0, 6, $this->address, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                // $this->Ln();
                // $this->Cell(0, 10, $this->evdate, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                //$this->writeHTML( $image_file, true, false, false, false, '');
                
            $html_content = '<table border="0"><tr><td align="center"><img src="'.$image_file.'" width="55px" height="55px" border="0" /><font style="font-size: 20px;font-family:"Comic Sans MS", "Comic Sans", cursive">'. $this->eventname.'</font><img src="assets/images/bga2.jpg" height="55px" border="0" /></td></tr><tr><td align="center">Affiliated to Gymnasts Association of Karnataka</td></tr></table>';   
            $this->writeHTML($html_content, false, true, false, true);
            }
               
    }
//
    // Page footer
    public function Footer() {
         $pageN = $this->PageNo();
        if ($pageN > 1) {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $ipath =$this->logo;
            $html_content = '<table><tr><td><img src="'.$ipath.'" width="15px" height="15px" border="0" /></td><td align="center">Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().'</td><td align="right">indiangymnastics.live</td></tr></table>';   
            $this->writeHTML($html_content, false, true, false, true);
        }

    }
    public function seteventname($val) {
        $this->eventname = $val;            
    }
    public function setaddress($val) {
        $this->address = $val;            
    }
    public function setevdate($val) {
        $this->evdate = $val;            
    }
    public function setlogo($val) {
        $this->logo = $val;            
    }
    public function setrptheader($val) {
        $this->rptheader = $val;            
    }
}
 
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
//$pdf->setlogo('assets/images/MAGA.jpg');

$pdf->seteventname('ENGALURU Gymnasts Association (R.)');
$pdf->setaddress('Affilited to Gymnasts Association of Karnataka');

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RnRFit');
$pdf->SetTitle('Aerobic');
$pdf->SetSubject('Aerobic');
$pdf->SetKeywords('Aerobic');

// set default header data
//$pdf->SetHeaderData(dirname(__FILE__).$eventlogo, PDF_HEADER_LOGO_WIDTH, $eventname, $eventaddress);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font 922D8D
$pdf->SetFont('helvetica', '', 10, '', true);

$pdf->AddPage();
$pdf->SetRightMargin(10);
$pdf->SetLeftMargin(10);
//---------------First Page ---------------------------

$imagepath =dirname(__FILE__); 
$html ='<style> td{height:25px;}</style>';
$html .='<table width="900" border="0" style="padding:0px;">
<tr><td><hr></td></tr>
<tr><td>';
$html .='<table border="0" width="600" cellspacing="0" cellpadding="0"><tbody>';
$html .='<tr><td colspan="2">&nbsp;</td></tr>'; 
$html .='<tr><td colspan="2" style="text-align: center;">Entry Form Artistic Gymnastics</td></tr>'; 
$html .='<tr><td colspan="2">&nbsp;</td></tr>'; 
$html .='<tr><td style="text-align: right;">Name of the Unit: </td><td style="text-align: left;"> RnRFit Gymnastics Academy</td></tr>';  
$html .='</tbody></table>';
$html .='</td></tr>';
$html .='<tr><td>';  
$html .='<table border="1" width="600" style="align-content: center;"  cellspacing="0" cellpadding="0"><tbody>';
$sql="SELECT * FROM `aer` where `Event` LIKE '44'";
$data= $comm->executesql($sql);
$html .='<tr><td align="center"> Age Group</td><td style="width:20px;">Sl. No.</td><td style="width:250px;" colspan="2">
<table border="0" style="text-align: center;"  cellspacing="0" cellpadding="0">
<tr><td colspan="2" align="center">Name</td></tr>
<tr><td>First Name</td><td>Last Name</td></tr>
</table></td><td align="center">Aadhar No.</td><td align="center">Date of Birth</td></tr>';
$i=1;
foreach ($comm->executesql($sql) as $row)
{
    if($row['Gender']=='Male')
    {
        if($i==1)
        {
            $html .='<tr><td rowspan="7" style="text-align: center;"> Gymnasts(MAG)</td><td style="width:20px;"> '.$i.'</td><td style="width:150px;"> '. strtoupper($row['name']) . '</td><td style="width:100px;"> '. strtoupper($row['lastname']) . '</td><td> '. $row['aadhaar'] . '</td><td> '.date("d/m/Y", strtotime($row['dob'])). '</td></tr>';
            $i++;
        }
        else
        {
            $html .='<tr><td> '.$i.'</td><td style="width:150px;"> '. strtoupper($row['name']) . '</td><td style="width:100px;"> '. strtoupper($row['lastname']) . '</td><td> '. $row['aadhaar'] . '</td><td> '. date("d/m/Y", strtotime($row['dob'])) . '</td></tr>';
            $i++;
        }        
    }    
}
$html .='<tr><td> 4</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>'; 
$html .='<tr><td> 5</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>'; 
$html .='<tr><td> 6</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>'; 
$html .='<tr><td> 7</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>'; 
// if($i<8)
// {
//     for($j=$i-1;$j<=8;$j++;)
//     {
//         $html .='<tr><td> 1</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>'; 
//     }        
// }
$i=1;
foreach ($comm->executesql($sql) as $row)
{
    if($row['Gender']=='Female' && $row['wag'] ==1)
    {
        if($i==1)
        {
            $html .='<tr><td rowspan="7" style="text-align: center;"> Gymnasts(MAG)</td><td style="width:20px;"> '.$i.'</td><td style="width:150px;"> '. strtoupper($row['name']) . '</td><td style="width:100px;"> '. strtoupper($row['lastname']) . '</td><td> '. $row['aadhaar'] . '</td><td> '.date("d/m/Y", strtotime($row['dob'])). '</td></tr>';
            $i++;
        }
        else
        {
            $html .='<tr><td> '.$i.'</td><td style="width:150px;"> '. strtoupper($row['name']) . '</td><td style="width:100px;"> '. strtoupper($row['lastname']) . '</td><td> '. $row['aadhaar'] . '</td><td> '. date("d/m/Y", strtotime($row['dob'])) . '</td></tr>';
            $i++;
        }        
    }   
}

$html .='<tr><td> 6</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>';
$html .='<tr><td> 7</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>';

$html .='<tr><td style="text-align: center;">Coach (MAG)</td><td> 1</td><td style="width:150px;"> ARDHENDU</td><td style="width:100px;"> DAS</td><td></td><td></td></tr>';
$html .='<tr><td style="text-align: center;">Coach (WAG)</td><td> 1</td><td style="width:150px;"> ANKITA</td><td style="width:100px;"> </td><td></td><td></td></tr>';
$html .='<tr><td style="text-align: center;">Manager (MAG)</td><td> 1</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>';
$html .='<tr><td style="text-align: center;">Manager (WAG)</td><td> 1</td><td style="width:150px;"> </td><td style="width:100px;"> </td><td></td><td></td></tr>';
$html .='</table>';
$html .='<table border="0" width="600" style="align-content: center;"  cellspacing="0" cellpadding="0"><tbody>';
$html .='<tr><td colspan="3">&nbsp;</td></tr>';
$html .='<tr><td colspan="3">Certified that the above information is correct and in accordance with our records.</td></tr>';
$html .='<tr><td colspan="3">Please use same format under 10 MAG and WAG.</td></tr>';
$html .='<tr><td colspan="3">&nbsp;</td></tr>';
$html .='<tr><td colspan="3">&nbsp;</td></tr>';
$html .='<tr><td>Hon. Secretary</td><td>Seal of the Units/Association</td><td align="center">President</td></tr>';
$html .='<tr><td colspan="3"></td></tr>';
$html .='</tbody>';
$html .='</table>';
$html .='</td></tr></table>';
//echo $html;
$pdf->writeHTML($html, true, 0, true, 0); 
// ------------------Till Here---------------------------------------  
// reset pointer to the last page
$pdf->lastPage();
$pdf->Output('artistic1.pdf', 'I');
 ?>