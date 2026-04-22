<?php
include('inc/dbconfig.php');
include_once('inc/common.php');
include('inc/constants.php');
include('inc/Participant.php');

$comm = new Common();
$participant = new Participant();
$id=$_GET['id'];
       


//get participants 
require_once('assets/TCPDF-master/tcpdf.php');
$sql="SELECT * FROM `aer` WHERE `id` = '$id'";
$data= $comm->executesql($sql);
$pdata=$data[0];

 $name = $pdata['name'];
 $lastname = $pdata['lastname']; 
        $policyno = $pdata['policyno'];
        //$event = $pdata['event'];      
        $dob = $pdata['dob'];
        $aadhaar = $pdata['aadhaar'];
        $Gender = $pdata['Gender'];
        $policyno = $pdata['policyno'];
        $policycompany = $pdata['policycompany'];
        $role = $pdata['role'];
        $height = $pdata['height'];
        $weight = $pdata['weight'];
        $bloodgroup = $pdata['bloodgroup'];
        $costumesize = $pdata['costumesize'];
        $tshirtsize = $pdata['tshirtsize'];
        $tracksuit = $pdata['tracksuit'];
        $shoessize = $pdata['shoessize'];
        $address = $pdata['address'];
        $Bankname = $pdata['Bankname'];
        $Branch = $pdata['Branch'];
        $accountno = $pdata['accountno'];
        $parent = $pdata['parent'];
         $ifsccode = $pdata['ifsccode'];
         $email = $pdata['email'];
    $phone = $pdata['phone'];
     $photo = $pdata['photo'];
 
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

$pdf->seteventname('Gymnasts Association of Karnataka');
$pdf->setaddress('Affilited to GFI,KOA & dept of Youth Empowerment of Sports & Sports Authority of Karnataka');

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RnRFit');
$pdf->SetTitle('EntryForm');
$pdf->SetSubject('EntryForm');
$pdf->SetKeywords('EntryForm');

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


$html ='<style> td{height:25px;}</style>';
$html .='<table width="900" border="0" style="padding:0px;">
<tr><td>';
$html .='<table border="0" width="600" style="text-align: center;"  cellspacing="0" cellpadding="0"><tbody>';
$html .='<tr><td >State Aerobics Gymnasts Selection 2022</td></tr>';  
$html .='<tr><td >Bengaluru,Sunday 20th March 2022</td></tr>
            <tr>
                <td style="text-align:left;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td>To</td>
                                <td style="text-align:right;" rowspan="4"><img src="'.$photo.'" height="100px" /></td>
                            </tr>
                             <tr>
                                <td>The Honourable General Secretary</td>
                            </tr>
                            <tr>
                                <td>Gymnast Association of Karnataka</td>
                            </tr>
                            <tr>
                                <td>Bengaluru.</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>';
$html .='</tbody></table></td></tr>';
$html .='<tr><td>';  
$html .='<table border="0" width="600" style="align-content: center;"  cellspacing="0" cellpadding="0"><tbody>';
$html .='<tr><td colspan="4">&nbsp;</td></tr>';
$html .='<tr><td colspan="4"><strong>Details of Gymnast</strong></td></tr>';
$html .='<tr><td colspan="1">Name of Gymnast</td><td colspan="3" style="float:left;">'.$name.'  '.$lastname.'</td></tr>';
$html .='<tr><td>Date of Birth</td><td style="float:left;">'.$dob.'</td><td>Aadhar</td><td style="float:left;">'.$aadhaar.'</td></tr>';
$html .='<tr><td colspan="1">Gender</td><td colspan="3">'.$Gender.'</td></tr>';
$html .='<tr><td>Medical Policy No.</td><td style="float:left;">'.$policyno.'</td><td>Name of Company of Policy</td><td style="float:left;">'.$policycompany.'</td></tr>';
$html .='<tr><td>Role</td><td style="float:left;">Gymnast</td><td>Blood Group</td><td style="float:left;">'.$bloodgroup.'</td></tr>';
$html .='<tr><td>Height</td><td style="float:left;">'.$height.'</td><td>Weight</td><td style="float:left;">'.$weight.'</td></tr>';
$html .='<tr><td colspan="1">Discipline</td><td colspan="3">Aerobic Gymnastics All age group</td></tr>';
$html .='<tr><td>Costume size</td><td style="float:left;">'.$costumesize.'</td><td>Tshirt size</td><td style="float:left;">'.$tshirtsize.'</td></tr>';
$html .='<tr><td>Track suit</td><td style="float:left;">'.$tracksuit.'</td><td>Shoes size</td><td style="float:left;">'.$shoessize.'</td></tr>';
$html .='<tr><td colspan="1">Address</td><td colspan="3">'.$address.'</td></tr>';
$html .='<tr><td>Email</td><td style="float:left;">'.$email.'</td><td>Mobile No.</td><td style="float:left;">'.$phone.'</td></tr>';
$html .='<tr><td>Bank name</td><td style="float:left;">'.$Bankname.'</td><td>Branch</td><td style="float:left;">'.$Branch.'</td></tr>';
$html .='<tr><td>Account no</td><td style="float:left;">'.$accountno.'</td><td>IFSC code</td><td style="float:left;">'.$ifsccode.'</td></tr>';
$html .='<tr><td colspan="4">&nbsp;</td></tr>';
$html .='<tr><td colspan="4" style="align-content: center;"><strong>Details of the section trial at which participation is requested</strong></td></tr>';
$html .='<tr><td colspan="4">&nbsp;</td></tr>';
$html .='<tr><td>Venue/City</td><td style="float:left;">Bengaluru</td><td>Date</td><td style="float:left;">20th March 2022</td></tr>';
$html .='<tr><td colspan="2">Accompanying the Gymnast</td><td colspan="2">'.$parent.'</td></tr>';
$html .='<tr><td colspan="4"><strong>Undertaking:</strong> As a bonafide gymnast /sport person / officially actively participating and engaged in the sport of gymnastics under Manager/Coach/President / Gen. Secretary/HOD. I am willing to participate in the State level Championship and selection trails described above. I will be responsible in case physical injury/accident occurred during gymnastics competition.</td></tr>';
$html .='<tr><td colspan="4">&nbsp;</td></tr>';
$html .='<tr><td colspan="4">&nbsp;</td></tr>';
$html .='<tr><td colspan="2">&nbsp;</td><td>Signaturel Seal / District</td><td></td></tr>';
$html .='<tr><td colspan="2">&nbsp;</td><td colspan="2">Parent / Guardian / Manager / Coach / President /Gen. Secretary/HOD</td></tr>';
$html .='<tr><td>Date</td><td colspan="3">&nbsp;</td></tr>';
$html .='<tr><td>Place</td><td>&nbsp;</td><td colspan="2">Signature of Gymnast</td></tr>';
$html .='</tbody>';
$html .='</table>';
$html .='</td></tr></table>';
//echo $html;
$pdf->writeHTML($html, true, 0, true, 0); 
// ------------------Till Here---------------------------------------  
// reset pointer to the last page
$pdf->lastPage();
$pdf->Output('scoresheet.pdf', 'I');
 ?>