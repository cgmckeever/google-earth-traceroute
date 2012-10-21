<?php


$html .= page_open();

$html .="<H2>Dynamic Controlled Google Earth Content</H2>";

$html .= "<TABLE width=\"65%\"><TR><TD>";

$html .= "Google Earth has the ability to import data directly from Network Link files.  There are two types of updates that can be controlled via this method, full dataset updates and view based (<I>'where am I looking'</I>) updates.  Both of these are completely controlled by Google Earth.<BR><BR>

However, what if the user wants to change a different parameter and display the results?  Google Earth would need to be set-up with a completely separte Network Link describing these parameters.  This makes the user experience not as intuitive as standard browser navigation.<BR><BR>

What if any search criteria that the user specifies could be automatically pushed to Google Earth without the need for another KML download?  This could be achieved with the concept of a dynamically linked KML.<BR><BR>

The proof of concept is a simple traceroute interface.  After starting the <A href=\"http:www.potf.net/traceroute\" target=\"_blank\">dynamic linked session</A>, entering a host (ie google.com), Google Earth will be automatically panned to the internet routing of your requests.<BR><BR>

In addition, to show off some next level implementation, you can restart the trace tour, or completely reset the results, clearing Google Earth's data cache of the points sent to it.<BR><BR>

Taking this one step further, two users, remotely situated could control the viewing experience of each other Google Earth simply by establishing a shared session.  Users could surf the web and see results simultaneously while sitting thousands of miles away.<BR><BR>

The ultimate use of this could be to integrate any web page content (ie Google Search Results) to update google Earth with Geospatial information pertinent to page that you are currently viewing.";

$html .= "</TD></TR></TABLE>";

$html .= page_close();


?>
