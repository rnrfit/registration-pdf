<?php
include('inc/dbconfig.php');
include_once('inc/common.php');
include('inc/constants.php');
include('inc/Participant.php');

$comm = new Common();
$participant = new Participant();

       
if (!empty($_POST))
{
$district=$_POST['district'];
$eventgroup=$_POST['eventgroup'];
//get participants 
require_once('assets/TCPDF-master/tcpdf.php');
$sql="SELECT * FROM `aer` WHERE `district` LIKE '$district' AND `eventgroup` LIKE '$eventgroup' order by eventcategory";
$data= $comm->executesql($sql);

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
                $this->SetFont('helvetica', '', 10);
                $this->Cell(0, 6, $this->address, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                $this->Ln();
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

$pdf->seteventname('GYMNAST ASSOCIATION OF KARNATAKA (Regd.)');
$pdf->setaddress('Affiliated to the Gymnastic Federation of India, Karnataka Olympic Association');
$pdf->setevdate('& DYES-Sports Authority of Karnataka');
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


$html ='<style> td{height:20px;}</style>';
$html .='<p>&nbsp;</p>';
$html .='<p>&nbsp;</p>';
$html .='<p>District: '.$district.'</p>';
$html .='<p>Group: '.$eventgroup.'</p>';
$html .='<table cellspacing="0" cellpadding="1" border="0">';
$html .='<tbody>
             <thead>
            <tr>
                <th style="font-weight: bold;">Event</th>
                <th width="30px" style="font-weight: bold;">S.No.</th>
                <th style="font-weight: bold;">Name</th>
                <th  style="font-weight: bold;">Aadhaar</th>
                <th style="font-weight: bold;">Date of Birth</th></tr></thead><tbody>';
                $i=1;
                foreach ($data as $row) {
                   $html .='<tr>
                <td>'.$row['eventcategory'].'</td>
                <td width="30px">&nbsp;</td>
                <td>'.strtoupper($row['name']). ' '.strtoupper($row['lastname']).'</td>
                <td>'.$row['aadhaar'].'</td>
                <td>'.date("d-m-Y",strtotime($row['dob'])).'</td></tr>';
                $i++;
                }
            $html .='</tbody>';
$html .='</table>';
//echo $html;
$pdf->writeHTML($html, true, 0, true, 0); 
// ------------------Till Here---------------------------------------  
// reset pointer to the last page
$pdf->lastPage();
$pdf->Output('scoresheet.pdf', 'I');
}
 ?>