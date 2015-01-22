spreadsheet/CDT WATER 2014.xlsx
===============================
The `spreadsheet/CDT WATER 2014.xlsx` is a backup of the Google docs CDT Water
Report spreadsheet in Microsoft EXCEL spreadsheet format. It has all entries 
for the Jonathan Ley Map Number, BC, Accumulated BC, Name, Status, Date
Reported, and Reported By columns. To use it,import and upload it to Google
docs. 

script/Code.gs
==============
The `script/Code.gs` file is a Google docs script file which when run
(typically using an hourly cron job) converts the Google docs CDT Water
Report spreadsheet to a `water-points.js` file used by the web-site described
below. It can be used as a template for a new Google docs script file. To
create a new script based upon this, update the SPREADSHEET_ID, and SHEET_NAME
to the new Google docs spreadsheet that was based upon the template above. 

crontab/entry.txt
=================
You must also update the cron job entry on your website to execute the Google
docs script file which produces the `water-points.js` file. A sample cron job
entry is found in entry.txt.

cdtwatergpx/index.php
======================================================================
The `cdtwatergpx/index.php` web-site files convert the `water-points.js` file
into a GPX XML download `cdtwatergpx.gpx` file. Note: A sample `pct/water-points.js`
file is provided even though it is overwritten when the cron job runs described
above.