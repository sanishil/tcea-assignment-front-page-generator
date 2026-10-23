var app = angular.module("myApp", ["ngRoute", "ngCookies"]);
app.config(function ($routeProvider) {
  $routeProvider
    .when("/", {
      templateUrl: "pages/dash.php",
      controller: "dashCtrl",
    })
    .when("/dash", {
      templateUrl: "pages/dash.php",
      controller: "dashCtrl",
    })
    .when("/dept", {
      templateUrl: "pages/dept.html",
      controller: "dashCtrl",
    })
    .when("/student", {
      templateUrl: "pages/student.html",
      controller: "dashCtrl",
    })
    .when("/subjects", {
      templateUrl: "pages/subjects.php",
      controller: "dashCtrl",
    })
    .when("/teacher", {
      templateUrl: "pages/teacher.php",
      controller: "dashCtrl",
    })
    .when("/change_pass", {
      templateUrl: "pages/change_pass.html",
      controller: "dashCtrl",
    })
    .when("/user_section", {
      templateUrl: "pages/user_section.html",
      controller: "dashCtrl",
    })
    .when("/active_user", {
      templateUrl: "pages/active_user.html",
      controller: "dashCtrl",
    })
    .otherwise({
      template: "<h1>Error 404! Not Found </h1>",
    });
});

var deptChartInstance = null;

// Chart colours follow the light / dark theme tokens in assets/css/modern.css
function chartColors() {
  var css = getComputedStyle(document.documentElement);
  function token(name, fallback) {
    return css.getPropertyValue(name).trim() || fallback;
  }
  return {
    text: token("--chart-text", "#52514e"),
    grid: token("--chart-grid", "#eef0f2"),
    bars: [token("--tone-blue", "#2a78d6"), token("--tone-orange", "#eb6834"), token("--tone-green", "#1baf7a")],
  };
}

// re-colour the dashboard chart when the theme is switched (assets/js/theme.js)
document.addEventListener("themechange", function () {
  var chart = deptChartInstance;
  if (!chart || !document.body.contains(chart.canvas)) return;
  var c = chartColors();
  chart.data.datasets.forEach(function (ds, i) {
    ds.backgroundColor = c.bars[i];
  });
  chart.options.plugins.legend.labels.color = c.text;
  chart.options.scales.x.ticks.color = c.text;
  chart.options.scales.y.ticks.color = c.text;
  chart.options.scales.y.grid.color = c.grid;
  chart.update();
});

