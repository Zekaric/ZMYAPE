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

define("VAR_DATA_FILE",          "myape_Var.php");
define("VAR_DATA_VAR",           "\$myapeVar");

define("KEY_YEAR_CURRENT",       "yearCurrent");
define("KEY_ID_NEXT_ASS",        "idNextAss");
define("KEY_ID_NEXT_EXP",        "idNextExp");
define("KEY_ID_NEXT_EXP_TYPE",   "idNextExpType");

define("KEY_DEFAULT_EXP_DATE",   "expDateDefault");
define("KEY_DEFAULT_EXP_TYPE",   "expTypeDefault");

define("KEY_DISPLAY_LIST",       "displayList");
define("VALUE_DISPLAY_LIST_ASS", "ass");
define("VALUE_DISPLAY_LIST_PAY", "pay");
define("VALUE_DISPLAY_LIST_EXP", "exp");

///////////////////////////////////////////////////////////////////////////////
// variable
$myapeVar = array();

// Check for the existance of the data file.
if (!zFileIsExisting(VAR_DATA_FILE))
{
   // Data file doesn't exist yet.  Create a default file.
   zDataSet($myapeVar, KEY_ID_NEXT_ASS,       1);
   zDataSet($myapeVar, KEY_ID_NEXT_EXP,       1);
   zDataSet($myapeVar, KEY_ID_NEXT_EXP_TYPE,  1);
   zDataSet($myapeVar, KEY_YEAR_CURRENT,     -1);

   zDataSet($myapeVar, KEY_DEFAULT_EXP_DATE, "0101");
   zDataSet($myapeVar, KEY_DEFAULT_EXP_TYPE,  0);

   zDataSet($myapeVar, KEY_DISPLAY_LIST,      VALUE_DISPLAY_LIST_EXP);

   // Save the default file.
   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

// Populate the variable from the data file.
require_once VAR_DATA_FILE;

// Check if all values are defined of if default ones need to be added.
if (!zDataIsExisting($myapeVar, KEY_ID_NEXT_ASS)      ||
    !zDataIsExisting($myapeVar, KEY_ID_NEXT_EXP)      ||
    !zDataIsExisting($myapeVar, KEY_ID_NEXT_EXP_TYPE) ||
    !zDataIsExisting($myapeVar, KEY_YEAR_CURRENT)     ||
    !zDataIsExisting($myapeVar, KEY_DEFAULT_EXP_DATE) ||
    !zDataIsExisting($myapeVar, KEY_DEFAULT_EXP_TYPE) ||
    !zDataIsExisting($myapeVar, KEY_DISPLAY_LIST))
{
   if (!zDataIsExisting($myapeVar, KEY_ID_NEXT_ASS))      zDataSet($myapeVar, KEY_ID_NEXT_ASS,       1);
   if (!zDataIsExisting($myapeVar, KEY_ID_NEXT_EXP))      zDataSet($myapeVar, KEY_ID_NEXT_EXP,       1);
   if (!zDataIsExisting($myapeVar, KEY_ID_NEXT_EXP_TYPE)) zDataSet($myapeVar, KEY_ID_NEXT_EXP_TYPE,  1);
   if (!zDataIsExisting($myapeVar, KEY_YEAR_CURRENT))     zDataSet($myapeVar, KEY_YEAR_CURRENT,     -1);
   
   if (!zDataIsExisting($myapeVar, KEY_DEFAULT_EXP_DATE)) zDataSet($myapeVar, KEY_DEFAULT_EXP_DATE, "0101");
   if (!zDataIsExisting($myapeVar, KEY_DEFAULT_EXP_TYPE)) zDataSet($myapeVar, KEY_DEFAULT_EXP_TYPE, "--");

   if (!zDataIsExisting($myapeVar, KEY_DISPLAY_LIST))     zDataSet($myapeVar, KEY_DISPLAY_LIST,     VALUE_DISPLAY_LIST_EXP);

   // Save the default file.
   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

// App variables should now be set up.

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Get expense date default
function myapeVarGetDefaultExpDate()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_DEFAULT_EXP_DATE);
}

///////////////////////////////////////////////////////////////////////////////
// Get the expense type default
function myapeVarGetDefaultExpType()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_DEFAULT_EXP_TYPE);
}

///////////////////////////////////////////////////////////////////////////////
// Which display list are we showing.
function myapeVarIsDisplayListAsset()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_DISPLAY_LIST) == VALUE_DISPLAY_LIST_ASS;
}

function myapeVarIsDisplayListPay()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_DISPLAY_LIST) == VALUE_DISPLAY_LIST_PAY;
}

function myapeVarIsDisplayListExpense()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_DISPLAY_LIST) == VALUE_DISPLAY_LIST_EXP;
}

///////////////////////////////////////////////////////////////////////////////
// Get the next id to us for expenses.
function myapeVarGetIdNextAss()
{
   global $myapeVar;

   $id = zDataGet($myapeVar, KEY_ID_NEXT_ASS);
   zDataSet($myapeVar, KEY_ID_NEXT_ASS, $id + 1);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);

   return $id;
}

///////////////////////////////////////////////////////////////////////////////
// Get the next id to us for expenses.
function myapeVarGetIdNextExp()
{
   global $myapeVar;

   $id = zDataGet($myapeVar, KEY_ID_NEXT_EXP);
   zDataSet($myapeVar, KEY_ID_NEXT_EXP, $id + 1);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);

   return $id;
}

///////////////////////////////////////////////////////////////////////////////
// Get the next id to use for expense types.
function myapeVarGetIdNextExpType()
{
   global $myapeVar;

   $id = zDataGet($myapeVar, KEY_ID_NEXT_EXP_TYPE);
   zDataSet($myapeVar, KEY_ID_NEXT_EXP_TYPE, $id + 1);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);

   return $id;
}

///////////////////////////////////////////////////////////////////////////////
// Get the current year that is being displayed/selected.
function myapeVarGetYearCurrent()
{
   global $myapeVar;

   return zDataGet($myapeVar, KEY_YEAR_CURRENT);
}

///////////////////////////////////////////////////////////////////////////////
// Set the expense date default
function myapeVarSetDefaultExpDate($date)
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_DEFAULT_EXP_DATE, $date);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Set the expense type default
function myapeVarSetDefaultExpType($type)
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_DEFAULT_EXP_TYPE, $type);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Which display list are we showing.
function myapeVarSetIsDisplayListAsset()
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_DISPLAY_LIST, VALUE_DISPLAY_LIST_ASS);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

function myapeVarSetIsDisplayListPay()
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_DISPLAY_LIST, VALUE_DISPLAY_LIST_PAY);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

function myapeVarSetIsDisplayListExpense()
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_DISPLAY_LIST, VALUE_DISPLAY_LIST_EXP);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Set the current year.
function myapeVarSetYearCurrent($year)
{
   global $myapeVar;

   zDataSet($myapeVar, KEY_YEAR_CURRENT, $year);

   zDataSave(VAR_DATA_FILE, $myapeVar, VAR_DATA_VAR);
}
