Convertor from Continental Divide Trail Water Report in Google Docs Spreadsheet format to GPX XML file 

spreadsheet/CDT WATER 2015.xlsx
===============================
The `spreadsheet/CDT WATER 2015.xlsx` is a backup of the Google Docs CDT Water
Report spreadsheet in Microsoft EXCEL spreadsheet format. It has the following
columns: Jonathan Ley Map Number, Bear Creek mile, Accumulated Bear Creek
mileage, Name, Status, Date Reported, and Reported By. To use it, you import
and upload it to Google docs. 

script/Code.gs
==============
The `script/Code.gs` file is a Google docs script file which when run converts
the entire Google docs CDT Water Report spreadsheet to a JSON with padding
(JSONP) file which is then used by the web-site `cdtwatergpx/index.php` as
described below. It can be used as a template for a new Google docs script
file. To create a new script based upon this, update the SPREADSHEET_ID, and
SHEET_NAME to reference a different Google docs spreadsheet. 

cdtwatergpx/index.php
======================================================================
The `cdtwatergpx/index.php` web-site file access the Google Docs CDT Water
Report spreadsheet URL and creates a GPX XML file to be downloaded as
`cdtwatergpx.gpx`.