app.controller("dashCtrl", function ($scope, $http, $timeout) {
  // pagination state for the table on the current page (see tablePager below)
  $scope.pager = { page: 1, size: 10 };

  // Student / subject lists: semester dropdown (All, then each semester that has rows)
  $scope.semSel = { value: "" }; // "" = All
  $scope.semTabs = [];

  function buildSemTabs(rows) {
    var sems = ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th"];
    // keep any other semester values that exist in the data
    rows.forEach(function (r) {
      if (r.sem && sems.indexOf(r.sem) === -1) sems.push(r.sem);
    });
    var tabs = [{ value: "", label: "All semesters", count: rows.length }];
    sems.forEach(function (s) {
      var count = rows.filter(function (r) { return r.sem == s; }).length;
      // hide empty semesters, but never the one that is selected
      if (count || s == $scope.semSel.value) {
        tabs.push({ value: s, label: s + " semester", count: count });
      }
    });
    return tabs;
  }

  $scope.bySem = function (x) {
    return !$scope.semSel.value || x.sem == $scope.semSel.value;
  };

  $scope.pickSem = function (value) {
    $scope.semSel.value = value;
    $scope.pager.page = 1;
  };

  // Subject list: semester tabs (shared with the student list) + branch filter
  $scope.branchSel = { value: null }; // null = All branches
  $scope.branches = [];

  $scope.byBranch = function (x) {
    return !$scope.branchSel.value || x.dept_name == $scope.branchSel.value;
  };

  // semester tab counts follow the chosen branch
  function buildSubjectFilters() {
    var rows = $scope.subject_data || [];
    $scope.branches = rows
      .map(function (r) { return r.dept_name; })
      .filter(function (d, i, all) { return d && all.indexOf(d) === i; })
      .sort();
    $scope.semTabs = buildSemTabs(rows.filter($scope.byBranch));
  }

  $scope.pickBranch = function () {
    $scope.pager.page = 1;
    buildSubjectFilters();
  };

  // Teacher list: department filter
  $scope.teacherDept = { value: null }; // null = All departments
  $scope.teacherDepts = [];

  $scope.byTeacherDept = function (x) {
    return !$scope.teacherDept.value || x.dept_name == $scope.teacherDept.value;
  };

  function buildTeacherDepts() {
    var rows = $scope.teacher_data || [];
    $scope.teacherDepts = rows
      .map(function (r) { return r.dept_name; })
      .filter(function (d, i, all) { return d && all.indexOf(d) === i; })
      .sort()
      .map(function (d) {
        return { name: d, count: rows.filter(function (r) { return r.dept_name == d; }).length };
      });
  }

  // "dashboard"
  $scope.std_view = function () {
    $http({
      url: "api/std/std_view.php",
      method: "POST",
      data: {
        op: "view",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.std_data = response.data.data;
        } else {
          $scope.std_data = []; // Set empty array
          $scope.noDataMessage = response.data.message;
        }
        $scope.semTabs = buildSemTabs($scope.std_data);
      },
      function () {
        alert("Error! Fetching Problem in std_data()");
      }
    );
  };

  // Dashboard chart: students / teachers / subjects per department
  $scope.dashView = "chart";
  $scope.dept_chart = function () {
    $http({
      url: "api/dash/dept_summary.php",
      method: "POST",
      data: {
        op: "view",
      },
    }).then(
      function (response) {
        if (response.data.status === "success") {
          $scope.dept_summary = response.data.data;
          // vertical bars: fixed height, a bit shorter on phones
          $scope.deptChartHeight = window.innerWidth < 576 ? "320px" : "400px";
          // wait for the height to be applied before drawing
          $timeout(function () {
            drawDeptChart($scope.dept_summary);
          });
        } else {
          $scope.dept_summary = [];
          $scope.deptChartError = response.data.message;
        }
      },
      function () {
        $scope.deptChartError = "Could not load chart data";
      }
    );
  };

  function drawDeptChart(rows) {
    var canvas = document.getElementById("deptChart");
    if (!canvas || typeof Chart === "undefined") return;
    if (deptChartInstance) deptChartInstance.destroy();

    var colors = chartColors();
    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    deptChartInstance = new Chart(canvas, {
      type: "bar",
      data: {
        labels: rows.map(function (r) { return r.dept_name; }),
        datasets: [
          { label: "Students", data: rows.map(function (r) { return r.students; }), backgroundColor: colors.bars[0] },
          { label: "Teachers", data: rows.map(function (r) { return r.teachers; }), backgroundColor: colors.bars[1] },
          { label: "Subjects", data: rows.map(function (r) { return r.subjects; }), backgroundColor: colors.bars[2] },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        datasets: {
          bar: {
            borderRadius: 4,
            borderSkipped: "start",
            barPercentage: 0.85,
            categoryPercentage: 0.8,
          },
        },
        interaction: { mode: "index", axis: "x", intersect: false },
        plugins: {
          legend: {
            position: "top",
            align: "start",
            labels: { color: colors.text, usePointStyle: true, pointStyle: "rectRounded", boxWidth: 10 },
          },
          tooltip: { padding: 10 },
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: {
              color: colors.text,
              autoSkip: false,
              // flat labels on wide screens; phones still need to tilt them
              maxRotation: window.innerWidth < 576 ? 50 : 0,
              // wrap names onto at most two lines; the tooltip still shows the full name
              callback: function (value) {
                var label = this.getLabelForValue(value);
                // phones: one short tilted line (wrapped lines overlap when tilted)
                if (window.innerWidth < 576) return label.length > 12 ? label.slice(0, 11) + "…" : label;
                var max = 16; // characters per line
                var lines = [], line = "";
                label.split(" ").forEach(function (word) {
                  if (line && (line + " " + word).length > max) {
                    lines.push(line);
                    line = word;
                  } else {
                    line = line ? line + " " + word : word;
                  }
                });
                lines.push(line);
                if (lines.length > 2) lines = [lines[0], lines[1].slice(0, max - 1) + "…"];
                return lines;
              },
            },
          },
          y: {
            beginAtZero: true,
            ticks: { color: colors.text, precision: 0 },
            grid: { color: colors.grid },
            border: { display: false },
          },
        },
      },
    });
  }
  // ****************** Department Section Start ******************
  $scope.dept_entry = function () {
    $http({
      url: "api/Dept/dept_entry.php",
      method: "POST",
      data: {
        dept_name: $scope.dept_name,
        hod: $scope.hod,
        op: "insert",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          alert(response.data.message);
          $scope.dept_name = null;
          $scope.hod = null;
          $scope.dept_view();
        } else {
          alert("Error: " + response.data.message);
        }
      },
      function (error) {
        console.error("HTTP Request Failed", error);
        alert("Error! Fetching Problem in sendData()");
      }
    );
  };
  $scope.dept_view = function () {
    $http({
      url: "api/Dept/dept_view.php",
      method: "POST",
      data: {
        op: "view",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.dept_data = response.data.data;
        } else {
          $scope.dept_data = []; // Set empty array
          $scope.noDataMessage = response.data.message;
        }
      },
      function () {
        alert("Error! Fetching Problem in dept_view()");
      }
    );
  };
  $scope.dept_delete = function (id) {
    if (confirm("Are you sure you want to delete this department?")) {
      $http({
        url: "api/Dept/dept_delete.php",
        method: "POST",
        data: {
          idd: id,
          op: "delete",
        },
      }).then(
        function (response) {
          console.log(response.data);
          $scope.deleteMessage = response.data.message; // Show message in UI
          setTimeout(() => {
            $scope.deleteMessage = "";
            $scope.$apply();
          }, 3000); // Hide after 3 sec
          $scope.dept_view();
        },
        function () {
          alert("Error! Fetching Problem in dept_delete()");
        }
      );
    }
  };
  $scope.dept_edit = function (id) {
    $http({
      url: "api/Dept/edit_update.php",
      method: "POST",
      data: {
        op: "select_condition",
        idd: id,
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.dept_name = response.data.data[0].dept_name;
          $scope.hod = response.data.data[0].hod;
          $scope.idd = id;
        } else {
          alert(response.data.message);
        }
      },
      function () {
        alert("Error! Fetching Problem in dept_edit()");
      }
    );
  };
  $scope.dept_update = function () {
    $http({
      url: "api/Dept/edit_update.php",
      method: "POST",
      data: {
        id: $scope.idd,
        dept_name: $scope.dept_name,
        hod: $scope.hod,
        op: "edit",
      },
    }).then(
      function (response) {
        console.log(response.data);
        alert(response.data.message); // Display response message

        if (response.data.status === "success") {
          $scope.dept_view(); // Refresh department list

          // Hide modal using Bootstrap 5 method
          var modalElement = document.getElementById("exampleModal");
          var modal = bootstrap.Modal.getInstance(modalElement); // Get modal instance
          if (modal) {
            modal.hide();
          }
        }
      },
      function () {
        alert("Error! Fetching Problem in dept_update()");
      }
    );
  };
  $scope.std_delete = function (id) {
    if (confirm("Are you sure you want to delete this stduent?")) {
      $http({
        url: "api/std/std_delete.php",
        method: "POST",
        data: {
          idd: id,
          op: "delete",
        },
      }).then(
        function (response) {
          console.log(response.data);
          $scope.deleteMessage = response.data.message; // Show message in UI
          setTimeout(() => {
            $scope.deleteMessage = "";
            $scope.$apply();
          }, 3000); // Hide after 3 sec
          $scope.std_view();
        },
        function () {
          alert("Error! Fetching Problem in std_delete()");
        }
      );
    }
  };

  // ****************** Department Section End ******************
  // ****************** Teacher Section Start ******************
  $scope.teacher_entry = function () {
    $http({
      url: "api/teacher/teacher_entry.php",
      method: "POST",
      data: {
        teacher: $scope.teacher,
        designation: $scope.designation,
        dept_name: $scope.dept_name,
        op: "insert",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          alert(response.data.message);
          $scope.teacher = null;
          $scope.designation = null;
          $scope.dept_name = null;
          $scope.teacher_view();
        } else {
          alert("Error: " + response.data.message);
        }
      },
      function (error) {
        console.error("HTTP Request Failed", error);
        alert("Error! Fetching Problem in sendData()");
      }
    );
  };
  $scope.teacher_view = function () {
    $http({
      url: "api/teacher/teacher_view.php",
      method: "POST",
      data: {
        op: "view",
      },
    }).then(
      function (response) {
        console.log(response.data); // Debugging
        if (response.data.status === "success") {
          $scope.teacher_data = response.data.data;
          $scope.noDataMessage = ""; // ✅ Reset message when data is available
        } else {
          $scope.teacher_data = []; // ✅ Ensure the array is empty
          $scope.noDataMessage = response.data.message; // ✅ Assign message
        }
        buildTeacherDepts();
      },
      function () {
        alert("Error! Fetching Problem in teacher_view()");
      }
    );
  };

  $scope.teacher_delete = function (id) {
    if (confirm("Are you sure you want to delete this teacher?")) {
      $http({
        url: "api/teacher/teacher_delete.php",
        method: "POST",
        data: {
          idd: id,
          op: "delete",
        },
      }).then(
        function (response) {
          console.log(response.data);
          $scope.deleteMessage = response.data.message; // Show message in UI
          setTimeout(() => {
            $scope.deleteMessage = "";
            $scope.$apply();
          }, 3000); // Hide after 3 sec
          $scope.teacher_view();
        },
        function () {
          alert("Error! Fetching Problem in teacher_delete()");
        }
      );
    }
  };
  $scope.teacher_edit = function (id) {
    $http({
      url: "api/teacher/teacher_update.php",
      method: "POST",
      data: {
        op: "select_condition",
        idd: id,
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.designation = response.data.data[0].designation;
          $scope.teacher = response.data.data[0].teacher;
          $scope.dept_name = response.data.data[0].dept_name;
          $scope.idd = id;
        } else {
          alert(response.data.message);
        }
      },
      function () {
        alert("Error! Fetching Problem in teacher_edit()");
      }
    );
  };
  $scope.teacher_update = function () {
    $http({
      url: "api/teacher/teacher_update.php",
      method: "POST",
      data: {
        id: $scope.idd,
        teacher: $scope.teacher,
        designation: $scope.designation,
        dept_name: $scope.dept_name,
        op: "edit",
      },
    }).then(
      function (response) {
        console.log(response.data);
        alert(response.data.message); // Display response message
        if (response.data.status === "success") {
          $scope.teacher_view(); // Refresh teacher list

          // Hide modal using Bootstrap 5 method
          var modalElement = document.getElementById("exampleModal");
          var modal = bootstrap.Modal.getInstance(modalElement); // Get modal instance
          if (modal) {
            modal.hide();
          }
        }
      },
      function () {
        alert("Error! Fetching Problem in teacher_update()");
      }
    );
  };

  // ****************** Teacher Section End ******************

  // ****************** Subject Section End ******************
  $scope.subject_entry = function () {
    $http({
      url: "api/subject/subject_entry.php",
      method: "POST",
      data: {
        subjects: $scope.subjects,
        code: $scope.code,
        sem: $scope.sem,
        course: $scope.course,
        dept_name: $scope.dept_name,
        op: "insert",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          alert(response.data.message);
          $scope.subjects = null;
          $scope.code = null;
          $scope.sem = null;
          $scope.course = null;
          $scope.dept_name = null;
          $scope.subject_view();
        } else {
          alert("Error: " + response.data.message);
        }
      },
      function (error) {
        console.error("HTTP Request Failed", error);
        alert("Error! Fetching Problem in sendData()");
      }
    );
  };

  $scope.subject_view = function () {
    $http({
      url: "api/subject/subject_view.php",
      method: "POST",
      data: {
        op: "view",
      },
    }).then(
      function (response) {
        console.log(response.data); // Debugging
        if (response.data.status === "success") {
          $scope.subject_data = response.data.data;
          $scope.noDataMessage = ""; // ✅ Reset message when data is available
        } else {
          $scope.subject_data = []; // ✅ Ensure the array is empty
          $scope.noDataMessage = response.data.message; // ✅ Assign message
        }
        buildSubjectFilters();
      },
      function () {
        alert("Error! Fetching Problem in subject_data()");
      }
    );
  };

  $scope.subject_delete = function (id) {
    if (confirm("Are you sure you want to delete this subject?")) {
      $http({
        url: "api/subject/subject_delete.php",
        method: "POST",
        data: {
          idd: id,
          op: "delete",
        },
      }).then(
        function (response) {
          console.log(response.data);
          $scope.deleteMessage = response.data.message; // Show message in UI
          setTimeout(() => {
            $scope.deleteMessage = "";
            $scope.$apply();
          }, 3000); // Hide after 3 sec
          $scope.subject_view();
        },
        function () {
          alert("Error! Fetching Problem in subject_delete()");
        }
      );
    }
  };

  $scope.subject_edit = function (id) {
    $http({
      url: "api/subject/subject_update.php",
      method: "POST",
      data: {
        op: "select_condition",
        idd: id,
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.subjects = response.data.data[0].subjects;
          $scope.code = response.data.data[0].code;
          $scope.sem = response.data.data[0].sem;
          $scope.course = response.data.data[0].course;
          $scope.dept_name = response.data.data[0].dept_name;
          $scope.idd = id;
        } else {
          alert(response.data.message);
        }
      },
      function () {
        alert("Error! Fetching Problem in subject_edit()");
      }
    );
  };
  $scope.subject_update = function () {
    $http({
      url: "api/subject/subject_update.php",
      method: "POST",
      data: {
        id: $scope.idd,
        subjects: $scope.subjects,
        code: $scope.code,
        sem: $scope.sem,
        course: $scope.course,
        dept_name: $scope.dept_name,
        op: "edit",
      },
    }).then(
      function (response) {
        console.log(response.data);
        alert(response.data.message); // Display response message
        if (response.data.status === "success") {
          $scope.subject_view(); // Refresh subject list

          // Hide modal using Bootstrap 5 method
          var modal = new bootstrap.Modal(
            document.getElementById("exampleModal")
          );
          modal.hide();
        }
      },
      function () {
        alert("Error! Fetching Problem in subject_update()");
      }
    );
  };

  // ****************** Subject Section End ******************

  $scope.user_entry = function () {
    $http({
      url: "api/user/user_entry.php",
      method: "POST",
      data: {
        name: $scope.name,
        user_id: $scope.user_id,
        pass: $scope.pass,
        type: $scope.type,
        op: "insert",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          alert(response.data.message);
          $scope.name = null;
          $scope.user_id = null;
          $scope.pass = null;
          $scope.type = null;
          $scope.user_view();
        } else {
          alert("Error: " + response.data.message);
        }
      },
      function (error) {
        console.error("HTTP Request Failed", error);
        alert("Error! Fetching Problem in sendData()");
      }
    );
  };
  $scope.user_view = function () {
    $http({
      url: "api/user/user_view.php",
      method: "POST",
      data: {
        op: "view",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.user_data = response.data.data;
        } else {
          $scope.user_data = []; // Set empty array
          $scope.noDataMessage = response.data.message;
        }
      },
      function () {
        alert("Error! Fetching Problem in user_view()");
      }
    );
  };
  $scope.user_delete = function (id) {
    if (confirm("Are you sure you want to Delete this department?")) {
      $http({
        url: "api/user/user_delete.php",
        method: "POST",
        data: {
          idd: id,
          op: "delete",
        },
      }).then(
        function (response) {
          console.log(response.data);
          $scope.deleteMessage = response.data.message; // Show message in UI
          setTimeout(() => {
            $scope.deleteMessage = "";
            $scope.$apply();
          }, 3000); // Hide after 3 sec
          $scope.user_view();
        },
        function () {
          alert("Error! Fetching Problem in user_delete()");
        }
      );
    }
  };
  $scope.user_edit = function (id) {
    $http({
      url: "api/user/user_edit_update.php",
      method: "POST",
      data: {
        op: "select_condition",
        idd: id,
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.name = response.data.data[0].name;
          $scope.user_id = response.data.data[0].user_id;
          $scope.type = response.data.data[0].type;
          $scope.idd = id;
        } else {
          alert(response.data.message);
        }
      },
      function () {
        alert("Error! Fetching Problem in user_edit()");
      }
    );
  };
  $scope.user_update = function () {
    $http({
      url: "api/user/user_edit_update.php",
      method: "POST",
      data: {
        id: $scope.idd,
        name: $scope.name,
        user_id: $scope.user_id,
        type: $scope.type,
        op: "edit",
      },
    }).then(
      function (response) {
        console.log(response.data);
        alert(response.data.message); // Display response message

        if (response.data.status === "success") {
          $scope.user_view(); // Refresh department list

          // Hide modal using Bootstrap 5 method
          var modalElement = document.getElementById("exampleModal");
          var modal = bootstrap.Modal.getInstance(modalElement); // Get modal instance
          if (modal) {
            modal.hide();
          }
        }
      },
      function () {
        alert("Error! Fetching Problem in user_update()");
      }
    );
  };
  $scope.change_pass = function () {
    $http({
      url: "api/change_pass.php",
      method: "POST",
      data: {
        current_pass: $scope.current_pass,
        npass: $scope.npass,
        cpass: $scope.cpass,
      },
    }).then(
      function (response) {
        console.log(response.data);
        alert(response.data.message);

        if (response.data.status === "success") {
          // Clear the form
          $scope.current_pass = "";
          $scope.npass = "";
          $scope.cpass = "";

          // Hide modal
          var modalElement = document.getElementById("exampleModal");
          var modal = bootstrap.Modal.getInstance(modalElement);
          if (modal) {
            modal.hide();
          }
        }
      },
      function (error) {
        console.error("Error:", error);
        alert("Error! Unable to update password");
      }
    );
  };

  //for the active user function user_view2
  $scope.user_view2 = function () {
    $http({
      url: "api/user/user_view.php",
      method: "POST",
      data: {
        op: "view2",
      },
    }).then(
      function (response) {
        console.log(response.data);
        if (response.data.status === "success") {
          $scope.user_data2 = response.data.data;
        } else {
          $scope.user_data2 = []; // Set empty array
          $scope.noDataMessage = response.data.message;
        }
      },
      function () {
        alert("Error! Fetching Problem in user_view2()");
      }
    );
  };
   $scope.deactivate = function (id) {
    if (confirm("Are you sure you want to Deactivate this ID?")) {
      $http({
        url: "api/user/deactivate.php",
        method: "POST",
        data: {
          idd: id,
          op: "deactivate",
        },
      }).then(
        function (response) {
          console.log(response.data);
          $scope.deleteMessage = response.data.message; // Show message in UI
          setTimeout(() => {
            $scope.deleteMessage = "";
            $scope.$apply();
          }, 3000); // Hide after 3 sec
          $scope.user_view2();
        },
        function () {
          alert("Error! Fetching Problem in deactivate()");
        }
      );
    }
  };
});

