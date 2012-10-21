<?php

set_time_limit(0);
$setting['database'] = 'mapmixdb';
// $SID = $_REQUEST['PHPSESSID'];
$page_array = array('session'=>true);

if ($_REQUEST['reset']==1 AND is_array($_SESSION['result'])){
  $result = $_SESSION['result'];
  foreach($result AS $leg){
    $leg['process'] = false;
    $leg['zoom'] = false;
    $update_result[] = $leg;
  }

  $_SESSION['result'] = $update_result;
}


if ($_REQUEST['submit']==1){
  $traceroute = $_REQUEST['traceroute'];
  $trace_type = $_REQUEST['trace_type'];
	
  if (strlen($_REQUEST['input']) != 0){
    $trace_type = 'linux';
    $input = $_REQUEST['input'];
    exec("traceroute -w 2 -q 1 {$input}",$traceroute_array);
  }else $traceroute_array = explode("\n",$traceroute);

  switch ($trace_type) {
    case "linux":
      foreach($traceroute_array AS $leg){
        $leg = string_normalize($leg);
	$parts = explode(" ",$leg);
	$parts[2] = str_replace("(","",$parts[2]);
	$parts[2] = str_replace(")","",$parts[2]);
	$geocode = hostip_geocode($parts[2]);
	//print $geocode;
	$parts['host'] = $parts[1];
	$parts['ip'] = $parts[2];
	$parts['time'] = $parts[3];
	$parts['unit'] = $parts[4];
	$parts['longitude'] = $geocode['longitude'];
	$parts['latitude'] = $geocode['latitude'];
	$parts['country'] = $geocode['country'];
	$parts['city'] = $geocode['city'];
	$parts['process'] = false;
	$leg_array[] = $parts;
     }
     break;
     case "ms-dos":
       foreach($traceroute_array AS $leg){
         $leg = string_normalize($leg);
         $parts = explode(" ",$leg);
         $parts[8] = str_replace("[","",$parts[8]);
         $parts[8] = str_replace("]","",$parts[8]);

	 if (strlen($parts[8]) != 0){
	   $geocode = hostip_geocode($parts[8]);
	 } else $geocode = hostip_geocode($parts[7]);

	 $parts['host'] = $parts[7];
         $parts['ip'] = $parts[8];
         $parts['time'] = $parts[1];
         $parts['unit'] = $parts[2];
         $parts['longitude'] = $geocode['longitude'];
         $parts['latitude'] = $geocode['latitude'];
         $parts['country'] = $geocode['country'];
         $parts['city'] = $geocode['city'];
         $parts['process'] = false;
         $leg_array[] = $parts;
       }
       break;
    default:
       break;
  }
  $_SESSION['zoom'] = $_REQUEST['zoom'];
  $_SESSION['result'] = $leg_array;

}elseif ($_REQUEST['reset'] != 1){
  $_SESSION['result'] = NULL;
}


if (strlen($traceroute) == 0){

  //hasbro.com
  $traceroute = " 1  207.210.210.193 (207.210.210.193)  0.980 ms\n";
  $traceroute .= " 2  206.123.64.53 (206.123.64.53)  0.540 ms\n";
  $traceroute .= " 3  206.123.64.29 (206.123.64.29)  0.470 ms\n";
  $traceroute .= " 4  206.123.64.22 (206.123.64.22)  0.466 ms\n";
  $traceroute .= " 5  border4.g3-2.colo4dallas-2.ext1.dal.pnap.net (216.52.189.9)  0.985 ms\n";
  $traceroute .= " 6  core4.ge2-0-bbnet1.ext1.dal.pnap.net (216.52.191.35)  0.700 ms\n";
  $traceroute .= " 7  12.119.136.21 (12.119.136.21)  0.961 ms\n";
  $traceroute .= " 8  gar3-p3100.dlstx.ip.att.net (12.123.196.98)  45.495 ms MPLS Label=32093 CoS=5 TTL=1 S=0\n";
  $traceroute .= " 9  tbr2-cl6.sl9mo.ip.att.net (12.122.10.89)  46.580 ms MPLS Label=32218 CoS=5 TTL=1 S=0\n";
  $traceroute .= "10  tbr2-cl7.cgcil.ip.att.net (12.122.10.45)  45.784 ms MPLS Label=32558 CoS=5 TTL=1 S=0\n";
  $traceroute .= "11  tbr2-cl5.cb1ma.ip.att.net (12.122.10.105)  46.477 ms MPLS Label=32123 CoS=5 TTL=1 S=0\n";
  $traceroute .= "12  gbr1-p100.cb1ma.ip.att.net (12.122.5.58)  106.195 ms MPLS Label=30 CoS=5 TTL=1 S=0\n";
  $traceroute .= "13  gar2-p360.cb1ma.ip.att.net (12.123.40.137)  44.973 ms\n";
  $traceroute .= "14  12.125.33.10 (12.125.33.10)  49.225 ms\n";
  $traceroute .= "15  *\n";
  $traceroute .= "16  *\n";
  $traceroute .= "17  *\n";
  $traceroute .= "18  *\n";
  $traceroute .= "19  *\n";
  $traceroute .= "20  *\n";
  $traceroute .= "21  *\n";
  $traceroute .= "22  *\n";
  $traceroute .= "23  *\n";
  $traceroute .= "24  *\n";
  $traceroute .= "25  *\n";
  
  $trace_type = "linux";
}

