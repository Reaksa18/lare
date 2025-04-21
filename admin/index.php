<?php    

     $page="dashboard.php";
     $p="dashboard";
     $footer= true ;
     if (isset($_GET['p']))
     {
               $p= $_GET['p'];
               switch($p)
               {
                case "slideshow" : $page = "slideshow.php" ;
                $footer = false ;
                break ;
                case "product" : $page = "product.php" ;
                $footer = false ;
                break ;

               }


     }

?>

<!DOCTYPE html>
<html lang="en">
 
    <?php include "include/head.php" ?>

  <body>
    <div class="wrapper">
    
     <?php include "include/sidebar.php" ?>

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
          
          
    <?php include "include/logoheader.php" ?>

          </div>
         
          <?php include "include/navbar.php" ?>

        </div>

        <?php include "$page" ?>
    
        <?php include "include/footer.php" ?>
        
      </div>

      <?php include "include/customtemplate.php" ?>

    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>

    <?php include "include/script.php" ?>

  </body>
</html>
