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
        .otherwise({
            template: "<h1>Error 404! Not Found </h1>",
        });
});

app.controller("dashCtrl", function ($scope, $http) {

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
            },
            function () {
                alert("Error! Fetching Problem in std_data()");
            }
        );
    };
    // ****************** Department Section Start ******************
    $scope.dept_entry = function () {
        $http({
            url: "api/dept/dept_entry.php",
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
            url: "api/dept/dept_view.php",
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
                url: "api/dept/dept_delete.php",
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
            url: "api/dept/edit_update.php",
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
            url: "api/dept/edit_update.php",
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
                    var modal = new bootstrap.Modal(document.getElementById("exampleModal"));
                    modal.hide();
                }
            },
            function () {
                alert("Error! Fetching Problem in subject_update()");
            }
        );
    };
    
    // ****************** Subject Section End ******************
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
});
