<?php 
  $page= "home.php";
  $p = "home";
  $slider= true ;
  $footer= true ;
 
  if (isset ($_GET['p']))
  {
     $p = $_GET['p'];
     switch ($p)
     {
      case "shop" : $page = "shop.php";
                    $slider = false ;
                    $footer = false ;
      break;

      case "contact" : $page = "contact.php";
                       $slider = false ;
                       $footer = false ;
      break;

      case "about" : $page = "about.php";
          $slider = false ;
          $footer = false ;
           break;

           case "blog" : $page = "blog.php";
                $slider = false ;
                $footer = false ;
             break;        
             
             
     }
  }

?>

<!DOCTYPE html>
<html>
   <?php include "include/head.php" ?>
   <body>
      
         <?php include "include/header.php" ?>
         <?php  if ($slider) include "include/slider.php" ?>
       
      </div>
    
      <?php include "$page"?>
      <?php if ($footer) include "include/footer.php"?>
   </body>
</html>