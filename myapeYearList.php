<?php
/* myapeYearList **************************************************************

Author: Robbert de Groot

Description:

Manage the myape_YearList.php file.

The list of years we have data for.

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
require_once "zDataList.php";
require_once "zDebug.php";

///////////////////////////////////////////////////////////////////////////////
// constant
define("YEAR_LIST_DATA_FILE", "myape_YearList.php");
define("YEAR_LIST_DATA_VAR",  "\$myapeYearList");

define("KEY_YEAR_VALUE",      "year");

///////////////////////////////////////////////////////////////////////////////
// variable
$myapeYearList = array();

// Check for the existance of the data file.
if (!zFileIsExisting(YEAR_LIST_DATA_FILE))
{
   // Save the default file.
   zDataListSave(YEAR_LIST_DATA_FILE, $myapeYearList, YEAR_LIST_DATA_VAR);
}

// Populate the variable from the data file.
require_once YEAR_LIST_DATA_FILE;

// Check if all values are defined of if default ones need to be added.
$count = count($myapeYearList);
if ($count > 0 &&
    (!zDataListIsExisting($myapeYearList, 0, KEY_YEAR_VALUE)))
{
   for ($index = 0; $index < $count; $index++)
   {
      // Check to see if all entries in the list have all the values.
      if (!zDataListIsExisting($myapeYearList, $index, KEY_YEAR_VALUE)) zDataListSet($myapeYearList, $index, -1);
   }
}

// year list should now be set up.

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Add a new year to the list.
function myapeYearListAdd($year)
{
   global $myapeYearList;

   $index = zDataListAdd($myapeYearList);

   myapeYearListSet($index, $year);
}

///////////////////////////////////////////////////////////////////////////////
// Edit a year values
function myapeYearListEdit($index, $year)
{
   myapeYearListSet($index, $year);
}

///////////////////////////////////////////////////////////////////////////////
// Get the number of years in the list.
function myapeYearListGetCount()
{
   global $myapeYearList;

   return count($myapeYearList);
}

///////////////////////////////////////////////////////////////////////////////
// Get a year value in the list.
function myapeYearListGetYear($index)
{
   global $myapeYearList;

   return zDataListGet($myapeYearList, $index, KEY_YEAR_VALUE);
}

///////////////////////////////////////////////////////////////////////////////
// Set a year
function myapeYearListSet($index, $year)
{
   global $myapeYearList;

   zDataListSet($myapeYearList, $index, KEY_YEAR_VALUE, $year);

   zDataListSave(YEAR_LIST_DATA_FILE, $myapeYearList, YEAR_LIST_DATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Sort the year list
function myapeYearListSort()
{
   global $myapeYearList;

   usort($myapeYearList, 'myapeYearListSortFunction');
}

///////////////////////////////////////////////////////////////////////////////
// Sort function to determin how one item relates to another.
function myapeYearListSortFunction($a, $b)
{
   if ($a[KEY_YEAR_VALUE] < $b[KEY_YEAR_VALUE]) return -1;
   if ($a[KEY_YEAR_VALUE] > $b[KEY_YEAR_VALUE]) return  1;
                                                return  0;
}
