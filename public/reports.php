<?php
// Place this at the top of your PHP file
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
$sessionManager->checkAccess(['Admin','Manager']);
require_once 'dB_Connection.php';

// Get system statistics
$popular_systems_query = "
    SELECT 
        suggested_system,
        COUNT(*) as installation_count,
        AVG(unit_genaration) as avg_generation,
        COUNT(*) * 100.0 / (SELECT COUNT(*) FROM calculated_data) as percentage
    FROM calculated_data 
    GROUP BY suggested_system 
    ORDER BY installation_count DESC";

$result = $conn->query($popular_systems_query);
$system_stats = [];
while($row = $result->fetch_assoc()) {
    $system_stats[] = $row;
}

// Calculate totals
$totals_query = "
    SELECT 
        COUNT(*) as total_systems,
        AVG(unit_genaration) as overall_avg_generation
    FROM calculated_data";
$totals_result = $conn->query($totals_query);
$totals = $totals_result->fetch_assoc();

error_reporting(0);          // Disable all error reporting
ini_set('display_errors', 0); 


?>

<?php 
  include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solar System Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
    body {
        background-image: url('pictures/solar-panels-roof-solar-cell.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        
    }

    /* Add these new styles */
    .content-wrapper {
        max-width: 1400px;  /* Increased max-width for better content spread */
        margin: 0 auto;
        padding: 0 1rem;
    }

    .dashboard-card {
        padding: 1rem;  /* Reduced padding */
    }

    table {
        font-size: 0.875rem;  /* Slightly smaller font size for tables */
    }

    /* Adjust chart containers */
    canvas {
        max-height: 300px !important;  /* Control chart height */
    }
</style>
</head>
<body>
    <div class="font-sans text-center tracking-wide bg-slate-900 rounded pt-6 pb-8 mt-8 font-bold align-middle"> 
        <p class="font-sans text-green-500 text-3xl align-middle">NPH Solar Solutions</p>
        <p class="font-sans text-orange-500 text-5xl align-middle">Admin Panel - System Reports</p>
    </div>

    <?php include 'admin_navbar.php'; ?>

     <!-- Dashboard Container -->
     <div class="container mx-auto px-4 py-8 bg-white mt-5 drop-shadow-md">
        <!-- System Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">Total Systems Calculated</h3>
                <p class="text-3xl font-bold text-blue-600" id="totalSystems">
                    <?php echo $totals['total_systems']; ?>
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">Average Generation</h3>
                <p class="text-3xl font-bold text-green-600" id="avgGeneration">
                    <?php echo number_format($totals['overall_avg_generation'], 2); ?> kWh
                </p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- System Size Distribution Chart -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">System Size Distribution</h3>
                <canvas id="systemDistributionChart"></canvas>
            </div>
            <!-- Average Generation Chart -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Average Generation by System Size</h3>
                <canvas id="generationChart"></canvas>
            </div>
        </div>

        <!-- Popular Systems Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <h3 class="text-lg font-semibold p-4 border-b">Popular System Sizes</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">System Size</th>
                            <th class="px-6 py-3">Count</th>
                            <th class="px-6 py-3">Percentage</th>
                            <th class="px-6 py-3">Avg Generation (kWh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($system_stats as $stat): ?>
                        <tr class="border-b">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($stat['suggested_system']); ?></td>
                            <td class="px-6 py-4"><?php echo $stat['installation_count']; ?></td>
                            <td class="px-6 py-4"><?php echo number_format($stat['percentage'], 1); ?>%</td>
                            <td class="px-6 py-4"><?php echo number_format($stat['avg_generation'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'footer.html'; ?>
    <script>
    // Initialize charts when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Get the PHP data
        const systemStats = <?php echo json_encode($system_stats); ?>;
        
        // System Distribution Chart (Pie Chart)
        const distributionCtx = document.getElementById('systemDistributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: systemStats.map(stat => stat.suggested_system),
                datasets: [{
                    data: systemStats.map(stat => stat.installation_count),
                    backgroundColor: [
                        '#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8',
                        '#82ca9d', '#8dd1e1', '#a4de6c', '#d0ed57', '#ffc658'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const stat = systemStats[context.dataIndex];
                                return `${stat.suggested_system}: ${stat.installation_count} (${stat.percentage.toFixed(1)}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Average Generation Chart (Bar Chart)
        const generationCtx = document.getElementById('generationChart').getContext('2d');
        new Chart(generationCtx, {
            type: 'bar',
            data: {
                labels: systemStats.map(stat => stat.suggested_system),
                datasets: [{
                    label: 'Average Generation (kWh)',
                    data: systemStats.map(stat => parseFloat(stat.avg_generation).toFixed(2)),
                    backgroundColor: '#00C49F',
                    borderColor: '#00B394',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average Generation (kWh)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'System Size'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    });
    </script>
</body>
</html>