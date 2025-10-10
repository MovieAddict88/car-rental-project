<?php
$page_title = "Reports";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Report Generation Logic ---
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$start_date = $selected_month . '-01';
$end_date = date("Y-m-t", strtotime($start_date));

$report_data = [
    'total_bookings' => 0,
    'total_revenue' => 0,
    'report_period' => date('F Y', strtotime($start_date)),
    'detailed_report' => []
];

// Fetch detailed report for the selected month
$stmt = $conn->prepare(
    "SELECT p.booking_id, p.amount, p.payment_date, c.brand, c.model
     FROM payments p
     JOIN bookings b ON p.booking_id = b.id
     JOIN cars c ON b.car_id = c.id
     WHERE p.status = 'completed' AND p.payment_date BETWEEN ? AND ?"
);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
$detailed_report = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$report_data['detailed_report'] = $detailed_report;
$report_data['total_bookings'] = count($detailed_report);
$report_data['total_revenue'] = array_sum(array_column($detailed_report, 'amount'));


// --- Chart Data Logic (Last 6 Months) ---
$chart_labels = [];
$chart_revenue = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $month_start = $month . '-01';
    $month_end = date('Y-m-t', strtotime($month_start));
    $chart_labels[] = date('M Y', strtotime($month_start));

    $stmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE status = 'completed' AND payment_date BETWEEN ? AND ?");
    $stmt->bind_param("ss", $month_start, $month_end);
    $stmt->execute();
    $rev_result = $stmt->get_result()->fetch_assoc();
    $chart_revenue[] = $rev_result['total'] ?? 0;
    $stmt->close();
}
$report_data['chart_data'] = [
    'labels' => $chart_labels,
    'revenue' => $chart_revenue
];

?>
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Financial Reports</h1>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Generate Report</h6>
        </div>
        <div class="card-body">
            <form class="form-inline">
                <div class="row">
                     <div class="col-md-4">
                        <label for="month" class="form-label">Select Month and Year:</label>
                        <input type="month" class="form-control" id="month" name="month" value="<?php echo $selected_month; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Report for <?php echo $report_data['report_period']; ?></h6>
            <div>
                <a href="#" class="btn btn-sm btn-success shadow-sm"><i class="fas fa-download fa-sm"></i> Export as CSV</a>
                <a href="#" class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-file-pdf fa-sm"></i> Export as PDF</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Total Bookings:</strong> <?php echo $report_data['total_bookings']; ?></p>
                    <p><strong>Total Revenue:</strong> $<?php echo number_format($report_data['total_revenue'], 2); ?></p>
                </div>
            </div>
            <hr>
            <h6 class="font-weight-bold">Detailed Transactions:</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Car</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($report_data['detailed_report'])): ?>
                            <tr><td colspan="4" class="text-center">No completed payments for this period.</td></tr>
                        <?php else: ?>
                            <?php foreach ($report_data['detailed_report'] as $row): ?>
                            <tr>
                                <td>#<?php echo $row['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($row['payment_date'])); ?></td>
                                <td>$<?php echo number_format($row['amount'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Overview (Last 6 Months)</h6>
        </div>
        <div class="card-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($report_data['chart_data']['labels']); ?>,
            datasets: [{
                label: 'Revenue ($)',
                data: <?php echo json_encode($report_data['chart_data']['revenue']); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php
require_once 'includes/footer.php';
?>