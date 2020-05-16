<?php
/* myapeExpTypeList **************************************************************

Author: Robbert de Groot

Description:

Manage the myape_ExpTypeList.php file.

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
define("EXP_TYPE_LISTDATA_FILE", "myape_ExpTypeList.php");
define("EXP_TYPE_LISTDATA_VAR",  "\$myapeExpTypeList");

define("KEY_EXPTYPE_ID",      "id");
define("KEY_EXPTYPE_CODE",    "code");
define("KEY_EXPTYPE_NAME",    "name");

///////////////////////////////////////////////////////////////////////////////
// variable
$myapeExpTypeList = array();

// Check for the existance of the data file.
if (!zFileIsExisting(EXP_TYPE_LISTDATA_FILE))
{
   // Save the default file.
   zDataListSave(EXP_TYPE_LISTDATA_FILE, $myapeExpTypeList, EXP_TYPE_LISTDATA_VAR);
}

// Populate the variable from the data file.
require_once EXP_TYPE_LISTDATA_FILE;

// Check if all values are defined of if default ones need to be added.
$count = count($myapeExpTypeList);
if ($count > 0 &&
    (!zDataListIsExisting($myapeExpTypeList, 0, KEY_EXPTYPE_ID)   ||
     !zDataListIsExisting($myapeExpTypeList, 0, KEY_EXPTYPE_CODE) ||
     !zDataListIsExisting($myapeExpTypeList, 0, KEY_EXPTYPE_NAME)))
{
   for ($index = 0; $index < $count; $index++)
   {
      // Check to see if all entries in the list have all the values.
      if (!zDataListIsExisting($myapeExpTypeList, $index, KEY_EXPTYPE_CODE)) zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE, "" . $index);
      if (!zDataListIsExisting($myapeExpTypeList, $index, KEY_EXPTYPE_NAME)) zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE, "[missing]");
   }
}

// expense type list should now be set up.

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Add a new expense type.
function myapeExpTypeListAdd($name)
{
   global $myapeExpTypeList;

   $index = zDataListAdd($myapeExpTypeList);
   $id    = myapeVarGetIdNextExpType();

   myapeExpTypeListSet($index, $id, $name);
}

///////////////////////////////////////////////////////////////////////////////
// Edit an expense type.
function myapeExpTypeListEdit($index, $name)
{
   global $myapeExpTypeList;

   $id = zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_ID);

   myapeExpTypeListSet($index, $id, $name);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code value.
function myapeExpTypeListGetCount()
{
   global $myapeExpTypeList;

   return count($myapeExpTypeList);
}

///////////////////////////////////////////////////////////////////////////////
// Get the code for the type
function myapeExpTypeListGetCode($index)
{
   global $myapeExpTypeList;

   return zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE);
}

///////////////////////////////////////////////////////////////////////////////
// Get the index from the code.
function myapeExpTypeListGetIndexFromCode($code)
{
   global $myapeExpTypeList;

   $count = count($myapeExpTypeList);
   for ($index = 0; $index < $count; $index++)
   {
      if ($code == zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE))
      {
         return $index;
      }
   }
   return -1;
}

///////////////////////////////////////////////////////////////////////////////
// Get the name for the type
function myapeExpTypeListGetName($index)
{
   global $myapeExpTypeList;

   return zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_NAME);
}

///////////////////////////////////////////////////////////////////////////////
// Set the expense type value.
function myapeExpTypeListSet($index, $id, $name)
{
   global $myapeExpTypeList;

   zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_ID,   $id);
   zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_NAME, $name);

   // Readjust the codes if need be.
   _myapeExpTypeListCreateCodes();

   zDataListSave(EXP_TYPE_LISTDATA_FILE, $myapeExpTypeList, EXP_TYPE_LISTDATA_VAR);
}

///////////////////////////////////////////////////////////////////////////////
// Sort the expense type list.
function myapeExpTypeListSort()
{
   global $myapeExpTypeList;

   usort($myapeExpTypeList, 'myapeExpTypeListSortFunction');
}

///////////////////////////////////////////////////////////////////////////////
// Comparison function for the sorting.
function myapeExpTypeListSortFunction($a, $b)
{
   if ($a[KEY_EXPTYPE_NAME] < $b[KEY_EXPTYPE_NAME]) return -1;
   if ($a[KEY_EXPTYPE_NAME] > $b[KEY_EXPTYPE_NAME]) return  1;
                                                    return  0;
}

///////////////////////////////////////////////////////////////////////////////
// Look at the entire list of expense type names and generate unique short codes
// for them.
function _myapeExpTypeListCreateCodes()
{
   myapeExpTypeListSort();

   _myapeExpTypeListCreate2LetterCodes();
}

///////////////////////////////////////////////////////////////////////////////
// find 2 letter codes if possible.
function _myapeExpTypeListCreate2LetterCodes()
{
   global $myapeExpTypeList;

   // Get the size of the code list.
   $count = count($myapeExpTypeList);

   // First pass, take the first letter of the first two words in the name.
   for ($index = 0; $index < $count; $index++)
   {
      $name = strtolower(zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_NAME));
      $code = substr($name, 0, 1);

      // Find the second word...
      $pos  = strpos($name, " ");
      // No second word...
      if ($pos == false)
      {
         // Use the second letter.
         $code .= substr($name, 1, 1);
      }
      else
      {
         $code .= substr($name, $pos + 1, 1);
      }

      zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE, $code);
   }

   // Second pass, There may be a few duplicate codes.  If so then we need to 
   // find a different letter for the duplicate codes.
   $lastCode = "";
   for ($index = 0; $index < $count; $index++)
   {
      $code = zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE);
      if ($code != $lastCode)
      {
         // Codes are unique;
         $lastCode = $code;
         continue;
      }

      // Codes are the same.  Find a different letter in the name and use that.
      // First check to see if there a multiple words.  If so then take the next
      // first letter of the word.
      // Retain the first letter.
      $found   = false;
      $str     = zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_NAME);
      while (true)
      {
         $pos  = strpos($name, " ");
         if ($pos == false)
         {
            break;
         }

         $str      =            substr($name, $pos + 1);
         $newCode  = strtolower(substr($code, 0, 1)) . strtolower(substr($str,  0, 1));

         // Check to see if the code exists.
         if (myapeExpTypeListGetIndexFromCode($newCode) == -1)
         {
            // Code doesn't exist
            zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE, $newCode);
            $found = true;
            break;
         }
      }

      // Found a unique code.
      if ($found)
      {
         continue;
      }

      // Unique code not found.
      // Check each letter in the name.
      $str   = zDataListGet($myapeExpTypeList, $index, KEY_EXPTYPE_NAME);
      while($str != "")
      {
         $str      =            substr($str, 1);
         $newCode  = strtolower(substr($code, 0, 1)) .strtolower(substr($str,  0, 1));
         if (myapeExpTypeListGetIndexFromCode($newCode) == -1)
         {
            // Code doesn't exist
            zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE, $newCode);
            $found = true;
            break;
         }
      }

      // Found a unique code.
      if ($found)
      {
         continue;
      }

      // Go through the alphabet.
      for ($aindex = 0; $aindex < 26; $aindex++)
      {
         switch ($aindex)
         {
         default:
         case  0: $letter = "a";
         case  1: $letter = "b";
         case  2: $letter = "c";
         case  3: $letter = "d";
         case  4: $letter = "e";
         case  5: $letter = "f";
         case  6: $letter = "g";
         case  7: $letter = "h";
         case  8: $letter = "i";
         case  9: $letter = "j";
         case 10: $letter = "k";
         case 11: $letter = "l";
         case 12: $letter = "m";
         case 13: $letter = "n";
         case 14: $letter = "o";
         case 15: $letter = "p";
         case 16: $letter = "q";
         case 17: $letter = "r";
         case 18: $letter = "s";
         case 19: $letter = "t";
         case 20: $letter = "u";
         case 21: $letter = "v";
         case 22: $letter = "w";
         case 23: $letter = "x";
         case 24: $letter = "y";
         case 25: $letter = "z";
         }

         $newCode = substr($code, 0, 1) . $letter;

         if (myapeExpTypeListGetIndexFromCode($newCode) == -1)
         {
            // Code doesn't exist
            zDataListSet($myapeExpTypeList, $index, KEY_EXPTYPE_CODE, $newCode);
            break;
         }
      }
   }
}
