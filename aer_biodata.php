<?php
include('inc/dbconfig.php');
include_once('inc/common.php');
include('inc/constants.php');
include('inc/Participant.php');

$comm = new Common();
$participant = new Participant();       
//get participants 
$id=$_GET['id'];
require_once('assets/TCPDF-master/tcpdf.php');
$sql="SELECT * FROM `aer` WHERE `id` = '$id'";
$data= $comm->executesql($sql);
$pdata=$data[0];
 $name = $pdata['name'];      
 $lastname = $pdata['lastname'];  
        $dob = $pdata['dob'];     
        $parent = $pdata['parent']; 
        $height = $pdata['height'];    
        $weight = $pdata['weight']; 
        $grade = $pdata['grade'];      
        $school = $pdata['school'];   
        $bloodgroup = $pdata['bloodgroup']; 
         $fathername = $pdata['fathername'];
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
                $image_file = 'assets/images/gak.jpg';
                $this->Image($image_file, 20, 10, 15, 15, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                 // Set font
                $this->SetFont('helvetica', 'B', 12);
                // Title
                $this->Cell(0, 8, $this->eventname, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                $this->Ln();
                $this->SetFont('helvetica',  'B', 12);
                $this->Cell(0, 8, $this->address, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                $this->Ln();
                 $this->SetFont('helvetica', '', 10);
                $this->Cell(0, 10, $this->evdate, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                //$this->writeHTML( $image_file, true, false, false, false, '');
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
            $ipath =dirname(__FILE__).$this->logo;
            // $html_content = '<table><tr><td><img src="'.$ipath.'" width="15px" height="15px" border="0" /></td><td align="center">Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().'</td><td align="right">indiangymnastics.live</td></tr></table>';   
            // $this->writeHTML($html_content, false, true, false, true);
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

$pdf->seteventname('Directorate of Youth Empowerment and Sports and');
$pdf->setaddress('Director General Sports Authority of Karnataka');
$pdf->setevdate('Bio data & Performance Record : Sportsmen / Women');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RnRFit');
$pdf->SetTitle('Certificate');
$pdf->SetSubject('Certificate');
$pdf->SetKeywords('Certificate');

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


$html ='<style> td{height:30px;}</style>';
$html .='<table cellspacing="0" cellpadding="1" border="0" width="700">';
$html .='<tbody><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
           <tr><td width="300px">Index card No. ...</td><td style="text-align:right">Game : GYMNASTICS</td></tr>
           <tr><td width="300px">Name of Gymnast .........'.$name.' '.$lastname.'</td><td>&nbsp;</td></tr>
           <tr><td>Father Name. .........'.$fathername.'</td><td>&nbsp;</td></tr>
           <tr><td>Address :</td><td>&nbsp;</td></tr>
           <tr><td>1. Permanent :...................................................... </td><td>&nbsp;</td></tr>
           <tr><td>........................................................................ </td><td>&nbsp;</td></tr>
           <tr><td>2. Temporary .................................................. </td><td>&nbsp;</td></tr>
           <tr><td>........................................................................ </td><td>&nbsp;</td></tr>
           <tr><td> </td><td>&nbsp;</td></tr>
           <tr><td colspan="2"><table border="0"><tbody>
        <tr><td>As on </td><td>Height </td><td>Weight </td><td>Chest </td></tr>
        <tr><td>.. '.date("d-m-Y").' .. </td><td>.. '.$height.' .. </td><td>.. '.$weight.' .. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td><td>.................. </td></tr>
           </tbody></table></td></tr><tr><td colspan="2">&nbsp;</td></tr>';
$html .='<tr><td width="300px">Date of Birth .........'.date("d-m-Y",strtotime($dob)).'</td><td>Place of Birth</td></tr>
<tr><td width="300px">Educational Qualifications ....'.$grade.'</td><td>&nbsp;</td></tr>
<tr><td width="300px">Profession ..................</td><td>Employer..................</td></tr>
<tr><td width="300px">Marital Status ..................</td><td>No. of Children (if married)..................</td></tr>
<tr><td width="300px">Blood Group ....'.$bloodgroup.'</td><td>Name of Coach / Mentor..................</td></tr><tr><td colspan="2">&nbsp;</td></tr>
<tr><td width="300px">Sports achievements till date (yearwise) .........</td><td>&nbsp;</td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>';
$html .='</tbody>';
$html .='</table>';

$pdf->writeHTML($html, true, 0, true, 0);
//first page ends here
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->AddPage();
$pdf->SetRightMargin(10);
$pdf->SetLeftMargin(10);
$start_page = $pdf->getPage();
$html ='<style> td{height:30px;}</style>';
$html .='<table border="0"><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2" style="text-align:center">Coaching Camp Attended</td></tr>';
$html .='<tr><td colspan="2"><table border="0"><tbody>
        <tr><td>Palce</td><td>Dates </td><td>Performance </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td></tr>
        <tr><td>.................. </td><td>.................. </td><td>.................. </td></tr>
           </tbody></table></td></tr>';
$html .='<tr><td colspan="2">Special distinction/achievements if any in International / National / State fixtures .........</td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>
<tr><td colspan="2">Any Other Information .........</td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>
<tr><td colspan="2">..................................................................................................................................................................... </td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td>........................................................................ </td><td>Left hand Thumb Impression</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr><td>Signature of Hon. Secretary of Association</td><td>Signature of Sports man/Woman</td></tr>';
            $html .='</tbody></table>';
   $pdf->writeHTML($html, true, 0, true, 0);
// ------------------Till Here---------------------------------------  
// reset pointer to the last page
$pdf->lastPage();
$pdf->Output('scoresheet.pdf', 'I');
?>