<?php
/* myapeExpList **************************************************************

Author: Robbert de Groot

Description:

Manage the myape_ExpList.php file.

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
require_once "myapeVar.php";

///////////////////////////////////////////////////////////////////////////////
// constant
define("EXP_LISTDATA_FILE_PRE",  "myape_ExpList_");
define("EXP_LISTDATA_FILE_POST", ".php");
define("EXP_LISTDATA_VAR",       "\$myapeExpList");

define("KEY_EXP_ID",             "id");
define("KEY_EXP_DATE",           "date");
define("KEY_EXP_TYPEID",         "typeId");
define("KEY_EXP_AMOUNT",         "amount");
define("KEY_EXP_COMMENT",        "comment");

///////////////////////////////////////////////////////////////////////////////
// variable
$myapeExpList = array();
$fileName     = myapeExpListGetFileName();

// Check for the existance of the data file.
if (!zFileIsExisting($fileName))
{
   // Save the default file.
   zDataListSave($fileName, $myapeExpList, EXP_LISTDATA_VAR);
}

// Populate the variable from the data file.
require_once $fileName;

// Check if all values are defined of if default ones need to be added.
$count = count($myapeExpList);
if ($count > 0 &&
    (!zDataListIsExisting($myapeExpList, 0, KEY_EXP_ID)     ||
     !zDataListIsExisting($myapeExpList, 0, KEY_EXP_AMOUNT) ||
     !zDataListIsExisting($myapeExpList, 0, KEY_EXP_DATE)   ||
     !zDataListIsExisting($myapeExpList, 0, KEY_EXP_TYPEID) ||
     !zDataListIsExisting($myapeExpList, 0, KEY_EXP_COMMENT)))
{
   for ($index = 0; $index < $count; $index++)
   {
      // Check to see if all entries in the list have all the values.
      if (!zDataListIsExisting($myapeExpList, $index, KEY_EXP_AMOUNT))  zDataListSet($myapeExpList, $index, KEY_EXP_AMOUNT,  0);
      if (!zDataListIsExisting($myapeExpList, $index, KEY_EXP_COMMENT)) zDataListSet($myapeExpList, $index, KEY_EXP_COMMENT, "");
      if (!zDataListIsExisting($myapeExpList, $index, KEY_EXP_DATE))    zDataListSet($myapeExpList, $index, KEY_EXP_DATE,    "0");
      if (!zDataListIsExisting($myapeExpList, $index, KEY_EXP_TYPEID))  zDataListSet($myapeExpList, $index, KEY_EXP_TYPEID,  -1);
   }
}

// expense type list should now be set up.

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Add a new expense type.
function myapeExpListAdd($date, $typeId, $amount, $comment)
{
   global $myapeExpList;

   $index = zDataListAdd($myapeExpList);

   $id    = myapeVarGetIdNextExp();

   myapeExpListSet($index, $id, $date, $typeId, $amount, $comment);
}

///////////////////////////////////////////////////////////////////////////////
// Edit an expense type.
function myapeExpListEdit($index, $date, $typeId, $amount, $comment)
{
   global $myapeExpList;

   $id = zDataListGet($myapeExpList, $index, KEY_EXP_ID);

   myapeExpListSet($index, $id, $date, $typeId, $amount, $comment);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeExpListGetAmount($index)
{
   global $myapeExpList;

   return zDataListGet($myapeExpList, $index, KEY_EXP_AMOUNT);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeExpListGetComment($index)
{
   global $myapeExpList;

   return zDataListGet($myapeExpList, $index, KEY_EXP_COMMENT);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code value.
function myapeExpListGetCount()
{
   global $myapeExpList;

   return count($myapeExpList);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeExpListGetDate($index)
{
   global $myapeExpList;

   return zDataListGet($myapeExpList, $index, KEY_EXP_DATE);
}

///////////////////////////////////////////////////////////////////////////////
// Get the year filename.
function myapeExpListGetFileName()
{
   return EXP_LISTDATA_FILE_PRE . myapeVarGetYearCurrent() . EXP_LISTDATA_FILE_POST;
}

///////////////////////////////////////////////////////////////////////////////
// Get the expense Id
function myapeExpListGetId($index)
{
   global $myapeExpList;

   return zDataListGet($myapeExpList, $index, KEY_EXP_ID);
}

///////////////////////////////////////////////////////////////////////////////
// Get the index from the code.
function myapeExpListGetIndexFromId($id)
{
   global $myapeExpList;

   $count = count($myapeExpList);
   for ($index = 0; $index < $count; $index++)
   {
      if ($id == zDataListGet($myapeExpList, $index, KEY_EXP_ID))
      {
         return $index;
      }
   }
   return -1;
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeExpListGetTypeId($index)
{
   global $myapeExpList;

   return zDataListGet($myapeExpList, $index, KEY_EXP_TYPEID);
}

///////////////////////////////////////////////////////////////////////////////
// Set the expense type value.
function myapeExpListSet($index, $id, $date, $typeId, $amount, $comment)
{
   global $myapeExpList;

   zDataListSet($myapeExpList, $index, KEY_EXP_ID,      $id);
   zDataListSet($myapeExpList, $index, KEY_EXP_AMOUNT,  $amount);
   zDataListSet($myapeExpList, $index, KEY_EXP_DATE,    $date);
   zDataListSet($myapeExpList, $index, KEY_EXP_TYPEID,  $typeId);
   zDataListSet($myapeExpList, $index, KEY_EXP_COMMENT, $comment);

   zDataListSave(myapeExpListGetFileName(), $myapeExpList, EXP_LISTDATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Sort the expense type list.
function myapeExpListSort()
{
   global $myapeExpList;

   usort($myapeExpList, 'myapeExpListSortFunction');
}

///////////////////////////////////////////////////////////////////////////////
// Comparison function for the sorting.
function myapeExpListSortFunction($a, $b)
{
   // Sort by date first...
   if ($a[KEY_EXP_DATE] < $b[KEY_EXP_DATE]) return -1;
   if ($a[KEY_EXP_DATE] > $b[KEY_EXP_DATE]) return  1;

   // Then by type...
   $atype  = myapeExpTypeListGetName(myapeExpTypeListGetIndexFromCode($a[KEY_EXP_TYPEID]));
   $btype  = myapeExpTypeListGetName(myapeExpTypeListGetIndexFromCode($b[KEY_EXP_TYPEID]));
   $result = strcmp($atype, $btype);
   if ($result != 0) return $result;

   // Then finally by id.
   // Id's are unique so there is never an equality.
   if ($a[KEY_EXP_ID] < $b[KEY_EXP_ID]) return -1;
                                        return  1;
}
