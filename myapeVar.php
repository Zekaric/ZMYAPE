<?php
/* mytVar *********************************************************************

Author: Robbert de Groot

Description:

Manage the myape_Var.php file.

Application level variables.

******************************************************************************/

/* MIT License ****************************************************************
Copyright (c) 2015 Robbert de Groot

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
******************************************************************************/

///////////////////////////////////////////////////////////////////////////////
// include
require_once "zFile.php";

require_once "zData.php";

///////////////////////////////////////////////////////////////////////////////
// constant
$version = 1;

define("VAR_DATA_FILE",    "myape_Var.php");
define("VAR_DATA_VAR",     "\$myapeVar");

define("KEY_YEAR_CURRENT", "yearCurrent");

///////////////////////////////////////////////////////////////////////////////
// variable
$myapeVar = array();

// Check for the existance of the data file.
if (!zFileIsExisting(VAR_DATA_FILE))
{
   // Data file doesn't exist yet.  Create a default file.
   zDataSet($myapeVar, KEY_YEAR_CURRENT, -1);

   // Save the default file.
   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

// Populate the variable from the data file.
require_once VAR_DATA_FILE;

// Check if all values are defined of if default ones need to be added.
if (!zDataIsExisting($myapeVar, KEY_YEAR_CURRENT))
{
   if (!zDataIsExisting($myapeVar, KEY_YEAR_CURRENT)) zDataSet($myapeVar, KEY_YEAR_CURRENT, -1);

   // Save the default file.
   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

// App variables should now be set up.

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Get the current year that is being displayed/selected.
function myapeVarGetYearCurrent()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_YEAR_CURRENT);
}

///////////////////////////////////////////////////////////////////////////////
// Set the current year.
function myapeVarSetYearCurrent($year)
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_YEAR_CURRENT, $year);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}
