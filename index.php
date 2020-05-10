<?php
/* index *********************************************************************

Author: Robbert de Groot

Description:

The main file of the web site.

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
require_once "zDebug.php";
require_once "zUtil.php";

require_once "myapeVar.php";
require_once "myapeDisplay.php";
require_once "myapeYearList.php";

///////////////////////////////////////////////////////////////////////////////
// function

function _ParseGetInt($str)
{
   $str = trim($str);

   // Nothing to parse.
   if (strlen($str) == 0)
   {
      return null;
   }

   // Get the number
   $num   = "";
   $isSet = false;
   while(true)
   {
      $letter = substr($str, 0, 1);
      $str    = substr($str, 1);

      if ($letter == "0" ||
          $letter == "1" ||
          $letter == "2" ||
          $letter == "3" ||
          $letter == "4" ||
          $letter == "5" ||
          $letter == "6" ||
          $letter == "7" ||
          $letter == "8" ||
          $letter == "9")
      {
         $num .= $letter;    
      }
      else
      {
         break;
      }
   }

   // No number found.
   $result = null;
   if ($num != "")
   {
      $result    = array();
      $result[0] = (int) $num;
      $result[1] = $str;
   }

   return $result;
}

// Return:
// null = failure.
// !null (array)
//    0 = letter.
//    1 = rest of the string.
function _ParseGetNonSpaceLetter($str)
{
   $str = trim($str);

   // Nothing to parse.
   if (strlen($str) == 0)
   {
      return null;
   }

   $isSet = false;
   while(true)
   {
      $letter = substr($str, 0, 1);
      $str    = substr($str, 1);

      if ($letter != " " &&
          $letter != "\t")
      {
         $isSet = true;
         break;    
      }

      if ($str == "")
      {
         break;
      }
   }

   // Create the result.
   $result = null;
   if ($isSet)
   {
      $result    = array();
      $result[0] = $letter;
      $result[1] = $str;
   }

   return $result;
}

///////////////////////////////////////////////////////////////////////////////
// The main page processing.

// Get the operation and command to process.
$str = zUtilGetValue("cmd");

// Process the command.
$result = _ParseGetNonSpaceLetter($str);

// if there is a command...
if ($result != null)
{
   $letter = $result[0];
   $str    = $result[1];

   // Year command
   if ($letter == "y")
   {
      $result = _ParseGetNonSpaceLetter($str);
      $letter = $result[0];
      $str    = $result[1];

      $year   = -1;
      if ($letter == "a" ||
          $letter == "s")
      {
         $result = _ParseGetInt($str);
         $year   = $result[0];
         $str    = $result[1];
      }

      if      ($letter == "a" &&
               $year   != -1)
      {
         myapeYearListAdd(      $year);
         myapeVarSetYearCurrent($year);
      }
      else if ($letter == "s" &&
               $year   != -1)
      {
         myapeVarSetYearCurrent($year);
      }
   }
}

// Display the result page.
myapeDisplay();
