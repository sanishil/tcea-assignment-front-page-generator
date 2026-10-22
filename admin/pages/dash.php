<?php
// PHP CODE - Get data from database
include "../api/connect.php"; // Adjust path as needed

// Initialize variables
$total_dept = 0;
$total_std = 0;
$total_subjects = 0;
$total_teacher = 0;

// Department count
$sql1 = "SELECT COUNT(DISTINCT dept_name) AS total_dept FROM dept";
$result1 = $con->query($sql1);
if ($result1 && $result1->num_rows > 0) {
   $total_dept = $result1->fetch_assoc()['total_dept'];
}

// Student count
$sql2 = "SELECT COUNT(id) AS total_std FROM student";
$result2 = $con->query($sql2);
if ($result2 && $result2->num_rows > 0) {
   $total_std = $result2->fetch_assoc()['total_std'];
}

// Subjects count
$sql3 = "SELECT COUNT(id) AS total_subjects FROM subjects";
$result3 = $con->query($sql3);
if ($result3 && $result3->num_rows > 0) {
   $total_subjects = $result3->fetch_assoc()['total_subjects'];
}

// Teacher count
$sql4 = "SELECT COUNT(id) AS total_teacher FROM teacher";
$result4 = $con->query($sql4);
if ($result4 && $result4->num_rows > 0) {
   $total_teacher = $result4->fetch_assoc()['total_teacher'];
}

$con->close();
?>
<div class="container-fluid" ng-init="dept_chart();">
   <!-- ================= STAT CARDS ================= -->
   <div class="row g-3" style="padding-top: 20px;">
      <div class="col-6 col-xl-3">
         <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
               <span class="stat-icon" style="background: #fde8e8; color: #e34948;"><i class="fa-solid fa-users"></i></span>
               <div class="stat-label">Registered Students</div>
               <div class="stat-value"><?php echo $total_std; ?></div>
            </div>
         </div>
      </div>
      <div class="col-6 col-xl-3">
         <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
               <span class="stat-icon" style="background: #fdeadf; color: #d95926;"><i class="fa-solid fa-chalkboard-user"></i></span>
               <div class="stat-label">Teachers</div>
               <div class="stat-value"><?php echo $total_teacher; ?></div>
            </div>
         </div>
      </div>
      <div class="col-6 col-xl-3">
         <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
               <span class="stat-icon" style="background: #dcf3ea; color: #148a60;"><i class="fa-solid fa-book"></i></span>
               <div class="stat-label">Subjects</div>
               <div class="stat-value"><?php echo $total_subjects; ?></div>
            </div>
         </div>
      </div>
      <div class="col-6 col-xl-3">
         <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
               <span class="stat-icon" style="background: #e1ecfa; color: #2a78d6;"><i class="fa-solid fa-building"></i></span>
               <div class="stat-label">Departments</div>
               <div class="stat-value"><?php echo $total_dept; ?></div>
            </div>
         </div>
      </div>
   </div>

   <!-- ================= DEPARTMENT CHART ================= -->
   <div class="row mt-3">
      <div class="col-12 mb-3">
         <div class="card shadow-sm border-0" style="border-radius: 10px;">
            <div class="card-body">
               <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                  <div>
                     <h5 class="mb-1"><i class="fa-solid fa-chart-bar"></i> Department Overview</h5>
                     <small class="text-muted">Students, teachers and subjects in each department</small>
                  </div>
                  <div class="btn-group btn-group-sm" role="group" aria-label="Chart or table view">
                     <button type="button" class="btn" ng-class="dashView == 'table' ? 'btn-outline-secondary' : 'btn-secondary'"
                        ng-click="dashView = 'chart'"><i class="fa-solid fa-chart-bar"></i> Chart</button>
                     <button type="button" class="btn" ng-class="dashView == 'table' ? 'btn-secondary' : 'btn-outline-secondary'"
                        ng-click="dashView = 'table'"><i class="fa-solid fa-table"></i> Table</button>
                  </div>
               </div>

               <div class="text-center text-danger py-4" ng-if="deptChartError">
                  <h5>{{ deptChartError }}</h5>
               </div>

               <!-- ng-show (not ng-if) so the canvas stays in the page while the table is open -->
               <div class="dept-chart-wrap" ng-show="dashView != 'table'" ng-style="{ height: deptChartHeight }">
                  <canvas id="deptChart" aria-label="Students, teachers and subjects per department" role="img"></canvas>
               </div>

               <div class="table-responsive" ng-show="dashView == 'table'">
                  <table class="table table-sm table-hover align-middle mb-0">
                     <thead class="table-light">
                        <tr>
                           <th>Department</th>
                           <th class="text-end">Students</th>
                           <th class="text-end">Teachers</th>
                           <th class="text-end">Subjects</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr ng-repeat="x in dept_summary | limitTo:pager.size:(pager.page-1)*pager.size">
                           <td>{{ x.dept_name }}</td>
                           <td class="text-end">{{ x.students }}</td>
                           <td class="text-end">{{ x.teachers }}</td>
                           <td class="text-end">{{ x.subjects }}</td>
                        </tr>
                     </tbody>
                  </table>
                  <table-pager total="dept_summary.length" pager="pager"></table-pager>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
