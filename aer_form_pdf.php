<?php
include('inc/dbconfig.php');
include_once('inc/common.php');
include('inc/constants.php');
include('inc/Participant.php');

$comm = new Common();
$participant = new Participant();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid id');
}

$data = $participant->get_participant_byid_aer($id);
if (empty($data)) {
    die('Record not found');
}

$pdata = $data[0];

$name        = $pdata['name'];
$lastname    = $pdata['lastname'];
$policyno    = $pdata['policyno'];
$dob         = $pdata['dob'];
$aadhaar     = $pdata['aadhaar'];
$Gender      = $pdata['Gender'];
$policycompany = $pdata['policycompany'];
$height      = $pdata['height'];
$weight      = $pdata['weight'];
$bloodgroup  = $pdata['bloodgroup'];
$costumesize = $pdata['costumesize'];
$tshirtsize  = $pdata['tshirtsize'];
$tracksuit   = $pdata['tracksuit'];
$shoessize   = $pdata['shoessize'];
$address     = $pdata['address'];
$Bankname    = $pdata['Bankname'];
$Branch      = $pdata['Branch'];
$accountno   = $pdata['accountno'];
$parent      = $pdata['parent'];   // Coach name & phone
$ifsccode    = $pdata['ifsccode'];
$email       = $pdata['email'];
$phone       = $pdata['phone'];
$photo       = $pdata['photo'];
$fathername  = !empty($pdata['fathername']) ? $pdata['fathername'] : '';
$mothername  = !empty($pdata['mothername']) ? $pdata['mothername'] : '';
$gfilicence  = !empty($pdata['gfilicence']) ? $pdata['gfilicence'] : '';
$agegroup    = !empty($pdata['agegroup'])   ? $pdata['agegroup']   : ''; // e.g. 'MAG Senior'
$dist        = !empty($pdata['dist'])       ? $pdata['dist']       : '';
$scst        = !empty($pdata['scst'])       ? $pdata['scst']       : '';

require_once('assets/TCPDF-master/tcpdf.php');

// ──────────────────────────────────────────────────────────────────────
// Custom PDF class — suppresses default header/footer so we draw our own
// ──────────────────────────────────────────────────────────────────────
class MYPDF extends TCPDF {
    public function Header() { /* custom header drawn in body */ }
    public function Footer() { /* no footer needed for single-page form */ }
}

// ──────────────────────────────────────────────────────────────────────
// Instantiate & configure
// ──────────────────────────────────────────────────────────────────────
$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GAK');
$pdf->SetTitle('GAK Artistic Gymnastics Entry Form');
$pdf->SetSubject('Entry Form');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(true, 5);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('helvetica', '', 9);
$pdf->AddPage();

// Page width for reference (A4 = 210mm, margins 10 each → 190mm usable)
$pageW = 190;

// ══════════════════════════════════════════════════════════════════════
// SECTION 1 — HEADER  (logos + title)
// ══════════════════════════════════════════════════════════════════════

// Left logo (GAK)
$gakLogo = 'assets/images/gak.jpg';
if (file_exists($gakLogo)) {
    $pdf->Image($gakLogo, 10, 10, 18, 18, 'JPG');
}

// Right logo (GFI / KOA etc.) — adjust path as needed
$gfiLogo = 'assets/images/gfi.jpg';
if (file_exists($gfiLogo)) {
    $pdf->Image($gfiLogo, 182, 10, 18, 18, 'JPG');
}

// Centre logos row (KOA medal, GFI ring logo, etc.) — combined centre image if available
$centreLogos = 'assets/images/logos_centre.png';
if (file_exists($centreLogos)) {
    $pdf->Image($centreLogos, 60, 10, 80, 18, 'PNG');
}

// Kannada title
// $pdf->SetXY(10, 29);
// $pdf->SetFont('helvetica', 'B', 10);
// $pdf->Cell($pageW, 5,
//     "\xE0\xB2\x9C\xE0\xB2\xBF\xE0\xB2\xAE\xE0\xB3\x8D\xE0\xB2\xA8\xE0\xB2\xBE\xE0\xB2\xB8\xE0\xB3\x8D\xE0\xB2\x9F\xE0\xB3\x8D " .
//     "\xE0\xB2\x85\xE0\xB2\xB8\xE0\xB3\x8B\xE0\xB2\xB8\xE0\xB2\xBF\xE0\xB2\xAF\xE0\xB3\x87\xE0\xB2\xB7\xE0\xB2\xA8\xE0\xB3\x8D " .
//     "\xE0\xB2\x86\xE0\xB2\xAB\xE0\xB3\x8D " .
//     "\xE0\xB2\x95\xE0\xB2\xA8\xE0\xB2\xBE\xE0\xB2\xB0\xE0\xB3\x8D\xE0\xB2\xA8\xE0\xB2\xBE\xE0\xB2\x9F\xE0\xB2\x95 (\xE0\xB2\xB0\xE0\xB2\xBF)",
//     0, false, 'C');