// Pagination bar for tables. Usage:
//   <tr ng-repeat="x in (rows = (data | filter:search)) | limitTo:pager.size:(pager.page-1)*pager.size">
//   <table-pager total="rows.length" pager="pager"></table-pager>
app.directive("tablePager", function () {
  return {
    restrict: "E",
    scope: { total: "<", pager: "=" },
    template:
      '<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2" ng-if="total > 0">' +
      '  <div class="d-flex align-items-center gap-2 small text-muted">' +
      '    <span>Show</span>' +
      '    <select class="form-select form-select-sm w-auto" ng-model="pager.size" ng-options="s.value as s.label for s in sizes" ng-change="pager.page = 1"></select>' +
      '    <span>{{ from() }}–{{ to() }} of {{ total }}</span>' +
      '  </div>' +
      '  <ul class="pagination pagination-sm mb-0">' +
      '    <li class="page-item" ng-class="{ disabled: pager.page == 1 }"><a class="page-link" href="" ng-click="go(pager.page - 1)">&laquo;</a></li>' +
      '    <li class="page-item" ng-repeat="p in pageList() track by $index" ng-class="{ active: p == pager.page, disabled: p == gap }">' +
      '      <a class="page-link" href="" ng-click="go(p)">{{ p }}</a></li>' +
      '    <li class="page-item" ng-class="{ disabled: pager.page == pages() }"><a class="page-link" href="" ng-click="go(pager.page + 1)">&raquo;</a></li>' +
      '  </ul>' +
      '</div>',
    link: function (scope) {
      // "All" is a very large page size, so every row fits on page 1
      scope.sizes = [
        { value: 10, label: "10" },
        { value: 25, label: "25" },
        { value: 50, label: "50" },
        { value: 100, label: "100" },
        { value: Number.MAX_SAFE_INTEGER, label: "All" },
      ];
      scope.gap = "…";

      scope.pages = function () {
        return Math.max(1, Math.ceil((scope.total || 0) / scope.pager.size));
      };
      scope.from = function () {
        return (scope.pager.page - 1) * scope.pager.size + 1;
      };
      scope.to = function () {
        return Math.min(scope.pager.page * scope.pager.size, scope.total);
      };
      scope.go = function (p) {
        if (typeof p === "number" && p >= 1 && p <= scope.pages()) scope.pager.page = p;
      };

      // 1 … 4 5 6 … 20
      scope.pageList = function () {
        var n = scope.pages(), cur = scope.pager.page, list = [];
        for (var i = 1; i <= n; i++) {
          if (i == 1 || i == n || Math.abs(i - cur) <= 1) list.push(i);
          else if (list[list.length - 1] !== scope.gap) list.push(scope.gap);
        }
        return list;
      };

      // stay on a valid page when rows are deleted or the search shrinks the list
      scope.$watchGroup(["total", "pager.size"], function () {
        if (scope.pager.page > scope.pages()) scope.pager.page = scope.pages();
      });
    },
  };
});
