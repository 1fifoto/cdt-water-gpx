Converter from Continental Divide Trail Water Report in Google Docs Spreadsheet format to GPX XML file 

1. Go to Google Drive: https://drive.google.com/drive/my-drive
2. Select CDT WATER 2016 file (owned by Elisabeth Chaplin) and open it by double clicking on it
3. Select Tools menu item > Script Editor
4. Edit Code.gs file in window either manually or better yet via GITHUB: https://github.com/1fifoto/cdt-water-gpx
5. Select Run menu item > doGet to authorize access and test out changes
6. On Water Report page of CDTC website [http://continentaldividetrail.org/water-report/] or 
the Trail Unites Us website [http://thetrailunitesus.com/water/] 
add link to access conversion script to download GPX file: https://script.google.com/macros/s/AKfycbySRs1sQTZYOC87JCNSedQ4iB_hSQZpRZHaRBsg0rAFyr6nRuo/exec

spreadsheet/CDT WATER 2015.xlsx
===============================
The `spreadsheet/CDT WATER 2015.xlsx` is a backup of the Google Docs CDT Water
Report spreadsheet in Microsoft EXCEL spreadsheet format. It has the following
columns: Jonathan Ley Map Number, Bear Creek mile, Accumulated Bear Creek
mileage, Name, Status, Date Reported, Reported By, GPS (N), GPS (W), and
Condition. To use it, you import and upload it to Google docs. 

script/Code.gs
==============
The `script/Code.gs` file is a Google docs script file which when run creates a
GPX XML file to be downloaded as `cdtwater.gpx`. The GPX XML file contains
the necessary XML Namespaces to allow it to be verified using the following
command: `SAXCount -v=always -n -s -f cdtwater.gpx`