// English title (bold large)
$pdf->SetXY(10, 24);
$pdf->SetFont('helvetica', 'B', 13);
$pdf->Cell($pageW, 7, 'Gymnasts Association of Karnataka (R.)', 0, false, 'C');

// Affiliation line 1
$pdf->SetXY(10, 31);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell($pageW, 4,
    '(Affiliated to Department of Youth Empowerment & Sports, & Sports Authority of Karnataka', 0, false, 'C');

// Affiliation line 2
$pdf->SetXY(10, 35);
$pdf->Cell($pageW, 4,
    'Karnataka Olympic Association and Gymnastic Federation of India)', 0, false, 'C');

// Divider
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 40, 200, 40);

// Championship title
$pdf->SetXY(10, 41);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($pageW, 6, 'JUNIOR & SENIOR  ARTISTIC GYMNASTICS STATE LEVEL CHAMPIONSHIP  2026', 0, false, 'C');

// Date and Venue on same line
$pdf->SetXY(10, 47);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell($pageW / 2, 5, 'DATE: 02nd SR. and 03rd JR. April 2026', 0, false, 'L');
$pdf->SetX(105);
$pdf->Cell($pageW / 2, 5, 'VENUE - CHAMUNDI VIHAR STADIUM, MYSORE', 0, false, 'R');

// $pdf->SetLineWidth(0.3);
// $pdf->Line(10, 63, 200, 63);

// ══════════════════════════════════════════════════════════════════════
// SECTION 2 — AGE GROUP TABLE (left) + PHOTO BOX (right)
// ══════════════════════════════════════════════════════════════════════
$pdf->SetXY(10, 45);

// Age group table HTML
$ageGroups = [
    ['MAG Senior', '2008 before'],
    ['MAG Junior', '2008 or 2011'],
    ['WAG Senior', '2010 or before'],
    ['WAG Junior', '2011 to 2012'],
];

$agHtml  = '<style>td{font-size:8pt;padding:1px 3px;} th{font-size:8pt;padding:1px 3px;font-weight:bold;}</style>';
$agHtml .= '<table border="1" cellspacing="0" cellpadding="2" style="width:280px;">';
$agHtml .= '<tr><th>Age Group</th><th>Year Born</th><th>&nbsp;&nbsp;&nbsp;</th></tr>';
foreach ($ageGroups as $ag) {
    $checked = ($agegroup === $ag[0]) ? '&#10003;' : '';
    $agHtml .= '<tr><td>' . $ag[0] . '</td><td>' . $ag[1] . '</td><td align="center">' . $checked . '</td></tr>';
}
$agHtml .= '</table>';

$pdf->writeHTMLCell(95, 30, 10, 55, $agHtml, 0, 0);

// Photo box (right side)
$photoX = 155;
$photoY = 55;
$photoW = 45;
$photoH = 35;

if (!empty($photo) && file_exists($photo)) {
    $pdf->Image($photo, $photoX, $photoY, $photoW, $photoH);
    $pdf->Rect($photoX, $photoY, $photoW, $photoH);
} else {
    $pdf->Rect($photoX, $photoY, $photoW, $photoH);
    // Inner instruction text
    $pdf->SetXY($photoX, $photoY + 2);
    $pdf->SetFont('helvetica', '', 6.5);
    $photoInstructions = '<table width="' . ($photoW * 2.8) . '"><tr><td align="center">'
        . 'Latest photo<br/>name with date of birth<br/>Gymnast<br/>should be print<br/>on passport<br/>size photo'
        . '</td></tr></table>';
    $pdf->writeHTMLCell($photoW, $photoH, $photoX + 1, $photoY + 2, $photoInstructions, 0, 0);
}

// ══════════════════════════════════════════════════════════════════════
// SECTION 3 — TO: block
// ══════════════════════════════════════════════════════════════════════
$yAfterAgePhoto = 80;
$pdf->SetXY(10, $yAfterAgePhoto);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(0, 4, 'To:', 0, 1, 'L');
$pdf->SetX(10);
$pdf->Cell(0, 4, "The Ho'ble General Secretary,", 0, 1, 'L');
$pdf->SetX(10);
$pdf->Cell(0, 4, 'Gymnasts  Association of Karnataka', 0, 1, 'L');

