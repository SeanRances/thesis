<?php
function passarray($test)
{
  for($i=0;$i<count($test);$i++)
  {
    $test[$i];
    if($test[$i] == "January")
    {
      $test[$i] = 1;
    }
    elseif($test[$i] == "April")
    {
      $test[$i] = 4;
    }
    elseif($test[$i] == "May")
    {
      $test[$i] = 5;
    }
  }
  return $test;
}
 ?>

 <html>
 <head>
 </head>
 <body>

<?php
$test = array("April", "May", "January");
$text = passarray($test);
sort($text);

for($i=0;$i<count($text);$i++)
{
  echo $text[$i];
}

 ?>
   </form>
 </body>
 </html>
