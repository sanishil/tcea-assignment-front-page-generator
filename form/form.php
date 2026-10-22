<?php
include "../tech/api/connect.php";

// department names are used by both the student and the faculty dropdowns
$depts = [];
$result = $con->query("SELECT dept_name FROM dept");
while ($row = $result->fetch_assoc()) {
    $depts[] = $row['dept_name'];
}
?>
<div class="ec-page">

    <!-- ================= Campus banner ================= -->
    <header class="ec-hero">
        <picture>
            <source srcset="img/campus.webp" type="image/webp">
            <img class="ec-hero-photo" src="img/campus.jpg" alt="" width="864" height="1080" decoding="async">
        </picture>
        <div class="ec-hero-inner">
            <div class="ec-brand">
                <img src="img/final_tcea.jpg" alt="TCEA logo" width="60" height="60">
                <div>
                    <div class="ec-brand-name">TECHNO COLLEGE OF ENGINEERING AGARTALA</div>
                    <div class="ec-brand-addr">Maheshkhola, Agartala, West Tripura, 799004</div>
                </div>
            </div>
            <a href="assets/TCEA_Format_Paper.pdf" target="_blank" rel="noopener" class="ec-format-btn"
                title="Download the format paper" aria-label="Download the format paper">
                <i class="fa-solid fa-download"></i><span>Format paper</span>
            </a>
        </div>
    </header>

    <!-- ================= Form ================= -->
    <div class="ec-shell">
        <div class="ec-card">

            <div class="ec-card-head">
                <h1>EduCover Page</h1>
                <p>Fill in your details to download a ready-to-print assignment cover page.</p>
            </div>

            <!-- 1. Student -->
            <section class="ec-section">
                <h2 class="ec-section-title"><span class="ec-step">1</span> Student details
                    <small><span class="req text-danger">*</span> required</small></h2>

                <div class="row g-3">
                    <div class="col-md-6 ec-field">
                        <label for="ecStdId"><i class="fa-solid fa-id-card"></i> Student ID<span class="req">*</span></label>
                        <input class="form-control" id="ecStdId" inputmode="numeric" autocomplete="off"
                            placeholder="e.g. 2101234"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            ng-model="std_id" ng-blur="checkStudentID()">
                        <div class="ec-hint">Used before? Your saved details fill in automatically.</div>
                    </div>
                    <div class="col-md-6 ec-field">
                        <label for="ecTuRoll"><i class="fa-solid fa-building-columns"></i> TU Roll No</label>
                        <input class="form-control" id="ecTuRoll" inputmode="numeric" autocomplete="off"
                            placeholder="Tripura University roll number"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            ng-model="tu_roll">
                    </div>

                    <div class="col-4 col-md-3 ec-field">
                        <label for="ecTitle">Title<span class="req">*</span></label>
                        <select class="form-select" id="ecTitle" ng-model="title">
                            <option value="">Select</option>
                            <option>Mr.</option>
                            <option>Miss.</option>
                            <option>Mrs.</option>
                            <option>Sri</option>
                            <option>Smt.</option>
                        </select>
                    </div>
                    <div class="col-8 col-md-9 ec-field">
                        <label for="ecName"><i class="fa-solid fa-user"></i> Full name<span class="req">*</span></label>
                        <input class="form-control" id="ecName" autocomplete="name" placeholder="As it should appear on the cover"
                            style="text-transform: capitalize;" ng-model="name">
                    </div>

                    <div class="col-md-6 ec-field">
                        <label for="ecStdDept"><i class="fa-solid fa-building"></i> Department<span class="req">*</span></label>
                        <select class="form-select" id="ecStdDept" ng-model="std_dept" ng-change="sem && std_dept && course && subjects_view();">
                            <option value="">Select department</option>
                            <?php foreach ($depts as $dept) { ?>
                            <option><?php echo htmlspecialchars($dept); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 ec-field">
                        <label for="ecSem">Semester<span class="req">*</span></label>
                        <select class="form-select" id="ecSem" ng-model="sem" ng-change="sem && std_dept && course && subjects_view();">
                            <option value="">Select</option>
                            <option>1st</option>
                            <option>2nd</option>
                            <option>3rd</option>
                            <option>4th</option>
                            <option>5th</option>
                            <option>6th</option>
                            <option>7th</option>
                            <option>8th</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 ec-field">
                        <label for="ecSection">Section<span class="opt">(optional)</span></label>
                        <select class="form-select" id="ecSection" ng-model="section">
                            <option value="">Select</option>
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- 2. Course & submission -->
            <section class="ec-section">
                <h2 class="ec-section-title"><span class="ec-step">2</span> Course &amp; submission</h2>

                <div class="row g-3">
                    <div class="col-md-3 ec-field">
                        <label for="ecCourse"><i class="fa-solid fa-graduation-cap"></i> Course<span class="req">*</span></label>
                        <select class="form-select" id="ecCourse" ng-model="course" ng-change="subjects_view();">
                            <option value="">Select</option>
                            <option>B.Tech</option>
                            <option>Diploma</option>
                        </select>
                    </div>
                    <div class="col-md-6 ec-field">
                        <label for="ecSubject"><i class="fa-brands fa-discourse"></i> Subject title<span class="req">*</span></label>
                        <select class="form-select" id="ecSubject" ng-model="subjects" ng-change="subjects_code_view();">
                            <option value="">Select subject</option>
                            <option ng-repeat="s in subjectss_data">{{s.subjects}}</option>
                        </select>
                        <div class="ec-hint">Subjects load after you pick department, semester and course.</div>
                    </div>
                    <div class="col-md-3 ec-field">
                        <label for="ecCode">Subject code</label>
                        <input class="form-control" id="ecCode" placeholder="Auto-filled" ng-model="code" readonly>
                    </div>

                    <div class="col-md-6 ec-field">
                        <label for="ecPageType"><i class="fa-solid fa-file-lines"></i> Cover page type<span class="req">*</span></label>
                        <select class="form-select" id="ecPageType" ng-model="page_type">
                            <option value="">Select type</option>
                            <option>Assignment</option>
                            <option>Lab Assignment-I</option>
                            <option>Lab Assignment-II</option>
                            <option>Laboratory Record Book</option>
                        </select>
                    </div>
                    <div class="col-md-6 ec-field">
                        <label for="ecDos"><i class="fa-solid fa-calendar"></i> Submission date<span class="req">*</span></label>
                        <input class="form-control" id="ecDos" type="date" ng-model="dos">
                    </div>
                </div>
            </section>

            <!-- 3. Faculty -->
            <section class="ec-section">
                <h2 class="ec-section-title"><span class="ec-step">3</span> Submitted to</h2>

                <div class="row g-3">
                    <div class="col-md-4 ec-field">
                        <label for="ecFDept"><i class="fa-solid fa-building"></i> Faculty department<span class="req">*</span></label>
                        <select class="form-select" id="ecFDept" ng-model="f_dept" ng-change="tech_view();">
                            <option value="">Select department</option>
                            <?php foreach ($depts as $dept) { ?>
                            <option><?php echo htmlspecialchars($dept); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-5 ec-field">
                        <label for="ecFName"><i class="fa-solid fa-chalkboard-user"></i> Faculty name<span class="req">*</span></label>
                        <select class="form-select" id="ecFName" ng-model="f_name" ng-change="designation_view();">
                            <option value="">Select faculty</option>
                            <option ng-repeat="f in tech_data">{{f.teacher}}</option>
                        </select>
                    </div>
                    <div class="col-md-3 ec-field">
                        <label for="ecFDesignation"><i class="fa-solid fa-pen-nib"></i> Designation</label>
                        <input class="form-control" id="ecFDesignation" placeholder="Auto-filled" ng-model="f_designation" readonly>
                    </div>
                </div>

                <!-- lab submissions go to a second faculty member -->
                <div class="ec-extra mt-3"
                    ng-show="page_type === 'Lab Assignment-I' || page_type === 'Lab Assignment-II' || page_type === 'Laboratory Record Book'">
                    <div class="ec-extra-label"><i class="fa-solid fa-flask"></i> Second faculty for lab submissions</div>
                    <div class="row g-3 pb-3">
                        <div class="col-md-9 ec-field">
                            <label for="ecF2Name"><i class="fa-solid fa-chalkboard-user"></i> Faculty name</label>
                            <select class="form-select" id="ecF2Name" ng-model="f2_name" ng-change="designation_view_2();">
                                <option value="">Select faculty</option>
                                <option ng-repeat="f in tech_data">{{f.teacher}}</option>
                            </select>
                        </div>
                        <div class="col-md-3 ec-field">
                            <label for="ecF2Designation"><i class="fa-solid fa-pen-nib"></i> Designation</label>
                            <input class="form-control" id="ecF2Designation" placeholder="Auto-filled" ng-model="f2_designation" readonly>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Actions -->
            <div class="ec-actions">
                <button type="button" class="btn ec-btn ec-btn-ghost" ng-click="clearAll()">
                    <i class="fa-solid fa-rotate-left"></i> Clear all
                </button>
                <button type="button" class="btn ec-btn ec-btn-primary" ng-click="executeFunctions();">
                    <i class="fa-solid fa-file-arrow-down"></i> Download cover page
                </button>
            </div>
        </div>

        <footer class="ec-footer">
            &copy; 2026 Developed By
            <a role="button" data-bs-toggle="modal" data-bs-target="#developerModal">Mr. Sani Shil</a>
            <br>
            Activity Reviewed by
            <a href="https://www.linkedin.com/in/nabakallol-deb-b096b7375/" target="_blank" rel="noopener">Mr. Nabakallol Deb</a>
            &amp;
            <a href="https://www.instagram.com/paulsagar.29/" target="_blank" rel="noopener">Mr. Sagar Rudra Paul</a>
        </footer>
    </div>

    <!-- Developer links popup -->
    <div class="modal fade" id="developerModal" tabindex="-1" aria-labelledby="developerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="developerModalLabel">Mr. Sani Shil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-grid gap-2">
                    <a href="https://www.linkedin.com/in/sanishil" target="_blank" rel="noopener" class="btn btn-primary">
                        <i class="fa-brands fa-linkedin"></i> LinkedIn
                    </a>
                    <a href="https://sanishil.site.je/" target="_blank" rel="noopener" class="btn btn-dark">
                        <i class="fa-solid fa-briefcase"></i> Portfolio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden A4 cover page: printPDF() shows it briefly and renders it to the PDF -->
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
                <div  style="font-size: 16px; font-family: 'Times New Roman', Times, serif;">Academic Session: 2026–2027</div>
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
