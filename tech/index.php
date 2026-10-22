<?php 
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id']!=true){
   session_unset();
   session_destroy();
   header("location:../login.html");
   mysqli_close($con);
   exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <link rel="icon" href="../img/final_tcea.jpg" />
   <!-- <link rel="stylesheet" href="../assets/fw/css/all.css">
      <link rel="stylesheet" href="../assets/fw/css/all.min.css"> -->
   <!-- Font Awesome 6 Free -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
   <script src="../assets/js/angular.min.js"></script>
   <script src="../assets/js/angular-router.min.js"></script>
   <script src="../assets/js/angular-cookies.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
   <title>EduCover Page</title>
   <!-- <style>
      body {
         font-family: "Segoe UI", Arial, sans-serif;
         /* text-align: center; */
         background: #f0f0f0;
         margin: 0;
         padding: 30px;
      }

      h2 {
         color: #222;
         font-size: 28px;
         margin-bottom: 30px;
      }

      .chart-container {
         position: relative;
         width: 90%;
         max-width: 800px;
         /* Increased width */
         height: 500px;
         /* Increased height */
         margin: 0 auto;
         background: #fff;
         border-radius: 20px;
         padding: 30px;
         box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      }

      canvas {
         width: 100% !important;
         height: 100% !important;
      }
   </style> -->
</head>

<body ng-app="myApp" style="padding-bottom: 100px; background-color: #edf5f5">
   <!-- bootsrap icon -->
   <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
         <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
         <path
            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
         <path
            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
      </symbol>
   </svg>
   <!-- bootsrap icon -->

   <div>

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm py-3">
         <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
               <img src="../img/final_tcea.jpg" alt="logo" class="img-fluid" style="max-height: 100px" />
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
               data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
               aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">

               <!-- YOUR ORIGINAL CENTER MENU -->
               <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fs-5">
                  <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="#!"><i class="fa-solid fa-house"></i> Home</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#!/student"><i class="fa-solid fa-school"></i> Student Details</a>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i>
                        Master Entry
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                        <li>
                           <a class="dropdown-item" href="#!/dept"><i class="fa-solid fa-building"></i> Department
                              Details</a>
                        </li>
                        <li>
                           <hr class="dropdown-divider" />
                        </li>
                        <li>
                           <a class="dropdown-item" href="#!/subjects"><i class="fa-solid fa-book"></i> Subjects</a>
                        </li>
                     </ul>
                  </li>

                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-users"></i> Teacher Section
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li>
                           <a class="dropdown-item" href="#!/teacher"><i class="fa-solid fa-user"></i> Add Teacher</a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#!/change_pass"><i class="fa-solid fa-key"></i> Change Password!</a>
                  </li>
                  
               </ul>

               <!-- ⭐ USER NAME ON RIGHT SIDE ⭐ -->
               <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fs-5">
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false" style="color: #818CF8;">
                        <i class="fa-solid fa-user"></i> <b><?php echo "Welcome Back " . ucwords($_SESSION['name']); ?>
                        </b></a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li>
                           <a class="dropdown-item" href="../logout.php"><i
                                 class="fa-solid fa-arrow-right-from-bracket"></i> Log
                              Out</a>
                        </li>
                     </ul>
                  </li>

               </ul>

            </div>
         </div>
      </nav>


      <div ng-view></div>

      <footer class="bg-light text-dark text-center shadow-sm py-3 fixed-bottom">
         <p class="mb-0">
            <i class="fa-solid fa-copyright"></i> 2025 Developed By
            <a href="https://www.linkedin.com/in/sanishil" class="text-dark text-decoration-none" target="_blank">
               Mr. Sani Shil
            </a> | Activity Reviewed by
        <a href="https://www.instagram.com/paulsagar28?igsh=MW9va3k4Z2M1OGl2bw=="
          class="text-dark fw-semibold text-decoration-none" target="_blank">
          Mr. Sagar Rudra Paul
        </a>
          

         </p>
      </footer>
      <script src="controller/main.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
         crossorigin="anonymous"></script>
      <!-- Option 2: Separate Popper and Bootstrap JS -->
      <!--
         <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
         -->
   </div>
</body>

</html>