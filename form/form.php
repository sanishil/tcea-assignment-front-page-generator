<?php
include "../tech/api/connect.php";
?>
<div class="container" style="padding-top: 20px;">

    <div class=" text-center">
        <!-- <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div> 
        <div class="spinner-grow text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div> -->
        <div class="card-body shadow-lg p-4 rounded">

            <div class="d-flex align-items-start">
                <!-- Logo/Image -->
                <img src="img/final_tcea.jpg" alt="College Logo" class="me-3"
                    style="width: 60px; height: 60px;">

                <!-- Text Section -->
                <div class="d-flex align-items-start justify-content-between" style="width: 100%;">
                    <div class="d-flex flex-column">
                        <span class="fw-bold fs-5" style="color:#347ec4;">
                            <b>TECHNO COLLEGE OF ENGINEERING AGARTALA</b>
                        </span>
                        <span class="fw-bold fs-12">Maheshkhola, Agartala, West Tripura,799004</span>
                        <span class="text-muted fs-6 text-start">EduCover Page...</span>
                    </div>
                    <a href="assets/TCEA_Format_Paper.pdf" target="_blank" class="btn btn-primary btn-sm align-self-start">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>
            </div>

            <!-- <marquee behavior="scroll" direction="left" scrollamount="8"
                style="background-color: #98a7abff; border-radius: 15px; ">
                🚧 <b>This website is under development. If you find any bug,
                    give your feedback at the bottom link <span
                        style="color: #4896D9;"> "Squash it here" </span> </b> ✨
            </marquee> -->
            <div class="text-center"> <b style="font-size: 20px;">
                    <i class="fa-solid fa-circle-info"></i> Fill in Your Details
                    Here
                </b></div>

            <div style="text-align: left;"><b><i class="fa-solid fa-users"></i>
                    Student
                    Details </b>
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="form-floating">
                        <input class="form-control"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            ng-model="std_id" ng-blur="checkStudentID()">
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-users-gear"></i>
                            Student ID </label>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-floating">
                        <input class="form-control"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            ng-model="tu_roll">
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-building-columns"></i>
                            TU Roll No </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Floating label select example"
                            ng-model="title">
                            <option selected></option>
                            <option>Mr.</option>
                            <option>Miss.</option>
                            <option>Mrs.</option>
                            <option>Sri</option>
                            <option>Smt.</option>
                        </select>
                        <label for="floatingTextarea">Honorific
                            Title </label>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-floating">
                        <input class="form-control"
                            style="text-transform: capitalize;" ng-model="name">
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-user"></i>
                            Name </label>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Floating label select example"
                            ng-model="sem">
                            <option selected></option>
                            <option>1st</option>
                            <option>2nd</option>
                            <option>3rd</option>
                            <option>4th</option>
                            <option>5th</option>
                            <option>6th</option>
                            <option>7th</option>
                            <option>8th</option>
                        </select>
                        <label for="floatingTextarea">Semester </label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Select Department"
                            ng-model="std_dept">
                            <option selected></option>
                            <?php
                            $sql = "SELECT dept_name FROM dept";
                            $result = $con->query($sql);
                            while ($row = $result->fetch_assoc()) { ?>
                            <option><?php echo $row['dept_name']; ?></option>
                            <?php } ?>
                        </select>
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-building"></i>
                            Department </label>
                    </div>
                </div>
                <div class="col-lg-2 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Floating label select example"
                            ng-model="course" ng-change="subjects_view();">
                            <option selected></option>
                            <option>B.Tech</option>
                            <option>Diploma</option>
                        </select>
                        <label for="floatingTextarea">Course
                            Code </label>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Floating label select example"
                            ng-model="subjects"
                            ng-change="subjects_code_view();">
                            <option selected></option>
                            <option
                                ng-repeat="s in subjectss_data">{{s.subjects}}</option>
                        </select>
                        <label for="floatingTextarea"><i
                                class="fa-brands fa-discourse"></i>
                            Subject
                            Title </label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <div class="form-floating">
                        <input class="form-control" ng-model="code" readonly>
                        <label for="floatingTextarea">Subject
                            Code </label>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Floating label select example"
                            ng-model="page_type">
                            <option selected></option>
                            <option>Assignment</option>
                            <option>Lab Assignment-I</option>
                            <option>Lab Assignment-II</option>
                            <option>Laboratory Record Book</option>
                        </select>
                        <label for="floatingTextarea">Front Page
                            Types </label>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Floating label select example"
                            ng-model="section">
                            <option selected></option>
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                        </select>
                        <label for="floatingTextarea"> Section</label>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="form-floating">
                        <input class="form-control" type="date" ng-model="dos">
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-calendar"></i>
                            Submission
                            Date </label>
                    </div>
                </div>
            </div>
            <div style="text-align: left;"><b><i
                        class="fa-solid fa-chalkboard-user"></i> Submitted
                    Faculty
                    Information</b>
                <hr>
            </div>
            <div class="row">
                <!-- Department Selection -->
                <div class="col-lg-4 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Select Department"
                            ng-model="f_dept" ng-change="tech_view();">
                            <option selected></option>
                            <?php
                            $sql = "SELECT dept_name FROM dept";
                            $result = $con->query($sql);
                            while ($row = $result->fetch_assoc()) { ?>
                            <option><?php echo $row['dept_name']; ?></option>
                            <?php } ?>
                        </select>
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-building"></i>
                            Department</label>
                    </div>
                </div>

                <!-- Faculty Name Selection -->
                <div class="col-lg-6 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid"
                            aria-label="Select Faculty"
                            ng-model="f_name" ng-change="designation_view();">
                            <option selected></option>
                            <option
                                ng-repeat="f in tech_data">{{f.teacher}}</option>
                        </select>
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-user"></i> Faculty
                            Name</label>
                    </div>
                </div>

                <!-- Designation Display -->
                <div class="col-lg-2 mb-3">
                    <div class="form-floating">
                        <input class="form-control" ng-model="f_designation"
                            readonly>
                        <label for="floatingTextarea"><i
                                class="fa-solid fa-pen-nib"></i>
                            Designation</label>
                    </div>
                </div>
            </div>
            <!-- Part 2 -->
            <div class="row"
                ng-show="page_type === 'Lab Assignment-I' || page_type === 'Lab Assignment-II' || page_type === 'Laboratory Record Book'">
                <!-- Faculty Name Selection -->
                <div class="col-lg-10 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelectGrid2"
                            aria-label="Select Faculty"
                            ng-model="f2_name"
                            ng-change="designation_view_2();">
                            <option selected></option>
                            <option
                                ng-repeat="f in tech_data">{{f.teacher}}</option>
                        </select>
                        <label for="floatingTextarea">
                            <i class="fa-solid fa-user"></i> Faculty Name
                        </label>
                    </div>
                </div>

                <!-- Designation Display -->
                <div class="col-lg-2 mb-3">
                    <div class="form-floating">
                        <input class="form-control" ng-model="f2_designation"
                            readonly>
                        <label for="floatingTextarea">
                            <i class="fa-solid fa-pen-nib"></i> Designation
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <button type="button" class="btn"
                    style=" background: linear-gradient(to bottom, #66ffcc 0%, #66ccff 100%);"
                    ng-click="executeFunctions();"><i
                        class="fa-solid fa-print"></i>
                    Print</button>
                <button type="button" class="btn"
                    style="background: linear-gradient(to bottom, #ff0066 0%, #cc33ff 100%); color: white;"
                    ng-click="clearAll()">
                    <i class="fa-solid fa-circle-xmark"></i> Clear All
                </button>

            </div>
            <br><br>
            <div class="row">
                <div class="col-lg-12">
                   <p class="mb-0">
                        <i class="fa-solid fa-copyright"></i> 2026 Developed By <b>
                        <a href="https://www.linkedin.com/in/sanishil" class="text-dark fw-semibold text-decoration-none"
                            target="_blank">
                            Mr. Sani Shil
                        </a></b> | Activity Reviewed by <b>
                       <a href="https://www.linkedin.com/in/nabakallol-deb-b096b7375/"
                            class="text-dark fw-semibold text-decoration-none" target="_blank">
                            Mr. Nabakallol Deb
                        </a></b> &<b>
                        <a href="https://www.instagram.com/paulsagar.29/"
                            class="text-dark fw-semibold text-decoration-none" target="_blank">
                            Mr. Sagar Rudra Paul
                        </a></b>

                    </p> 
                    
                </div>
            </div>
        </div>
    </div>
    <div class="a4-page" id="contentToPrint"
        style="display: none; width: 210mm; height: 297mm; padding: 20mm; background: white; box-sizing: border-box; font-family: 'Times New Roman', Times, serif;">

        <div class="text-center"
            style="text-align: center; font-family: 'Times New Roman', Times, serif;">
            <div
                style="font-size: 24px; font-weight: bold;"><b>{{page_type}}</b></div>
            <div class="mb-4"
                style="font-size: 35px; font-weight: bold; margin-bottom: 20px;">
                <b>Techno College of Engineering Agartala</b>
            </div>

            <div class="content"
                style="font-size: 19px; margin-bottom: 10px; font-family: 'Times New Roman', Times, serif;">
                <b>Course Title:</b> {{ subjects }}
            </div>
            <div class="content"
                style="font-size: 19px; margin-bottom: 10px; font-family: 'Times New Roman', Times, serif;">
                <b>Course Code:</b> {{ code }}
            </div>

            <br>
            <img src="img/final_tcea.jpg" alt="College Logo"
                class="college-logo"
                style="width: 150px; height: auto; margin: 20px auto; display: block;">
            <div class="content"
                style="font-size: 19px; margin-bottom: 10px; font-family: 'Times New Roman', Times, serif;">
                <b>MAHESHKHOLA, AGARTALA, TRIPURA</b>
                <br><br>

                <div class="content"
                    style="font-size: 19px; font-weight: bold; text-decoration: underline; font-family: 'Times New Roman', Times, serif;">
                    Submitted to
                </div>
                <div
                    style="font-size: 16px; font-weight: bold; font-family: 'Times New Roman', Times, serif;">
                    {{f_name}}</div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">{{f_designation}}</div><br>
                <div ng-if="page_type"
                    style="font-size: 16px; font-weight: bold; font-family: 'Times New Roman', Times, serif;">
                    {{f2_name}}</div>
                <div ng-if="page_type"
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">{{f2_designation}}</div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;"><b>Department
                        of
                        {{f_dept}}</b></div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;"><b>Techno
                        College of
                        Engineering Agartala</b></div>

                <br><br>

                <div class="content"
                    style="font-size: 19px; font-weight: bold; text-decoration: underline; font-family: 'Times New Roman', Times, serif;">
                    Submitted by
                </div>
                <div
                    style="font-size: 16px; font-weight: bold; font-family: 'Times New Roman', Times, serif; text-transform: capitalize;">
                    {{title}} {{name}}</div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">Student
                    ID.: {{std_id}}
                </div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">TU
                    Roll No.: {{tu_roll}}
                </div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">Semester:
                    {{sem}}</div>
                <div ng-if="section"
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">Section:
                    {{section}}</div>
                <div  style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">Academic Session: 2025–2026</div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;"><b>Department
                        of
                        {{std_dept}}</b></div>
                <div
                    style="font-size: 16px; font-family: 'Times New Roman', Times, serif;"><b>Techno
                        College of
                        Engineering Agartala</b></div>

                <br><br>

                <div
                    style="font-size: 16px; font-weight: bold; font-family: 'Times New Roman', Times, serif;">
                    Date of Submission - {{dos | date:'dd-MM-yyyy'}}
                </div>
            </div>
        </div>

    </div>
  </div>
    <?php
    $con->close();
    ?>