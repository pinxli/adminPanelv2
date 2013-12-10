<?php
// Create connection
$con=mysqli_connect("localhost","monyhero_dev","2HM-bvn-pgQ-HyQ","monyhero_hk_dev");

// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  else{
	echo "traceeeee";
  }
?>