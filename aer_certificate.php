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
        $dob = $pdata['dob'];     
        $parent = $pdata['parent'];   
        $grade = $pdata['grade'];      
        $school = $pdata['school'];   
  $photo = $pdata['photo'];
   $gender = $pdata['Gender'];
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
                $image_file = 'assets/images/logo.png';
                $this->Image($image_file, 20, 10, 15, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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

$pdf->seteventname('GYMNASTICS FEDERATION OF INDIA');
$pdf->setaddress('16th Aerobic Gymnastics National Championships (All Age Group)');
$pdf->setevdate('from 26 th -27th March, 2022 at Bangalore-KARNATAKA');
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

$html .='<table width="600" border="0" style="padding:0px;">';
$html .='<tbody>
            <tr>
                <td><hr></td>
            </tr>
             <tr>
                <td style="text-align:center;">AGE CERTIFICATE</td>
            </tr>
            <tr>
                <td style="width:100%" border="0">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td>To
                                </td>
                                <td style="text-align:right;" rowspan="4"><img src="'.$photo.'" height="100px" /></td>
                            </tr>
                             <tr>
                                <td>President/Secretary
                                </td>
                            </tr>
                            <tr>
                                <td>Gymnastics Federation of India
                                </td>
                            </tr>
                            <tr>
                                <td>New Delhi.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>


            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>I hereby certify that Mr./Ms. …' .$name.'  '.$lastname;
                If($gender=='Male')
                {
                    $html .='…… son  ';
                }
                else
                {
                    $html .='…… daughter ';
                }
            $html .='of</td>
            </tr>
            <tr>
                <td>………………………'.$fathername.'......… studying in / working with (name of organization) ………..............</td>
            </tr>
            <tr>
                <td>'.$school.'......................'.$grade.'.................................... class
                    .</td>
            </tr>
            <tr>
                <td>';
                If($gender=='Male')
                {
                    $html .=' His ';
                }
                else
                {
                    $html .=' Her ';
                }
               
            $html .='date of birth as per the record is ......'.date("d-m-Y",strtotime($dob)).'.……............…</td>
            </tr>
            <tr>
                <td>Specimen signature &amp; left hand thumb impression are affixed in my presence. Visible
                    identification marks are given below.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="width:100%">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td>(Left Hand Thumb Impression)</td>
                                <td>(Signature of the Gymnast)</td>
                                <td>(Verified by Parent/ Guardian of Gymnast)</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Signature: …………………………………</td>
            </tr>
            <tr>
                <td>Name: ……………………………………..</td>
            </tr>
            <tr>
                <td>Seal of Gymnastics Institution/Club</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Visible Identification Marks
                    <ol>
                        <li>................................................................................</li>
                        <li>................................................................................</li>
                    </ol>
                    
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Note: 
                    <ol>
                        <li>This certificate should be signed by Head of the institution/Club.</li>
                        <li>A Gymnast should be on the role of the Institution/Club/District / State Association / Unit at least for 6 months prior to submission of this certificate.</li>
                        <li>The Seal of College/ Institution / Club / District / State Association / Unit over the photograph is
                            essential.</li>
                       
                    </ol>  </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Declaration by State Gymnastics Association/Unit:</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>I hereby declare that Mr./Ms is a
                    registered gymnast</td>
            </tr>
            <tr>
                <td>of our
                    Association/Unit &amp; the particulars that are given above are correct to the best of my knowledge
                    and I am
                    personally liable for its correctness.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
             <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: right;">Signature of District Secretary
                   </td>
            </tr>
            <tr>
                <td style="text-align: right;">Seal of the District Association</td>
            </tr>
            <tr>
                <td>Sign. of State/Unit Secretary with Seal</td>
            </tr>
            <tr>
                <td></td>
            </tr>
        </tbody>';
$html .='</table>';
//echo $html;
$pdf->writeHTML($html, true, 0, true, 0); 
// ------------------Till Here---------------------------------------  
// reset pointer to the last page
$pdf->lastPage();
$pdf->Output('scoresheet.pdf', 'I');
 ?>