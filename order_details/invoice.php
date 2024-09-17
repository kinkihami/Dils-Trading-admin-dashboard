<?php
require ('../fpdf/fpdf.php'); // Assuming you're using FPDF


$order_id = $_GET['id']; // Assuming you're passing the order ID via GET

// Fetch order details from the database
$conn = mysqli_connect('localhost', 'root', '', 'dils_trading');

$query = "SELECT d.owner, d.shopname, d.phonenumber, d.address, p.productname, p.image, pv.price, pv.size, o.id, o.orderdate, o.totalamount, o.status, od.quantity FROM order_details as od INNER JOIN products as p ON p.id=od.productid INNER JOIN orders as o ON o.id=od.orderid INNER JOIN dealers as d ON d.username=o.username INNER JOIN variants as pv ON pv.id=od.variantid WHERE od.orderid = '{$order_id}'";
$result = mysqli_query($conn, $query);
$invoice = mysqli_fetch_array($result);


// Create a new PDF document
$pdf = new FPDF('P', 'mm', 'A4'); // Orientation, units, page size
$pdf->AddFont('DejaVu', '', 'DejaVuSans.php');
$pdf->AddFont('TimesNewRoman', '', 'timesnewroman.php');
$pdf->AddFont('TimesNewRomanB', '', 'times new roman bold.php');
$pdf->AddPage();

// Set font for headers
$pdf->SetFont('TimesNewRomanB', '', 20);

// Add header information
$pdf->Cell(0, 10, 'Estimate', 0, 1, 'C');

$pdf->Cell(0, 25, 'DS ESTIMATE', 1, 1, 'L');
$pdf->SetFont('TimesNewRomanB', '', 10);
$pdf->SetFillColor(240,240,240);
$pdf->Cell(95, 7, 'Bill To:', 1, 0,'L',true);
$pdf->Cell(95, 7, 'Invoice Details:', 1, 1,'L',true);
$pdf->SetFont('TimesNewRoman', '', 10);
$owner = ucfirst($invoice['owner']);
$pdf->MultiCell(95, 7, "{$owner}\nContact No: {$invoice['phonenumber']}", 1, 'L');

// Move the cursor back to the right of the first cell
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x + 95, $y - 21); // Adjust the Y position as needed

// Create the Date cell at the top left of the second column
$pdf->Cell(95, 21, "Date: {$invoice['orderdate']}", 1, 1);

// ... Add other header details
$pdf->Cell(190, 5, '', 0, 1); //empty field for getting vertical space


// Set font for table headers
$pdf->SetFont('TimesNewRomanB', '', 10);
$pdf->Cell(10, 7, '#', 1, 0, 'C', true);
$pdf->Cell(80, 7, 'Item name', 1, 0, 'L', true);
$pdf->Cell(20, 7, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Unit', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Price/Unit', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Amount', 1, 1, 'C', true);


// Set font for table data
$pdf->SetFont('TimesNewRoman', '', 10);
$query = "SELECT d.owner, d.shopname, d.phonenumber, d.address, p.productname, p.image, pv.price, pv.size, o.id, u.unit, o.orderdate, o.totalamount, o.status, od.quantity FROM order_details as od INNER JOIN products as p ON p.id=od.productid INNER JOIN orders as o ON o.id=od.orderid INNER JOIN unit as u ON u.id=p.unitid INNER JOIN dealers as d ON d.username=o.username INNER JOIN variants as pv ON pv.id=od.variantid WHERE od.orderid = '{$order_id}'";
$result = mysqli_query($conn, $query);

$totalamount = 0;
$index=1;
while ($row = mysqli_fetch_array($result)) {
    $pdf->Cell(10, 7, $index, 1, 0, 'C');
    $pdf->Cell(80, 7, $row['productname'], 1, 0, 'L');
    $pdf->Cell(20, 7, $row['quantity'], 1, 0, 'C');
    $pdf->Cell(20, 7, $row['unit'], 1, 0, 'C');
    $pdf->Cell(30, 7, number_format($row['price'], 2), 1, 0, 'C');
    $pdf->Cell(30, 7, number_format($row['price'] * $row['quantity'], 2), 1, 1, 'R');

    $totalamount += $row['price'] * $row['quantity'];
    $index++;
}


function convertNumber($num = false)
{
    $num = str_replace(array(',', ''), '', trim($num));
    if (!$num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array(
        '',
        ucfirst('one'),
        ucfirst('two'),
        ucfirst('three'),
        ucfirst('four'),
        ucfirst('five'),
        ucfirst('six'),
        ucfirst('seven'),
        ucfirst('eight'),
        ucfirst('nine'),
        ucfirst('ten'),
        ucfirst('eleven'),
        ucfirst('twelve'),
        ucfirst('thirteen'),
        ucfirst('fourteen'),
        ucfirst('fifteen'),
        ucfirst('sixteen'),
        ucfirst('seventeen'),
        ucfirst('eighteen'),
        ucfirst('nineteen')
    );
    
$list2 = array(
    '', 
    ucfirst('ten'), 
    ucfirst('twenty'), 
    ucfirst('thirty'), 
    ucfirst('forty'), 
    ucfirst('fifty'), 
    ucfirst('sixty'), 
    ucfirst('seventy'), 
    ucfirst('eighty'), 
    ucfirst('ninety'), 
    ucfirst('hundred')
);
$list3 = array(
    '', 
    ucfirst('thousand'), 
    ucfirst('million'), 
    ucfirst('billion'), 
    ucfirst('trillion'), 
    ucfirst('quadrillion'), 
    ucfirst('quintillion'), 
    ucfirst('sextillion'), 
    ucfirst('septillion'),
    ucfirst('octillion'), 
    ucfirst('nonillion'), 
    ucfirst('decillion'), 
    ucfirst('undecillion'), 
    ucfirst('duodecillion'), 
    ucfirst('tredecillion'), 
    ucfirst('quattuordecillion'),
    ucfirst('quindecillion'), 
    ucfirst('sexdecillion'), 
    ucfirst('septendecillion'), 
    ucfirst('octodecillion'), 
    ucfirst('novemdecillion'), 
    ucfirst('vigintillion')
);
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? '' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . '' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ($tens < 20) {
            $tens = ($tens ? ' and ' . $list1[$tens] . '' : '');
        } elseif ($tens >= 20) {
            $tens = (int) ($tens / 10);
            $tens = ' and ' . $list2[$tens] . '';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . '';
        }
        $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    $words = implode('', $words);
    $words = preg_replace('/^\s\b(and)/', '', $words);
    $words = trim($words);
    $words = ucfirst($words);
    $words = $words . " Rupees Only";
    return $words;
}





$pdf->SetFont('TimesNewRomanB', '', 10);
$pdf->Cell(160, 7, 'Total', 1, 0, 'R');
$pdf->Cell(30, 7, number_format($totalamount, 2), 1, 1, 'R');

$amountInWords = convertNumber($totalamount);
$pdf->Multicell(190, 7, "Total Amount ({$amountInWords})", 1, 'C');

$pdf->Cell(190, 5, '', 0, 1);
$pdf->SetFont('TimesNewRomanB', '', 10);
$pdf->Cell(190, 7, 'Terms and Conditions', 1, 1, 'L',true);
$pdf->SetFont('TimesNewRoman', '', 10);
$pdf->Cell(190, 7, 'Thanks for doing business with us!', 1, 1);

// Add subtotals, totals, and other footer information

// $pdf->Output('estimate.pdf', 'D'); // Generate and download the PDF

$pdf->Output();

?>