$html .= "<TABLE width=\"50%\"><TR><TD>";
$html .= "<H2>Traceroute -> Google Earth</H2><A href=\"{$_SERVER['PHP_SELF']}\">Refresh/Clear Trace</A><BR><BR>";
$html .= "This utility will interact with Google Earth via a <A href=\"session-kml.php\">Linked KML</A> to feed it Geo-Locations of a traceroute.  ";
$html .= "Enter in a traceroute (ms-dos or linux format) or an IP/Host. <BR> ";
$html .= "You can then control Google Earth from this page.<BR>";
$html .= "</TD></TR></TABLE>\n";

$html .= "** Trace by Host will take some time and will point-of-origin from this server **\n<BR>";
$html .= "** Doesnt work so well with international host lookups - sorry **\n<BR>";
$html .= "** For best results - do not save to to <I>My Documents</I> when exiting GE **\n<BR>";
$html .= "** If GE seems to have timed-out (dialogue pop-up)  after the traceresult (this window) has been updated, hit refresh on the <I>TraceRoute</I> Link **\n<BR>";


$html .= "<FORM method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\n";
$html .= "<TABLE>\n";

$html .= "<TR><TD valign=top>Host/IP: <INPUT type=\"text\" name=\"input\" size=\"30\" value=\"{$input}\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>-OR-</B></TD><TD>&nbsp;&nbsp;&nbsp;</TD><TD><A href=\"{$_SERVER['PHP_SELF']}?reset=1\">Replay TraceRoute Tour</A></TD></TD>\n";

if ($trace_type == 'linux'){
  $linux = "checked";
}else $msdos = "checked";

if ($_REQUEST['zoom'] == 1) $zoom = "checked";

$html .= "<TR><TD valign=top>Traceroute: <INPUT type=\"radio\" name=\"trace_type\" value=\"linux\" " .$linux . " >Linux</INPUT><INPUT type=\"radio\" name=\"trace_type\" value=\"ms-dos\" " .$msdos. " >MS-DOS</INPUT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type=\"checkbox\" name=\"zoom\" value=\"1\" $zoom >Zoom at each leg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type=\"submit\" value=\"Trace\"></TD><TD></TD><TD></TD></TR>\n";
$html .= "<TR><TD valign=top><TEXTAREA name=\"traceroute\" rows=\"20\" cols=\"60\">$traceroute</TEXTAREA></TD>\n";

$html .= "<TD>&nbsp;&nbsp;&nbsp;</TD>";

$html .= "<TD valign=top>\n";
$html .= "<TABLE><TR><TD valign=middle>GeoIP Powered by:</TD><TD valign=middle><A href=\"http://www.hostip.info/\" target=\"_blank\"><img src=\"http://www.hostip.info/images/button-86x18.gif\" border=0></A></TD></TR></TABLE><BR>";
$html .= "<B>General Syntax - see default for example.</B><BR><BR>\n";
$html .= "<B>Linux Traceroute (traceroute host)</B><BR>\n";
$html .= "<TABLE border=1><TR><TD><I>1</I></TD><TD><I>64.238.216.246</I></TD><TD><I>(64.238.216.246)</I></TD><TD><I> 0.079 ms</I></TD></TR>\n";
$html .= "<TR><TD>trace-leg</TD><TD>IP/Host</TD><TD>IP</TD><TD>response time</TD></TR></TABLE>\n";

$html .= "<BR><B>MS-DOS Traceroute (tracert host)</B><BR>\n";
$html .= "<TABLE border=1><TR><TD><I>1</I></TD><TD><I>2 ms</I></TD><TD><I>1 ms</I></TD><TD><I><10 ms</I></TD><TD><I>64.238.216.246</I></TD></TR>\n";
$html .= "<TR><TD>trace-leg</TD><TD>response-1</TD><TD>response-2</TD><TD>response-3</TD><TD>IP</TD></TR></TABLE>\n";
$html .= "</TD></TR>\n";

$html .= "</TABLE>\n";
$html .= "<INPUT type=\"hidden\" name=\"submit\" value=\"1\">\n";
$html .= "</FORM>\n";


$html .= "</TD></TR></TABLE>\n";

if(is_array($leg_array) != 0){
  reset($leg_array);
  $html .= "<TABLE border=1>";
  $html .= "<TR><TD>City</TD><TD>Country</TD><TD>Latitude</TD><TD>Longitude</TD><TD>Host/IP</TD><TD>IP</TD><TD>Response Time</TD></TR>";
  foreach($leg_array AS $leg){
    $html .= "<TR><TD>{$leg['city']}&nbsp;</TD><TD>{$leg['country']}&nbsp;</TD><TD>{$leg['latitude']}&nbsp;</TD><TD>{$leg['longitude']}&nbsp;</TD><TD>{$leg['host']}&nbsp;</TD><TD>{$leg['ip']}&nbsp;</TD><TD>{$leg['time']}{$leg['unit']}&nbsp;</TD></TR>\n";
  }
  $html .= "</TABLE>";
}


print $html;

?>
