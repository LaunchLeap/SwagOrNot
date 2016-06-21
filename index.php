<?php
include('config.ini.php');

/* Write code below */


//todo : treat all AJAX actions here
if(!empty($_POST['action'])){
  header('Content-type: application/json');
  $response = array();
  
  //treat all actions
  
  echo json_encode($response);
  die();
}
//display the page
else{


}

?>
