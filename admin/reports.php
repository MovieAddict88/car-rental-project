<?php
require_once 'includes/header.php';

// Default filter values
$filter_month = $_GET['month'] ?? date('m');
$filter_year = $_GET['year'] ?? date('Y');
$export_type = $_GET['export'] ?? '';

$report_data = [];
$report_summary = [
    'total_bookings' => 0,
    'total_revenue' => 0,
];

// --- Data Fetching Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['month'])) {
    try {
        $query = "
            SELECT
                b.id, u.name as user_name, c.brand, c.model, b.total_price, b.created_at
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN cars c ON b.car_id = c.id
            WHERE
                b.status IN ('approved', 'completed') AND
                MONTH(b.created_at) = ? AND
                YEAR(b.created_at) = ?
            ORDER BY b.created_at DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$filter_month, $filter_year]);
        $report_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate summary
        $report_summary['total_bookings'] = count($report_data);
        $report_summary['total_revenue'] = array_sum(array_column($report_data, 'total_price'));

    } catch (PDOException $e) {
        $error = "Failed to generate report: " . $e->getMessage();
    }
}

// --- Export Logic ---
if (!empty($export_type) && !empty($report_data)) {

    // Set filename
    $filename = "report_{$filter_year}-{$filter_month}.{$export_type}";

    if ($export_type === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // Add header row
        fputcsv($output, ['Booking ID', 'User', 'Car', 'Date', 'Revenue']);

        // Add data rows
        foreach ($report_data as $row) {
            fputcsv($output, [
                $row['id'],
                $row['user_name'],
                $row['brand'] . ' ' . $row['model'],
                date('Y-m-d', strtotime($row['created_at'])),
                $row['total_price']
            ]);
        }
        fclose($output);
        exit();

    } elseif ($export_type === 'pdf') {
        require_once __DIR__ . '/../libs/fpdf.php';

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "Booking Report - {$filter_month}/{$filter_year}", 0, 1, 'C');
        $pdf->Ln(10);

        // Summary
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, "Total Bookings: " . $report_summary['total_bookings'], 0, 1);
        $pdf->Cell(0, 8, "Total Revenue: P" . number_format($report_summary['total_revenue'], 2), 0, 1);
        $pdf->Ln(5);

        // Table Header
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230,230,230);
        $pdf->Cell(30, 7, 'Booking ID', 1, 0, 'C', true);
        $pdf->Cell(50, 7, 'User', 1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Car', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Revenue', 1, 1, 'C', true);

        // Table Body
        $pdf->SetFont('Arial', '', 10);
        foreach ($report_data as $row) {
            $pdf->Cell(30, 7, $row['id'], 1);
            $pdf->Cell(50, 7, substr($row['user_name'], 0, 25), 1);
            $pdf->Cell(60, 7, substr($row['brand'] . ' ' . $row['model'], 0, 30), 1);
            $pdf->Cell(40, 7, 'P' . number_format($row['total_price'], 2), 1, 1, 'R');
        }

        $pdf->Output('D', $filename);
        exit();
    }
}

?>

<h1 class="h2">Reports</h1>
<p>Generate and export reports for bookings and revenue.</p>

<div class="card mb-4">
    <div class="card-header">
        <h5>Filter Report</h5>
    </div>
    <div class="card-body">
        <form action="reports.php" method="get" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="month" class="form-label">Month</label>
                <select name="month" id="month" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?php echo ($filter_month == $m) ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="year" class="form-label">Year</label>
                <select name="year" id="year" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?php echo $y; ?>" <?php echo ($filter_year == $y) ? 'selected' : ''; ?>>
                            <?php echo $y; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                 <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>


<?php if (isset($_GET['month']) && empty($error)): ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Report for <?php echo date('F', mktime(0,0,0,$filter_month,1)) . ' ' . $filter_year; ?></h5>
        <div>
            <a href="?month=<?php echo $filter_month; ?>&year=<?php echo $filter_year; ?>&export=csv" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
            <a href="?month=<?php echo $filter_month; ?>&year=<?php echo $filter_year; ?>&export=pdf" class="btn btn-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3 text-center">
            <div class="col-md-6">
                <div class="stat-card p-3 border rounded">
                    <h4>Total Bookings</h4>
                    <p class="fs-3 mb-0"><?php echo $report_summary['total_bookings']; ?></p>
                </div>
            </div>
             <div class="col-md-6">
                <div class="stat-card p-3 border rounded">
                    <h4>Total Revenue</h4>
                    <p class="fs-3 mb-0">₱<?php echo number_format($report_summary['total_revenue'], 2); ?></p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Car</th>
                        <th>Date</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($report_data): ?>
                        <?php foreach($report_data as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                            <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No data found for the selected period.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php elseif (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>


<?php require_once 'includes/footer.php'; ?>