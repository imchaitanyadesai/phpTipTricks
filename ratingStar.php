<?php
$cfg_min_stars = 1;
$cfg_max_stars = 5;
$temp_stars = 3.8;
for($i=$cfg_min_stars; $i<=$cfg_max_stars; $i++) {
  //echo $temp_stars;
  if ($temp_stars >= 1) {
    echo '<img src="Star (Full).png"/>';
    $temp_stars--;
  }else {
    if ($temp_stars >= 0.5) {
     echo '<img src="Star (Half Full).png"/>';
      $temp_stars -= 0.5;
    }else {
      echo '<img src="Star (Empty).png"/>';
    }
  }

}
?>
