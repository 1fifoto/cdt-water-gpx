Convertor from Continental Divide Trail Water Report in Google Docs Spreadsheet format to GPX XML file 

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
