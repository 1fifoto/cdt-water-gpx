// The name of the spreadsheet from the browser location bar.
// Copy after 'key=' until before the next URL parameter beginning w/&
var SPREADSHEET_ID = '0AgLF16H5kj44dF96UEJpRjFOWkRQOHQ1a21NYlVrMlE';

// The name of the sheet, displayed in a tab at the bottom of the spreadsheet.
// Default is 'Sheet1' if it's the first sheet.
var SHEET_NAME = 'WATER';

function doGet(request) {
  
    // Access Google docs spreadsheet
    var values = SpreadsheetApp.openById(SPREADSHEET_ID).getSheetByName(SHEET_NAME).getDataRange().getValues();
  
    // Create XML document containing gpx root element with namespaces
    var doc = XmlService.parse("<?xml version=\"1.0\" encoding=\"UTF-8\"?><gpx xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.topografix.com/GPX/1/1\" xsi:schemaLocation=\"http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd\" version=\"1.1\" creator=\"bwatt@1fifoto.com\"></gpx>");
    var gpx = doc.getRootElement();
    var ns = XmlService.getNamespace("http://www.topografix.com/GPX/1/1")
  
    // Validate latitude and longitude uniquely
    // See: http://stackoverflow.com/questions/7549669/php-validate-latitude-longitude-strings-in-decimal-format
    var latPat = /^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/;
    var lonPat = /^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/;
    
    // Loop through all rows in spreadsheet
    for (var i = 0; i <values.length; i++) {
      
        values[i][8] = "-"+values[i][8]; // add minus sign to GPS (W)
      
        // Validate latitude and longitude uniquely
        if (latPat.test(values[i][7]) && lonPat.test(values[i][8])) {
        
            var wpt = XmlService.createElement("wpt", ns) // create waypoint element
                .setAttribute("lat", values[i][7]) // add latitude attribute to waypoint element
                .setAttribute("lon", values[i][8]); // add longitude attribute to waypoint element
            gpx.addContent(wpt); // add waypoint as child element of gpx
           
            if (values[i][5] != "") {
                var time = XmlService.createElement("time", ns); // create time element
                var date = new Date(values[i][5]); // parse date string
                date.setHours(12,0,0,0); // set hours to 12 (noon) and min, secs and millisecs to 0
                time.setText(date.toISOString()); // set text of time element to ISO8601 format
                wpt.addContent(time); // add time as child element of waypoint
            }
          
            var name = XmlService.createElement("name", ns); // create name element
            var nameText = "";
            if (values[i][1] != "" && values[i][0] != "") {
                // Both: concatenate Bear Creek mile number,  "|", Jonathan Ley map number and ": "
                nameText += values[i][1] + "|" + values[i][0] + ": "; 
            } else if (values[i][1] != "") {
                // Only BC: concatenate Bear Creek mile number and ": "
                nameText += values[i][1] + ": "; 
            } else if (values[i][0] != "") {
                // Only Ley: concatenate Jonathan Ley map number and ": "
                nameText += values[i][0] + ": ";
            }
            nameText += values[i][3]; // concatenate water source name
            name.setText(nameText); // set text of name element
            wpt.addContent(name); // add name as child element of waypoint

            var desc = XmlService.createElement("desc", ns); // create description element
            var descEncoded = htmlspecialchars(values[i][4], ENT_DISALLOWED); // Encode special characters
            desc.setText(descEncoded); // set text of description element
            wpt.addContent(desc); // add description as child element of waypoint
           
        }
    }
  
    // Format and return XML document as downloadable file
    var str = XmlService.getPrettyFormat().format(doc);
    return ContentService.createTextOutput(str).setMimeType(ContentService.MimeType.XML).downloadAsFile("cdtwater.gpx");
}

// Testing to see if the jsonp parameter is being used properly.
function testDoGet() {
    var request = {parameters: {jsonp: 'callback'}};
    var results = doGet(request);
    Logger.log(results.getContent());
}