<?php
    
//    $jsonpContents = file_get_contents("water-points.js"); // read in JSON with padding string
    $jsonpContents = file_get_contents("https://script.google.com/macros/s/AKfycbwHHCmlw8Y9PSEwIz5N5E_1pqhw9wsRDFDLEF2lV51BvhEvnXw/exec?jsonp=plotWaterPoints"); // directly read in JSON with padding string
    $contents = substr(substr($jsonpContents,16),0,-1); // chop off JSON padding to create pure JSON
    $points = json_decode($contents, true); // convert from pure JSON to array

    $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><gpx xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.topografix.com/GPX/1/1\" xsi:schemaLocation=\"http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd\" version=\"1.1\" creator=\"bwatt@1fifoto.com\" ></gpx>");

    foreach ($points as $point) {
        $point[8] = "-".$point[8]; // add minus sign to GPS (W)
        // Validate latitude and longitude uniquely
        // See: http://stackoverflow.com/questions/7549669/php-validate-latitude-longitude-strings-in-decimal-format
        if (preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $point[7]) == 1 &&
            preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $point[8]) == 1) {

            $wpt = $xml->addChild('wpt'); // create waypoint child element
            $wpt->addAttribute('lat', $point[7]); // add latitude attribute to waypoint element
            $wpt->addAttribute('lon', $point[8]); // add longitude attribute to waypoint element

            if ($point[5] != "") {
                $wpt->addChild('time'); // create time child of waypoint
                $date = DateTime::createFromFormat("D M d H:i:s P Y", $point[5]); // Fri Apr 11 02:00:00 GMT-05:00 2014
                $date->setTimeZone(new DateTimeZone('America/Denver')); // Set US Mountain time zone
                $date->setTime(12, 0, 0); // Set noon
                $wpt->time = $date->format("Y-m-d\TH:i:sP"); // convert to ISO8601: 2014-04-11T12:00:00-06:00
            }

            // Some of the names and desc may contain an ampersand. Do not use
            // addChild(element,string), instead use addChild(element) followed
            // by assignment to the element's string.
            // See: http://stackoverflow.com/questions/552957/rationale-behind-simplexmlelements-handling-of-text-values-in-addchild-and-adda

            $wpt->addChild('name'); // create name child of waypoint
            $wpt->name = "";
            if ($point[1] != "" && $point[0] != "") {
                // Both: Concatenate Bear Creek mile number,  "|", Jonathan Ley map number and ": "
                $wpt->name .= $point[1] . "|" . $point[0] . ": "; 
            } elseif ($point[1] != "") {
                // Only BC: Concatenate Bear Creek mile number and ": "
                $wpt->name .= $point[1] . ": "; 
            } elseif ($point[0] != "") {
                // Only Ley: Concatenate Jonathan Ley map number and ": "
                $wpt->name .= $point[0] . ": ";
            }
            $wpt->name .= $point[3]; // Concatenate water source name

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