// ══════════════════════════════════════════════════════════════════════
// SECTION 4 — DETAILS OF GYMNAST heading + Dist field
// ══════════════════════════════════════════════════════════════════════
$yDetails = $yAfterAgePhoto + 16;
$pdf->SetXY(10, $yDetails);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(55, 5, 'Details of Gymnast', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(30, 5, '(Fill in the Capital letters)', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(40, 5, 'Dist', 0, 0, 'R');
// dotted line for dist
$pdf->SetLineStyle(['dash' => '1,1']);
$pdf->Line($pdf->GetX(), $yDetails + 4, 180, $yDetails + 4);
$pdf->SetLineStyle(['dash' => 0]);

// Helper: draws a labelled row with a bottom-bordered value cell
// Resets line style to solid before each call
$rowH  = 7;   // row height
$labelW = 38; // label column width
$valueW = $pageW - $labelW; // value column width

// Draw a single full-width labelled row
function drawRow($pdf, &$y, $label, $value, $pageW = 190, $rowH = 7, $labelW = 38) {
    $pdf->SetLineStyle(['dash' => 0]);
    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->Cell($labelW, $rowH, $label, 'B', 0, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell($pageW - $labelW, $rowH, $value, 'B', 1, 'L');
    $y += $rowH;
}

// Draw a two-column row (two label+value pairs side by side)
function drawRowDual($pdf, &$y, $lbl1, $val1, $lbl2, $val2, $pageW = 190, $rowH = 7, $lbl1W = 30, $val1W = 55, $lbl2W = 38) {
    $pdf->SetLineStyle(['dash' => 0]);
    $val2W = $pageW - $lbl1W - $val1W - $lbl2W;
    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->Cell($lbl1W, $rowH, $lbl1, 'B', 0, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell($val1W, $rowH, $val1, 'B', 0, 'L');
    $pdf->SetFont('helvetica', '', 8.5);
    $pdf->Cell($lbl2W, $rowH, $lbl2, 'B', 0, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell($val2W, $rowH, $val2, 'B', 1, 'L');
    $y += $rowH;
}

$y = $yDetails + 7;
$pdf->SetLineWidth(0.1);
// ── Name of the Gymnast ──────────────────────────────────────────────
drawRow($pdf, $y, 'Name of the Gymnast :', $name . '  ' . $lastname);

// ── Father Name ──────────────────────────────────────────────────────
drawRow($pdf, $y, 'Father Name :', $fathername);

// ── Mother Name ──────────────────────────────────────────────────────
drawRow($pdf, $y, 'Mother Name :', $mothername);

// ── DOB + Aadhaar ────────────────────────────────────────────────────
$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(28, $rowH, 'Date of Birth :', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(42, $rowH, $dob, 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(55, $rowH, 'Aaadhar No:', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW - 28 - 42 - 55, $rowH, $aadhaar, 'B', 1, 'L');
$y += $rowH;

// ── Address ──────────────────────────────────────────────────────────
// ── Address (2 rows: label row, then value row with bottom border) ────
$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell($pageW, $rowH, 'Address :', 0, 1, 'L');
$y += $rowH;

$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW, $rowH, $address, 'B', 1, 'L');
$y += $rowH;

// ── Email ────────────────────────────────────────────────────────────
drawRow($pdf, $y, 'E-Mail :', $email);

// ── Mobile + GFI Licence ─────────────────────────────────────────────
drawRowDual($pdf, $y, 'Mobile No. :', $phone, 'GFI Licence No. :', $gfilicence, 190, $rowH, 28, 57, 38);

// ── Gender + Medical Policy + Company ───────────────────────────────
$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(37, $rowH, 'Gender : Male / Female', 'B', 0, 'L');
$pdf->Cell(33, $rowH, 'Medical Policy No:', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(30, $rowH, $policyno, 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(50, $rowH, 'Name of the company of Policy:', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW - 37 - 33 - 30 - 50, $rowH, $policycompany, 'B', 1, 'L');
$y += $rowH;

// ── SC/ST note ───────────────────────────────────────────────────────
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell($pageW, $rowH - 1, 'SC / ST enclose colour copy of certificate', 0, 1, 'L');
$y += ($rowH - 1);

// ── Height + Weight + Blood Group ────────────────────────────────────
$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, $rowH, 'Gymnast : Height', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(30, $rowH, $height, 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(18, $rowH, 'Weight', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(30, $rowH, $weight, 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, $rowH, 'Blood Group', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW - 22 - 30 - 18 - 30 - 25, $rowH, $bloodgroup, 'B', 1, 'L');
$y += $rowH;

// ── Bank Details heading ──────────────────────────────────────────────
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($pageW, $rowH - 1, 'Only Gymnast / Participant Bank Details to be provided', 1, 1, 'C');
$y += ($rowH - 1);

// ── Bank Name + Branch ────────────────────────────────────────────────
$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, $rowH, 'Bank Name', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(60, $rowH, $Bankname, 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(20, $rowH, 'Branch', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW - 25 - 60 - 20, $rowH, $Branch, 'B', 1, 'L');
$y += $rowH;

// ── A/c No + IFSC Code ────────────────────────────────────────────────
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, $rowH, 'A/c No.', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(60, $rowH, $accountno, 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(20, $rowH, 'IFSC Code', 'B', 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell($pageW - 25 - 60 - 20, $rowH, $ifsccode, 'B', 1, 'L');
$y += $rowH;

// ── Costume / T-Shirt / Track suit / Shoes Size ───────────────────────
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$sizeLabels = ['Gymnast Costume Size', 'T-Shirt Size', 'Track suit Size', 'Shoes Size'];
$sizeValues = [$costumesize, $tshirtsize, $tracksuit, $shoessize];
$colW = $pageW / 4;
foreach ($sizeLabels as $i => $lbl) {
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell($colW, $rowH - 2, $lbl, 0, 0, 'C');
}
$pdf->Ln($rowH - 2);
$pdf->SetX(10);
foreach ($sizeValues as $val) {
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell($colW, $rowH, $val, 1, 0, 'C');
}
$pdf->Ln($rowH);
$y += ($rowH - 2) + $rowH;

// ── Accompanying Coach ───────────────────────────────────────────────
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell($pageW, $rowH, 'Accompanying Gymnast the Name of the Coach & Phone No. :  ' . $parent, 'B', 1, 'L');
$y += $rowH + 2;

// ══════════════════════════════════════════════════════════════════════
// SECTION 5 — UNDERTAKING
// ══════════════════════════════════════════════════════════════════════
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(22, 5, 'Undertaking:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->MultiCell($pageW - 22, 5,
    'As a bonafide gymnast /sport person / officially actively participating and engaged in the sport of gymnastics under Manager/'
    . 'Coach/President/Gen.Secretary/HOD. I am willing to participate in the State level Championship and selection trails described '
    . 'above. I will be responsible in case physical injury/accident occurred during gymnastics competition.',
    0, 'L', false, 1, 32, $y);
$y = $pdf->GetY() + 3;

// ══════════════════════════════════════════════════════════════════════
// SECTION 6 — LEFT THUMB IMPRESSION BOX + SIGNATURES
// ══════════════════════════════════════════════════════════════════════
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->SetXY(10, $y);
$pdf->Cell($pageW, 5, 'Left Hand Thumb Impression Gymnast', 0, 1, 'L');
$y += 5;

$thumbX = 10;
$thumbY = $y;
$thumbW = 60;
$thumbH = 20;
$pdf->Rect($thumbX, $thumbY, $thumbW, $thumbH);

// Right side: Signature / Seal / Dist. / Association
$pdf->SetXY(100, $thumbY + 5);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(100, 5, 'Signature / Seal / Dist. / Association', 0, 1, 'R');

$pdf->SetXY(100, $thumbY + 13);
$pdf->Cell(100, 5, 'President /Gen.Secretary', 0, 1, 'R');

$y = $thumbY + $thumbH + 4;

// ── Date / Place / Gymnast Signature ────────────────────────────────
$pdf->SetXY(10, $y);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(15, 6, 'Date :', 0, 0, 'L');
$pdf->SetLineStyle(['dash' => '1,1']);
$pdf->Line($pdf->GetX(), $y + 5, 105, $y + 5);

$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetX(110);
$pdf->Cell(40, 6, 'Signature of the Gymnast', 0, 0, 'L');
$pdf->SetLineStyle(['dash' => '1,1']);
$pdf->Line($pdf->GetX(), $y + 5, 200, $y + 5);

$y += 7;
$pdf->SetLineStyle(['dash' => 0]);
$pdf->SetXY(10, $y);
$pdf->Cell(15, 6, 'Place :', 0, 0, 'L');
$pdf->SetLineStyle(['dash' => '1,1']);
$pdf->Line($pdf->GetX(), $y + 5, 105, $y + 5);

// ══════════════════════════════════════════════════════════════════════
// Output
// ══════════════════════════════════════════════════════════════════════
$pdf->lastPage();
$pdf->Output('GAK-Reg-Form-' . $id . '.pdf', 'I');