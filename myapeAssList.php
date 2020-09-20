<?php
/* myapeAssList **************************************************************

Author: Robbert de Groot

Description:

Manage the myape_AssList.php file.

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
define("ASS_LISTDATA_FILE",      "myape_AssList.php");
define("ASS_LISTDATA_VAR",       "\$myapeAssList");

define("KEY_ASS_ID",             "id");
define("KEY_ASS_AMOUNT_START",   "amountStart");
define("KEY_ASS_AMOUNT_STOP",    "amountStart");
define("KEY_ASS_DATE_START",     "dateStart");
define("KEY_ASS_DATE_STOP",      "dateStop");
define("KEY_ASS_TYPEID",         "typeId");
define("KEY_ASS_COMMENT",        "comment");

///////////////////////////////////////////////////////////////////////////////
// variable
$myapeAssList = array();
$fileName     = myapeAssListGetFileName();

// Check for the existance of the data file.
if (!zFileIsExisting($fileName))
{
   // Save the default file.
   zDataListSave($fileName, $myapeAssList, ASS_LISTDATA_VAR);
}

// Populate the variable from the data file.
require_once $fileName;

// Check if all values are defined of if default ones need to be added.
$count = count($myapeAssList);
if ($count > 0 &&
    (!zDataListIsExisting($myapeAssList, 0, KEY_ASS_ID)           ||
     !zDataListIsExisting($myapeAssList, 0, KEY_ASS_AMOUNT_START) ||
     !zDataListIsExisting($myapeAssList, 0, KEY_ASS_AMOUNT_STOP)  ||
     !zDataListIsExisting($myapeAssList, 0, KEY_ASS_DATE_START)   ||
     !zDataListIsExisting($myapeAssList, 0, KEY_ASS_DATE_STOP)    ||
     !zDataListIsExisting($myapeAssList, 0, KEY_ASS_TYPEID)       ||
     !zDataListIsExisting($myapeAssList, 0, KEY_ASS_COMMENT)))
{
   for ($index = 0; $index < $count; $index++)
   {
      // Check to see if all entries in the list have all the values.
      if (!zDataListIsExisting($myapeAssList, $index, KEY_ASS_AMOUNT_START))  zDataListSet($myapeAssList, $index, KEY_ASS_AMOUNT_START,   0);
      if (!zDataListIsExisting($myapeAssList, $index, KEY_ASS_AMOUNT_STOP))   zDataListSet($myapeAssList, $index, KEY_ASS_AMOUNT_STOP,    0);
      if (!zDataListIsExisting($myapeAssList, $index, KEY_ASS_COMMENT))       zDataListSet($myapeAssList, $index, KEY_ASS_COMMENT,        "");
      if (!zDataListIsExisting($myapeAssList, $index, KEY_ASS_DATE_START))    zDataListSet($myapeAssList, $index, KEY_ASS_DATE_START,     "00000000");
      if (!zDataListIsExisting($myapeAssList, $index, KEY_ASS_DATE_STOP))     zDataListSet($myapeAssList, $index, KEY_ASS_DATE_START,     "00000000");
      if (!zDataListIsExisting($myapeAssList, $index, KEY_ASS_TYPEID))        zDataListSet($myapeAssList, $index, KEY_ASS_TYPEID,         "sa");
   }
}

// expense type list should now be set up.

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Add a new expense type.
function myapeAssListAdd($typeId, $amountStart, $amountStop, $dateStart, $dateStop, $comment)
{
   global $myapeAssList;

   $index = zDataListAdd($myapeAssList);

   $id    = myapeVarGetIdNextAss();

   myapeAssListSet($index, $id, $typeId, $amountStart, $amountStop, $dateStart, $dateStop, $comment);
}

///////////////////////////////////////////////////////////////////////////////
// Edit an expense type.
function myapeAssListEdit($index, $typeId, $amountStart, $amountStop, $dateStart, $dateStop, $comment)
{
   global $myapeAssList;

   $id = zDataListGet($myapeAssList, $index, KEY_ASS_ID);

   myapeAssListSet($index, $id, $typeId, $amountStart, $amountStop, $dateStart, $dateStop, $comment);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeAssListGetAmountStart($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_AMOUNT_START);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeAssListGetAmountStop($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_AMOUNT_STOP);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeAssListGetComment($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_COMMENT);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code value.
function myapeAssListGetCount()
{
   global $myapeAssList;

   return count($myapeAssList);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeAssListGetDateStart($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_DATE_START);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeAssListGetDateStop($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_DATE_STOP);
}

///////////////////////////////////////////////////////////////////////////////
// Get the year filename.
function myapeAssListGetFileName()
{
   return ASS_LISTDATA_FILE;
}

///////////////////////////////////////////////////////////////////////////////
// Get the expense Id
function myapeAssListGetId($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_ID);
}

///////////////////////////////////////////////////////////////////////////////
// Get the index from the code.
function myapeAssListGetIndexFromId($id)
{
   global $myapeAssList;

   $count = count($myapeAssList);
   for ($index = 0; $index < $count; $index++)
   {
      if ($id == zDataListGet($myapeAssList, $index, KEY_ASS_ID))
      {
         return $index;
      }
   }
   return -1;
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeAssListGetTypeId($index)
{
   global $myapeAssList;

   return zDataListGet($myapeAssList, $index, KEY_ASS_TYPEID);
}

///////////////////////////////////////////////////////////////////////////////
// Get the nice name for the asset type.
function myapeAssListGetTypeName($id)
{
   if ($type == "sa") return "Cash";
   if ($type == "as") return "Asset";
   if ($type == "gs") return "Inv:GIC";
   if ($type == "ms") return "Inv:Mutual Fund ";
   if ($type == "bs") return "Inv:Bond";
   if ($type == "ss") return "Inv:Stock";
   if ($type == "gt") return "TFSA:GIC";
   if ($type == "mt") return "TFSA:Mutual Fund ";
   if ($type == "bt") return "TFSA:Bond";
   if ($type == "st") return "TFSA:Stock";
   if ($type == "gr") return "RRSP:GIC";
   if ($type == "mr") return "RRSP:Mutual Fund";
   if ($type == "br") return "RRSP:Bond";
   if ($type == "sr") return "RRSP:Stock";

   return "(" . $id . ")";
}

///////////////////////////////////////////////////////////////////////////////
// Set the expense type value.
function myapeAssListSet($index, $id, $typeId, $amountStart, $amountStop, $dateStart, $dateStop, $comment)
{
   global $myapeAssList;

   zDataListSet($myapeAssList, $index, KEY_ASS_ID,             $id);
   zDataListSet($myapeAssList, $index, KEY_ASS_TYPEID,         $typeId);
   zDataListSet($myapeAssList, $index, KEY_ASS_AMOUNT_START,   $amountStart);
   zDataListSet($myapeAssList, $index, KEY_ASS_AMOUNT_STOP,    $amountStop);
   zDataListSet($myapeAssList, $index, KEY_ASS_DATE_START,     $dateStart);
   zDataListSet($myapeAssList, $index, KEY_ASS_DATE_STOP,      $dateStart);
   zDataListSet($myapeAssList, $index, KEY_ASS_COMMENT,        $comment);

   zDataListSave(myapeAssListGetFileName(), $myapeAssList, ASS_LISTDATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Sort the expense type list.
function myapeAssListSort()
{
   global $myapeAssList;

   usort($myapeAssList, 'myapeAssListSortFunction');
}

///////////////////////////////////////////////////////////////////////////////
// Comparison function for the sorting.
function myapeAssListSortFunction($a, $b)
{
   // Sort by type...
   $atype  = myapeAssTypeListGetName(myapeAssTypeListGetIndexFromCode($a[KEY_ASS_TYPEID]));
   $btype  = myapeAssTypeListGetName(myapeAssTypeListGetIndexFromCode($b[KEY_ASS_TYPEID]));

   $atype = myapeAssListSortFunction_TypeToRank($atype);
   $btype = myapeAssListSortFunction_TypeToRank($btype);

   if ($atype < $btype) return -1;
   if ($atype > $btype) return  1;

   // Then sort by end date first...
   if ($a[KEY_ASS_DATE_STOP] < $b[KEY_ASS_DATE_STOP]) return -1;
   if ($a[KEY_ASS_DATE_STOP] > $b[KEY_ASS_DATE_STOP]) return  1;

   // Then sort by end amount first...
   if ($a[KEY_ASS_AMOUNT_STOP] < $b[KEY_ASS_AMOUNT_STOP]) return -1;
   if ($a[KEY_ASS_AMOUNT_STOP] > $b[KEY_ASS_AMOUNT_STOP]) return  1;

   // Then finally by id.
   // Id's are unique so there is never an equality.
   if ($a[KEY_ASS_ID] < $b[KEY_ASS_ID]) return -1;
                                        return  1;
}

///////////////////////////////////////////////////////////////////////////////
// Convert the type to a rank.
function myapeAssListSortFunction_TypeToRank($type)
{
   if ($type == "sa") return  0;
   if ($type == "as") return  1;
   if ($type == "gs") return  2;
   if ($type == "ms") return  3;
   if ($type == "bs") return  4;
   if ($type == "ss") return  5;
   if ($type == "gt") return  6;
   if ($type == "mt") return  7;
   if ($type == "bt") return  8;
   if ($type == "st") return  9;
   if ($type == "gr") return 10;
   if ($type == "mr") return 11;
   if ($type == "br") return 12;
   if ($type == "sr") return 13;

   return 0;
}
