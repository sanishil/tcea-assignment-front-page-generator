<?php include '../session_check.php'; ?>
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
   <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
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
   <!-- Shared look (sidebar, cards, tables, forms) -->
   <link rel="stylesheet" href="../assets/css/modern.css" />
</head>

<body ng-app="myApp">
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


   <!-- ================= SIDEBAR ================= -->
   <aside class="sidebar" id="sidebar">
      <div class="sidebar-brand">
         <a href="#!"><img src="../img/final_tcea.jpg" alt="logo" class="img-fluid" /></a>
      </div>

      <nav class="sidebar-menu">
         <ul class="list-unstyled mb-0">
            <li>
               <a class="sidebar-link" href="#!"><i class="icon fa-solid fa-house"></i> Home</a>
            </li>
            <li>
               <a class="sidebar-link" href="#!/student"><i class="icon fa-solid fa-school"></i> Student Details</a>
            </li>

            <li class="sidebar-heading">Management</li>

            <li>
               <button class="sidebar-link" type="button" data-bs-toggle="collapse" data-bs-target="#menuMaster"
                  aria-expanded="false" aria-controls="menuMaster">
                  <i class="icon fa-solid fa-gear"></i> Master Entry
                  <i class="chevron fa-solid fa-chevron-right"></i>
               </button>
               <ul class="sidebar-submenu collapse" id="menuMaster">
                  <li>
                     <a class="sidebar-link" href="#!/dept"><i class="icon fa-solid fa-building"></i> Department Details</a>
                  </li>
                  <li>
                     <a class="sidebar-link" href="#!/subjects"><i class="icon fa-solid fa-book"></i> Subjects</a>
                  </li>
               </ul>
            </li>

            <li>
               <button class="sidebar-link" type="button" data-bs-toggle="collapse" data-bs-target="#menuTeacher"
                  aria-expanded="false" aria-controls="menuTeacher">
                  <i class="icon fa-solid fa-users"></i> Teacher Section
                  <i class="chevron fa-solid fa-chevron-right"></i>
               </button>
               <ul class="sidebar-submenu collapse" id="menuTeacher">
                  <li>
                     <a class="sidebar-link" href="#!/teacher"><i class="icon fa-solid fa-user"></i> Add Teacher</a>
                  </li>
               </ul>
            </li>


            <li class="sidebar-heading">Account</li>

            <li>
               <a class="sidebar-link" href="#!/change_pass"><i class="icon fa-solid fa-key"></i> Change Password</a>
            </li>
         </ul>
      </nav>

      <!-- ⭐ USER NAME + LOGOUT AT THE BOTTOM ⭐ -->
      <div class="sidebar-user">
         <div class="name"><i class="fa-solid fa-circle-user"></i> <?php echo htmlspecialchars(ucwords($_SESSION['name'])); ?></div>
         <a class="sidebar-link logout" href="../logout.php"><i class="icon fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
      </div>
   </aside>
   <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

   <!-- ================= MAIN ================= -->
   <div class="main-wrapper">
      <header class="topbar d-flex align-items-center">
         <button class="btn btn-light border d-lg-none me-3" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars"></i>
         </button>
         <span class="fw-semibold fs-5" id="pageTitle">Home</span>
         <span class="ms-auto d-none d-sm-inline" style="color: var(--accent);">
            <i class="fa-solid fa-user"></i>
            <b><?php echo "Welcome Back " . htmlspecialchars(ucwords($_SESSION['name'])); ?></b>
         </span>
      </header>

      <main class="main-content">
         <div ng-view></div>
      </main>

      <footer class="bg-light text-dark text-center shadow-sm py-3">
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
   </div>

   <script src="controller/main.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"></script>
   <script>
      // Sidebar: drawer toggle on small screens + highlight the current route
      (function () {
         var body = document.body;
         var links = document.querySelectorAll(".sidebar-menu a.sidebar-link");
         var pageTitle = document.getElementById("pageTitle");

         function closeSidebar() {
            body.classList.remove("sidebar-open");
         }

         document.getElementById("sidebarToggle").addEventListener("click", function () {
            body.classList.toggle("sidebar-open");
         });
         document.getElementById("sidebarBackdrop").addEventListener("click", closeSidebar);

         // Keep each group's chevron in sync with its collapse state
         document.querySelectorAll(".sidebar-submenu").forEach(function (submenu) {
            var toggle = document.querySelector('[data-bs-target="#' + submenu.id + '"]');
            submenu.addEventListener("show.bs.collapse", function () { toggle.setAttribute("aria-expanded", "true"); });
            submenu.addEventListener("hide.bs.collapse", function () { toggle.setAttribute("aria-expanded", "false"); });
         });

         function setActive() {
            var hash = location.hash || "#!";
            if (hash === "#!/" || hash === "#!/dash") hash = "#!";

            links.forEach(function (link) {
               var isActive = link.getAttribute("href") === hash;
               link.classList.toggle("active", isActive);
               if (!isActive) return;

               pageTitle.textContent = link.textContent.trim();

               // Open the group that contains the active page
               var submenu = link.closest(".sidebar-submenu");
               if (submenu && !submenu.classList.contains("show")) {
                  new bootstrap.Collapse(submenu, { toggle: true });
               }
            });
            closeSidebar();
         }

         window.addEventListener("hashchange", setActive);
         setActive();
      })();
   </script>
<script src="../assets/js/idle_logout.js"></script>
</body>

</html>
