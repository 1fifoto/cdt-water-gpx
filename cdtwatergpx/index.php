<?php
    
    $jsonpContents = file_get_contents("water-points.js"); // read in JSON with padding string
    $contents = substr(substr($jsonpContents,16),0,-1); // chop off JSON padding
    $points = json_decode($contents, true); // convert from JSON to array

    $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><gpx></gpx>");

    foreach ($points as $point) {
        $point[8] = "-".$point[8]; // add minus sign to GPS (W)
        // validate latitude and longitude uniquely
        // See: http://stackoverflow.com/questions/7549669/php-validate-latitude-longitude-strings-in-decimal-format
        if (preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $point[7]) == 1 &&
            preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $point[8]) == 1) {

            $wpt = $xml->addChild('wpt'); // create waypoint child element
            $wpt->addAttribute('lat', $point[7]); // add latitude attribute to waypoint element
            $wpt->addAttribute('lon', $point[8]); // addlongitude attribute to waypoint element

            if ($point[5] != "") {
                $wpt->addChild('time'); // create time child of waypoint
                $date = DateTime::createFromFormat("D M d H:i:s P Y", $point[5]); // Fri Apr 11 02:00:00 GMT-05:00 2014
                $date->setTimeZone(new DateTimeZone('America/Denver')); // Set mountain time zone
                $date->setTime(12, 0, 0); // Set noon
                $wpt->time = $date->format("Y-m-d\TH:i:sP"); // convert to ISO8601: 2014-04-11T02:00:00Z
            }

            // Some of the names and desc may contain an ampersand.
            // See: http://stackoverflow.com/questions/552957/rationale-behind-simplexmlelements-handling-of-text-values-in-addchild-and-adda

            $wpt->addChild('name'); // create name child of waypoint
            $wpt->name = $point[0] . ": " . $point[3]; // Concatenate Jonathan Ley map number to water source name

            $wpt->addChild('desc'); // create description child of waypoint
            $wpt->desc = $point[4];
        } else {
            // print "Missing lat and lon: ".$point[0]." ".$point[1]."\n";
        }
    }
        
    header("Content-type:application/gpx+xml");
    header("Content-Disposition:attachment;filename=cdtwater.gpx");
    print($xml->asXML());

?>
