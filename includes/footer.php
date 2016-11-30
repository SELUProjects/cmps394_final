<?php
if(!defined('MyConst')) {
   die('Direct access not permitted');
}
?>
<!-- close the wrapper -->
</div>
<div id="footer">

   <ul> 

    <li><a href="">About</a></li> 
    <li><a href="">Help</a></li> 
    <li><a href="">Contact Us</a></li> 

   </ul> 

</div>

<script
  src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
  integrity="sha256-/SIrNqv8h6QGKDuNoLGA4iret+kyesCkHGzVUUV0shc="
  crossorigin="anonymous"></script>
<script 
    src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" 
    crossorigin="anonymous"></script>
<?php if (isset($js)) {
    foreach ($js as $value) {
      echo '<script src="' . $value . '"></script>';
    }    
} ?>
