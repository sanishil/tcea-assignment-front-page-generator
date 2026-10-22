<?php
include "../api/connect.php";
?>

<div class="container-fluid" ng-init="teacher_view();">
    <div class="row" style="padding-top: 20px;">
        <div class="col-lg-12 mb-3">
            <div class="shadow-lg p-4 rounded">
                <h5 class="d-flex justify-content-between align-items-center">
                    <span>Teacher Section</span>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="fa-solid fa-plus"></i>
                    </button>
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
                    <div class="table-responsive"><table class="table">
                        <thead class="table-light border-dark">
                            <tr class="text-center">
                                <th style="width: 10%;">#</th>
                                <th style="width: 25%;">Teacher</th>
                                <th style="width: 25%;">Designation</th>
                                <th style="width: 25%;">Department</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr ng-repeat="x in teacher_data | filter:search">
                                <th>{{$index+1}}</th>
                                <td>{{x.teacher}}</td>
                                <td>{{x.designation}}</td>
                                <td>{{x.dept_name}}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" ng-click="teacher_edit(x.id)"><i
                                            class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger" ng-click="teacher_delete(x.id);"><i
                                            class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table></div> <!-- ✅ Fixed missing closing table tag -->

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

<!-- Modal for Adding Teacher -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input class="form-control" ng-model="teacher">
                    <label>Teacher Name</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectGrid" aria-label="Select Department"
                        ng-model="designation">
                        <option selected></option>
                        <option>H.O.D & Assistant Professor</option>
                        <option>H.O.D & Associate Professor</option>
                        <option>Associate Professor</option>
                        <option>Assistant Professor</option>
                        <option>Lecturer</option>
                        <option>Guest Lecturer</option>
                    </select>
                    <label for="floatingSelectGrid">Designation</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectGrid" aria-label="Select Department"
                        ng-model="dept_name">
                        <option selected></option>
                        <?php
                        $sql = "SELECT dept_name FROM dept";
                        $result = $con->query($sql);
                        while ($row = $result->fetch_assoc()) { ?>
                            <option><?php echo $row['dept_name']; ?></option>
                        <?php } ?>
                    </select>
                    <label for="floatingSelectGrid">Department</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>
                    Close</button>
                <button type="button" class="btn btn-success" ng-click="teacher_entry();"><i
                        class="fa-solid fa-floppy-disk"></i> Create</button>
            </div>
        </div>
    </div>
</div>


<!-- edit Modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input class="form-control" ng-model="teacher">
                    <label>Teacher Name</label>
                    <input type="hidden" class="form-control" ng-model="idd">
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectGrid" aria-label="Select Department"
                        ng-model="designation">
                        <option selected></option>
                        <option>H.O.D & Assistant Professor</option>
                        <option>H.O.D & Associate Professor</option>
                        <option>Associate Professor</option>
                        <option>Assistant Professor</option>
                        <option>Lecturer</option>
                        <option>Guest Lecturer</option>
                    </select>
                    <label for="floatingSelectGrid">Designation</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectGrid" aria-label="Select Department"
                        ng-model="dept_name">
                        <option selected></option>
                        <?php
                        $sql = "SELECT dept_name FROM dept";
                        $result = $con->query($sql);
                        while ($row = $result->fetch_assoc()) { ?>
                            <option><?php echo $row['dept_name']; ?></option>
                        <?php } ?>
                    </select>
                    <label for="floatingSelectGrid">Department</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>
                    Close</button>
                <button type="button" class="btn btn-success" ng-click="teacher_update()"><i
                        class="fa-solid fa-download"></i>
                    Update</button>
            </div>
        </div>
    </div>
</div>

<?php
$con->close();
?>