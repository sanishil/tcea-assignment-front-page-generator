<?php
include "../api/connect.php";
?>
<div class="container-fluid" ng-init="subject_view();">
    <div class="row" style="padding-top: 20px;">
        <div class="col-lg-12 mb-3">
            <div class="shadow-lg p-4 rounded">
                <h5 class="d-flex justify-content-between align-items-center">
                    <span>Subjects Section</span>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                            class="fa-solid fa-plus"></i></button>
                </h5>
                <div class="alert alert-success" ng-if="deleteMessage">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>
                    {{ deleteMessage }}
                </div>
                <div class="row pt-2">
                    <div class="col-md-4 offset-md-8">
                        <input class="form-control" type="search" ng-model="search" placeholder="Search"
                            aria-label="Search">
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive"><table class="table table-borderless">
                        <thead class="table-light border-dark">
                            <tr class="text-center">
                                <th style="width: 10%;">#</th>
                                <th style="width: 20%;">Subjects Name</th>
                                <th style="width: 13%;">Subjects Code</th>
                                <th style="width: 10%;">Semester</th>
                                <th style="width: 25%;">Department</th>
                                <th style="width: 12%;">Course</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr ng-repeat="x in subject_data | filter:search">
                                <th >{{$index+1}}</th>
                                <td>{{x.subjects}}</td>
                                <td>{{x.code}}</td>
                                <td>{{x.sem}}</td>
                                <td>{{x.dept_name}}</td>
                                <td>{{x.course}}</td>
                                <td class="text-center"><button class="btn btn-warning" ng-click="subject_edit(x.id)" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                            class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger" ng-click="subject_delete(x.id);"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                    </table></div>
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4 text-center text-danger" ng-if="teacher_data.length === 0">
                            <h3>{{ noDataMessage }} !</h3> <!-- ✅ Fixed reference to teacher_data -->
                        </div>
                        <div class="col-lg-4"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div>

<!-- Modal sections -->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Create New
                    Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <input class="form-control" ng-model="subjects">
                            <label for="floatingTextarea">
                                Subject Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <input class="form-control" ng-model="code">
                            <label for="floatingTextarea">
                                Subject Code</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelectGrid"
                                aria-label="Floating label select example" ng-model="sem">
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
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelectGrid"
                                aria-label="Floating label select example" ng-model="dept_name">
                                <option selected></option>
                                <?php
                                $sql = "SELECT dept_name FROM dept";
                                $result = $con->query($sql);
                                while ($row = $result->fetch_assoc()) { ?>
                                    <option><?php echo $row['dept_name']; ?></option>
                                <?php } ?>
                            </select>
                            <label for="floatingTextarea">
                                Department </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelectGrid"
                                aria-label="Floating label select example" ng-model="course">
                                <option selected></option>
                                <option>B.Tech</option>
                                <option>Diploma</option>
                            </select>
                            <label for="floatingTextarea">
                                Course </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>
                    Close</button>
                <button type="button" class="btn btn-success" ng-click="subject_entry();"><i
                        class="fa-solid fa-floppy-disk"></i> Create</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Create New
                    Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <input class="form-control" ng-model="subjects">
                            <label for="floatingTextarea">
                                Subject Name</label>
                            <input type="hidden" class="form-control" ng-model="idd">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <input class="form-control" ng-model="code">
                            <label for="floatingTextarea">
                                Subject Code</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelectGrid"
                                aria-label="Floating label select example" ng-model="sem">
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
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelectGrid"
                                aria-label="Floating label select example" ng-model="dept_name">
                                <option selected></option>
                                <?php
                                $sql = "SELECT dept_name FROM dept";
                                $result = $con->query($sql);
                                while ($row = $result->fetch_assoc()) { ?>
                                    <option><?php echo $row['dept_name']; ?></option>
                                <?php } ?>
                            </select>
                            <label for="floatingTextarea">
                                Department </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelectGrid"
                                aria-label="Floating label select example" ng-model="course">
                                <option selected></option>
                                <option>B.Tech</option>
                                <option>Diploma</option>
                            </select>
                            <label for="floatingTextarea">
                                Course </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>
                    Close</button>
                <button type="button" class="btn btn-success" ng-click="subject_update()"><i class="fa-solid fa-download"></i>
                Update</button>
            </div>
        </div>
    </div>
  </div>