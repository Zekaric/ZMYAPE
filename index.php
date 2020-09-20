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

///////////////////////////////////////////////////////////////////////////////
// function

// Return:
// null = failure.
// !null (array)
//    0 = integer
//    1 = rest of the string
function _ParseGetInteger($str)
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
         $str  = substr($str, 1);
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
function _ParseGet1Letter($str)
{
   $str = trim($str);

   // Nothing to parse.
   if (strlen($str) == 0)
   {
      return null;
   }

   // Create the result.
   $result    = array();
   $result[0] = substr($str, 0, 1);
   $result[1] = substr($str, 1);

   return $result;
}

// Return:
// null = failure.
// !null (array)
//    0 = 2 letter code
//    1 = rest of the string
function _ParseGet2Letter($str)
{
   $str = trim($str);

   // Nothing to parse.
   if (strlen($str) <= 1)
   {
      return null;
   }

   // Create the result
   $result = array();
   $result[0] = substr($str, 0, 2);
   $result[1] = substr($str, 2);

   return $result;
}

// Return:
// null = failure.
// !null (array)
//    0 = 4 letter code
//    1 = rest of the string.
function _ParseGet4Letter($str)
{
   $str = trim($str);

   // Nothing to parse.
   if (strlen($str) <= 1)
   {
      return null;
   }

   // Create the result
   $result = array();
   $result[0] = substr($str, 0, 4);
   $result[1] = substr($str, 4);

   return $result;
}

///////////////////////////////////////////////////////////////////////////////
// The main page processing.

// Get the operation and command to process.
$str = zUtilGetValue("cmd");

// Process the command.
$op = "";
$result = _ParseGet1Letter($str);
if ($result != null) { $op = $result[0]; $str = $result[1]; }

// Common commands
// Switching Lists
if ($op == "l")
{
   $op     = "";
   $result = _ParseGet1Letter($str);
   if ($result != null) { $op = $result[0]; $str = $result[1]; }

   if      ($op == "")
   {
      if      (myapeVarIsDisplayListAsset())    myapeVarSetIsDisplayListPay();
      else if (myapeVarIsDisplayListPay())      myapeVarSetIsDisplayListExpense();
      else if (myapeVarIsDisplayListExpense())  myapeVarSetIsDisplayListAsset();
   }
   else if ($op == "a")                         myapeVarSetIsDisplayListAsset();
   else if ($op == "p")                         myapeVarSetIsDisplayListPay();
   else if ($op == "e")                         myapeVarSetIsDisplayListExpense();

   require_once "myapeDisplay.php";
}
// Commands for asset
else if (myapeVarIsDisplayListAsset())
{
   require_once "myapeDisplay.php";
   require_once "myapeAssList.php";
}
// Commands for pay
else if (myapeVarIsDisplayListPay())
{

}
// Commands for expense
else if (myapeVarIsDisplayListExpense())
{
   // Year command
   if ($op == "y")
   {
      $result = _ParseGet1Letter($str);
      if ($result != null) { $op = $result[0]; $str = $result[1]; }
      
      if ($result != null)
      {
         $year   = -1;
         if ($op == "a" ||
             $op == "s")
         {
            $result = _ParseGetInteger($str);
            if ($result != null)
            {
               $year   = $result[0];
               $str    = $result[1];
            }
         }

         if      ($op   == "a" &&
                  $year != -1)
         {
            myapeYearListAdd(      $year);
            myapeVarSetYearCurrent($year);
         }
         else if ($op   == "s" &&
                  $year != -1)
         {
            myapeVarSetYearCurrent($year);
         }
      }
   }

   // Year processing needs to be done before these includes because the current
   // year could change what script is being used.
   require_once "myapeDisplay.php";
   require_once "myapeYearList.php";
   require_once "myapeExpTypeList.php";

   // Expense Type command
   if ($op == "t")
   {
      $result = _ParseGet1Letter($str);
      $op     = $result[0];
      $str    = $result[1];

      if      ($op == "a")
      {
         $name = trim($str);

         myapeExpTypeListAdd($name);
      }
      else if ($op == "e")
      {
         $result = _ParserGetCode($str);

         $code = "";
         if ($result != null)
         {
            $code   = $result[0];
            $str    = $result[1];
         }

         $name = trim($str);

         $index = myapeExpTypeListGetIndexFromCode($code);
         if ($code  != "" &&
             $index != -1)
         {
            myapeExpTypeListEdit($index, $name);
         }
      }
   }
   // Expense command
   if ($op == "e")
   {
      //zDebugPrint($str);

      $result = _ParseGet1Letter($str);
      $op     = $result[0];
      $str    = $result[1];

      if ($op == "e" ||
          $op == "~")
      {
         $result = _ParseGetInteger($str);
         $id     = (int) $result[0];
         $str    = $result[1];
   
         //zDebugPrint($id);
      }

      //zDebugPrint($op);

      $date          = myapeVarGetDefaultExpDate();
      $typeCode      = myapeVarGetDefaultExpType();
      $amountList    = array();
      $amountCount   = 0;
      $comment       = "";
      if ($op == "a" ||
          $op == "e")
      {
         while (true)
         {
            $result = _ParseGet1Letter($str);
            if ($result == null)
            {
               break;
            }

            //zDebugPrintArray($result);

            $letter = $result[0];
            $str    = $result[1];

            if      ($letter == "d")
            {
               $result = _ParseGet4Letter($str);

               if ($date != $result[0])
               {
                  myapeVarSetDefaultExpDate($result[0]);
               }

               $date   = $result[0];
               $str    = $result[1];

               //zDebugPrint("d " . $date);
            }
            else if ($letter == "t")
            {
               $result   = _ParseGet2Letter($str);

               if ($typeCode != $result[0])
               {
                  myapeVarSetDefaultExpType($result[0]);
               }

               $typeCode = $result[0];
               $str      = $result[1];

               //zDebugPrint("t " . $typeCode);
            }
            else if ($letter == "a")
            {
               $result                     = _ParseGetInteger($str);
               $amountList[$amountCount++] = $result[0];
               $str                        = $result[1];

               //zDebugPrint("a " . $amountList[$amountCount - 1]);
            }
            else if ($letter == "`")
            {
               $comment = $str;

               //zDebugPrint("` " . $comment);
               break;
            }
         }

         if      ($op == "a")
         {
            for ($index = 0; $index < $amountCount; $index++)
            {
               myapeExpListAdd($date, $typeCode, $amountList[$index], $comment);
            }
         }
         else if ($op == "e")
         {
            $index = myapeExpListGetIndexFromId($id);

            if ($index != -1)
            {
               if ($amountCount <= 0)    $amountList[0] = myapeExpListGetAmount(  $index);
               if ($comment     == null) $commnet       = myapeExpListGetComment( $index);
               if ($date        == null) $date          = myapeExpListGetDate(    $index);
               if ($typeCode    == null) $typeCode      = myapeExpListGetTypeCode($index);

               myapeExpListEdit($index, $date, $typeCode, $amount[0], $comment);
            }
         }
      }
   }
   }

// Display the result page.
myapeDisplay();
