<?php

$SID = $_REQUEST['PHPSESSID'];
$directory = "traceroute";


$output .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$output .= "<kml xmlns=\"http://earth.google.com/kml/2.0\">";
$output .= "<NetworkLink>";
$output .= "<name>Traceroute</name>";
$output .= "<flyToView>1</flyToView>";
$output .= "<Url>";
$output .= "    <href>http://www.potf.net/{$directory}/index.kmq?PHPSESSID={$SID}</href>";
$output .= "    <viewRefreshMode>onStop</viewRefreshMode>";
$output .= "    <viewRefreshTime>1</viewRefreshTime>";
$output .= "  </Url>";
$output .= "</NetworkLink>";
$output .= "</kml>";

header("content-type: application/vnd.google-earth.kml+xml");
// force a "save as" window to open
header("content-disposition: attachment; filename=traceroute.kml");
// send the data

print $output;

exit();

?>
