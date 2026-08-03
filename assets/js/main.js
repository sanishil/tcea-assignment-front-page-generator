var app = angular.module("myApp", ["ngRoute", "ngCookies"]);
app.config(function ($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl: "form/form.php",
            controller: "dashCtrl",
        })
        .otherwise({
            template: "<h1>Error 404! Not Found </h1>",

        });
});

app.controller("dashCtrl", function ($scope, $http) {


    $scope.clearAll = function () {
        $scope.std_id = "";
        $scope.tu_roll = "";
        $scope.title = "";
        $scope.name = "";
        $scope.sem = "";
        $scope.std_dept = "";
        $scope.course = "";
        $scope.subjects = "";
        $scope.code = "";
        $scope.page_type = "";
        $scope.section = "";
        $scope.dos = "";
        $scope.f_dept = "";
        $scope.f_name = "";
        $scope.f_designation = "";
        console.log("Form cleared!");
    };

    $scope.faculty_view = function () {
        if (!$scope.f_dept) {
            $scope.facultyList = [];
            return;
        }

        $http({
            url: "form/get_faculty.php",
            method: "POST",
            data: {
                op: "view",
                dept_name: $scope.f_dept,
            },
        }).then(
            function (response) {
                console.log(response.data);
                if (response.data.status === "success") {
                    $scope.facultyList = response.data.data;
                } else {
                    $scope.facultyList = []; // Set empty array if no data
                    $scope.noFacultyMessage = response.data.message;
                }
            },
            function () {
                alert("Error! Fetching Problem in faculty_view()");
            }
        );
    };


    // ****************** Student Section Start ******************
    $scope.std_entry = function () {
        return $http({
            url: "form/std_entry.php",
            method: "POST",
            data: {
                std_id: $scope.std_id,
                tu_roll: $scope.tu_roll,
                title: $scope.title,
                name: $scope.name,
                sem: $scope.sem,
                std_dept: $scope.std_dept,
                course: $scope.course,
                subjects: $scope.subjects,
                code: $scope.code,
                page_type: $scope.page_type,
                section: $scope.section,
                dos: $scope.dos,
                f_dept: $scope.f_dept,
                f_name: $scope.f_name,
                f_designation: $scope.f_designation,
                op: "insert", // Always "insert", PHP will check if update is needed
            },
        }).then(
            function (response) {
                console.log(response.data);
                if (response.data.status === "success") {
                    alert(response.data.message);
    
                    // Clear input fields after successful operation
                    $scope.std_id = null;
                    $scope.tu_roll = null;
                    $scope.title = null;
                    $scope.name = null;
                    $scope.sem = null;
                    $scope.std_dept = null;
                    $scope.course = null;
                    $scope.subjects = null;
                    $scope.code = null;
                    $scope.page_type = null;
                    $scope.section = null;
                    $scope.dos = null;
                    $scope.f_dept = null;
                    $scope.f_name = null;
                    $scope.f_designation = null;
    
                    return true; // success
                } else {
                    alert("Please fill all details properly!");
                    return false; // failed
                }
            },
            function (error) {
                console.error("HTTP Request Failed", error);
                alert("Error! Problem in sending data.");
                return false;
            }
        );
    };
    
    $scope.executeFunctions = function () {
        $scope.std_entry().then(function (success) {
            if (success) {
                $scope.printPDF(); // Only print if save/update successful
            }
        });
    };
    
    $scope.printPDF = function () {
        const { jsPDF } = window.jspdf;
        let content = document.getElementById("contentToPrint");
        content.style.display = "block";
        html2canvas(content, {
            scale: 2,
            backgroundColor: "#ffffff",
            useCORS: true
        }).then(canvas => {
            let pdf = new jsPDF("p", "mm", "a4");
            let imgWidth = 210;  // A4 width in mm
            let imgHeight = (canvas.height * imgWidth) / canvas.width;
            let imgData = canvas.toDataURL("image/jpeg", 0.7);
            pdf.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight);
            let currentDate = new Date();
            let formattedDate = currentDate.toISOString().slice(0, 10); // Format: YYYY-MM-DD
            pdf.save(`EduCoverPage_${formattedDate}.pdf`);
            content.style.display = "none";
        });
    };
    
    $scope.tech_view = function () {
        $http({
            url: "form/get_faculty.php",
            method: "POST",  // Change from GET to POST
            data: { f_dept: $scope.f_dept }, // Send department name correctly
            headers: { "Content-Type": "application/json" },
        }).then(
            function (response) {
                console.log(response.data); // Debugging
                if (response.data.status === "success") {
                    $scope.tech_data = response.data.data;
                } else {
                    $scope.tech_data = [];
                    $scope.noDataMessage = response.data.message;
                }
            },
            function () {
                alert("Error! Fetching Problem in tech_view()");
            }
        );
    };
    
    $scope.designation_view = function () {
        if (!$scope.f_dept || !$scope.f_name) {
            alert("Please select both department and faculty name");
            return;
        }

        $http({
            url: "form/get_faculty_designation.php",
            method: "POST",
            data: { f_dept: $scope.f_dept, f_name: $scope.f_name },
            headers: { "Content-Type": "application/json" },
        }).then(
            function (response) {
                console.log("Response Data:", response.data);
                if (response.data.status === "success" && response.data.data.length > 0) {
                    $scope.f_designation = response.data.data.join(", ");
                } else {
                    $scope.f_designation = "No designation found";
                }
            },
            function () {
                alert("Error! Fetching Problem in designation_view()");
            }
        );
    };
    $scope.subjects_view = function () {
        if (!$scope.sem || !$scope.std_dept || !$scope.course) {
            alert("Please select semester, department, and course");
            return;
        }

        $http({
            url: "form/subject_gets.php",
            method: "POST",
            data: { sem: $scope.sem, std_dept: $scope.std_dept, course: $scope.course },
            headers: { "Content-Type": "application/json" },
        }).then(
            function (response) {
                console.log(response.data); // Debugging
                if (response.data.status === "success") {
                    $scope.subjectss_data = response.data.data; // Store the list of subjects
                } else {
                    $scope.subjectss_data = [];
                    $scope.noDataMessage = response.data.message;
                }
            },
            function () {
                alert("Error! Fetching subjects failed.");
            }
        );
    };
    $scope.subjects_code_view = function () {
        if (!$scope.sem || !$scope.std_dept || !$scope.course || !$scope.subjects) {
            alert("Please select semester, department, and course");
            return;
        }

        $http({
            url: "form/subject_code_gets.php",
            method: "POST",
            data: { sem: $scope.sem, std_dept: $scope.std_dept, course: $scope.course, subjects: $scope.subjects },
            headers: { "Content-Type": "application/json" },
        }).then(
            function (response) {
                console.log("Response Data:", response.data);
                if (response.data.status === "success" && response.data.data.length > 0) {
                    $scope.code = response.data.data.join(", ");
                } else {
                    $scope.code = "No designation found";
                }
            },
            function () {
                alert("Error! Fetching subjects failed.");
            }
        );
    };

    $scope.checkStudentID = function () {
        if (($scope.std_id && $scope.std_id.trim() !== "")) {
            $http.post("form/check_student.php", { std_id: $scope.std_id })
                .then(function (response) {
                    if (response.data.status === "found") {
                        // Populate the form fields with existing data
                        $scope.tu_roll = response.data.tu_roll;
                        $scope.title = response.data.title;
                        $scope.name = response.data.name;
                        $scope.sem = response.data.sem;
                        $scope.std_dept = response.data.std_dept;
                    } else {
                        // Clear fields if no data is found
                        $scope.tu_roll = "";
                        $scope.title = "";
                        $scope.name = "";
                        $scope.sem = "";
                        $scope.std_dept = "";
                    }
                })
                .catch(function (error) {
                    console.error("Error fetching student data:", error);
                });
        }
    };



    // ****************** Student Section End ******************



});
