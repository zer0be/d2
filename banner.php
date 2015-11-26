<?php 

$banners = array(
    '',
    '_b',
);
$img = array_rand($banners);

?>

<a href='index.php'>
  <div class="container-fluid descent-header hidden-xs" style="background: url('img/descentbanner<?php echo $banners[$img]; ?>.png') no-repeat center;">
  </div>

  <div class="visible-xs-block">
    <img src="img/descentbanner_mobile<?php echo $banners[$img]; ?>.jpg" width="100%" />
  </div>
</a>
