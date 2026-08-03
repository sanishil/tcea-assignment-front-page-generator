<?php
// PHP CODE - Get data from database
include "../api/connect.php"; // Adjust path as needed

// Initialize variables
$total_dept = 0;
$total_std = 0;
$total_subjects = 0;
$php_error = null;

try {
   // Department count
   $sql1 = "SELECT COUNT(id) AS total_dept FROM dept";
   $result1 = $con->query($sql1);
   if ($result1 && $result1->num_rows > 0) {
      $row1 = $result1->fetch_assoc();
      $total_dept = $row1['total_dept'];
   }

   // Student count
   $sql2 = "SELECT COUNT(id) AS total_std FROM student";
   $result2 = $con->query($sql2);
   if ($result2 && $result2->num_rows > 0) {
      $row2 = $result2->fetch_assoc();
      $total_std = $row2['total_std'];
   }

   // Subjects count
   $sql3 = "SELECT COUNT(id) AS total_subjects FROM subjects";
   $result3 = $con->query($sql3);
   if ($result3 && $result3->num_rows > 0) {
      $row3 = $result3->fetch_assoc();
      $total_subjects = $row3['total_subjects'];
   }

   if ($con) {
      $con->close();
   }

} catch (Exception $e) {
   $php_error = 'Database error: ' . $e->getMessage();
}
?>
<div class="container-fluid">
   <div class="row" style="padding-top: 20px;">
      <div class="col-lg-4 mb-3">
         <div class="card shadow-sm text-dark" style="height: 200px; border-radius: 10px; background-color: #ff6666;">
            <div class="card-body">
               <h5 class="card-title"><i class="fa-solid fa-users"></i> Total Register Students</h5>
               <h3 class=""><?php echo $total_std; ?></h3>
            </div>
         </div>
      </div>
      <div class="col-lg-4 mb-3">
         <div class="card shadow-sm text-dark" style="height: 200px; border-radius: 10px;">
            <div class="card-body">
               <h5 class="card-title"><i class="fa-solid fa-book"></i> Total Subjects</h5>
               <h3 class=""><?php echo $total_subjects; ?></h3>
            </div>
         </div>
      </div>
      <div class="col-lg-4 mb-3">
         <div class="card shadow-sm bg-primary text-white" style="height: 200px; border-radius: 10px;">
            <div class="card-body">
               <h5 class="card-title"><i class="fa-solid fa-building"></i> Total Departments</h5>
               <h3 class=""><?php echo $total_dept; ?></h3>
            </div>
         </div>
      </div>
   </div>

</div>
<h2>📊 College Record Summary (Large Circular Chart)</h2>
<div class="chart-container">
   <canvas id="collegeChart"></canvas>
</div>
<script>
        // Global chart instance
        let collegeChartInstance = null;

        function renderDashboardChart() {
            const loadingElement = document.getElementById('loading');
            const errorElement = document.getElementById('error');
            const canvasElement = document.getElementById('collegeChart');
            
            // Hide loading immediately since we have PHP data
            if (loadingElement) loadingElement.style.display = 'none';
            if (errorElement) errorElement.style.display = 'none';
            if (canvasElement) canvasElement.style.display = 'block';
            
            // Get data from PHP variables
            const phpData = {
                total_dept: <?php echo $total_dept; ?>,
                total_std: <?php echo $total_std; ?>,
                total_subjects: <?php echo $total_subjects; ?>
            };

            // Check for PHP errors
            <?php if ($php_error): ?>
                showError("<?php echo $php_error; ?>");
                return;
            <?php endif; ?>

            console.log("PHP Data:", phpData);
            
            // Initialize chart with PHP data
            initializeChart(phpData);
        }

        function initializeChart(phpData) {
            const ctx = document.getElementById('collegeChart');
            const errorElement = document.getElementById('error');
            
            if (!ctx) {
                showError("Chart container not found");
                return;
            }
            
            // Destroy existing chart if it exists
            if (collegeChartInstance) {
                collegeChartInstance.destroy();
                collegeChartInstance = null;
            }
            
            try {
                const chartContext = ctx.getContext('2d');
                
                // Prepare chart data
                const chartData = [
                    parseInt(phpData.total_dept) || 0,
                    parseInt(phpData.total_std) || 0,
                    parseInt(phpData.total_subjects) || 0
                ];
                
                collegeChartInstance = new Chart(chartContext, {
                    type: 'doughnut',
                    data: {
                        labels: ['Departments', 'Students', 'Subjects'],
                        datasets: [{
                            label: 'Database Records',
                            data: chartData,
                            backgroundColor: ['#36a2eb', '#ff6384', '#ffce56'],
                            borderWidth: 2,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 16
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'College Database Overview',
                                font: {
                                    size: 20
                                }
                            },
                            tooltip: {
                                bodyFont: {
                                    size: 16
                                },
                                titleFont: {
                                    size: 16
                                }
                            }
                        }
                    }
                });
                
                console.log("Chart created successfully");
                
            } catch (chartError) {
                console.error("Error creating chart:", chartError);
                showError("Error creating chart. Please check console for details.");
            }
        }

        function showError(message) {
            const errorElement = document.getElementById('error');
            const loadingElement = document.getElementById('loading');
            const canvasElement = document.getElementById('collegeChart');
            
            if (loadingElement) loadingElement.style.display = 'none';
            if (canvasElement) canvasElement.style.display = 'none';
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
        }

        // Initialize chart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            renderDashboardChart();
        });
    </script>