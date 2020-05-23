<?php
/* myapeDisplay ***************************************************************

Author: Robbert de Groot

Description:

Display the interface.

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
// constant

///////////////////////////////////////////////////////////////////////////////
// include
require_once "zDebug.php";

require_once "myapeVar.php";
require_once "myapeYearList.php";
require_once "myapeExpTypeList.php";
require_once "myapeExpList.php";

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Display the page.
function myapeDisplay()
{
   myapeDisplayExpense();
}

function myapeDisplayExpense()
{
   myapeYearListSort();
   myapeExpListSort();

   ////////////////////////////////////////////////////////////////////////////
   // Print the header.
   print <<<END
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

 <head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="style_reset.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Zekaric:MYAPE:Expense</title>
 </head>

 <body>
  
  <h1>Zekaric : MYAPE : Expense</h1>

  <table>
   <tbody>
    <tr>
END;

   ////////////////////////////////////////////////////////////////////////////
   // Print the year column 
   print <<< END
     <td>
      <table class="narrow">
       <tbody>
        <tr>
         <th><nobr>Vis</nobr></th>
         <th><nobr>Year</nobr></th>
        </tr>
END;

   $currYear = myapeVarGetYearCurrent();
   $count    = myapeYearListGetCount();
   for ($index = 0; $index < $count; $index++)
   {
      $year = myapeYearListGetYear($index);

      // Get the visibilty string.
      $currYearStr = "<img class=sized src=rankBit0.svg />";
      if ($year == $currYear)
      {
         $currYearStr = "<img class=sized src=rankBit1.svg />";
      }

      if (($index % 2) == 0) print "         <tr class=\"altrow\">\n";
      else                   print "         <tr>\n";

      print "" . 
         "          <td class=\"bool\">" . $currYearStr . "</td>\n" .
         "          <td>"                . $year        . "</td>\n" .
         "         </tr>\n";
   }

   print <<< END
       </tbody>
      </table>
     </td>
END;

   /////////////////////////////////////////////////////////////////////////
   // Expense type List
   print <<< END
     <td>
      <table class="narrow">
       <tbody>
        <tr>
         <th><nobr>Code</nobr></th>
         <th><nobr>Type</nobr></th>
        </tr>
END;

   $count    = myapeExpTypeListGetCount();
   for ($index = 0; $index < $count; $index++)
   {
      $code = myapeExpTypeListGetCode($index);
      $name = myapeExpTypeListGetName($index);

      if (($index % 2) == 0) print "         <tr class=\"altrow\">\n";
      else                   print "         <tr>\n";

      print "" . 
         "          <td class=\"mono\">" . $code .        "</td>\n" .
         "          <td><nobr>"          . $name . "</nobr></td>\n" .
         "         </tr>\n";
   }

   print <<< END
       </tbody>
      </table>
     </td>
END;

   /////////////////////////////////////////////////////////////////////////
   // Expenses List
   print <<< END
     <td class="fillNoPad">
      <table class="wide">
       <tbody>
        <tr>
         <th             ><nobr>Id</nobr></th>
         <th             ><nobr>Date</nobr></th>
         <th             ><nobr>Type</nobr></th>
         <th             ><nobr>Amount</nobr></th>
         <th class="desc"><nobr>Comment</nobr></th>
        </tr>
END;

   $count = myapeExpListGetCount();
   $total = 0;
   for ($index = 0; $index < $count; $index++)
   {
      $id      = myapeExpListGetId(     $index);
      $date    = myapeExpListGetDate(   $index);
      $type    = myapeExpTypeListGetName(myapeExpTypeListGetIndexFromCode(myapeExpListGetTypeId($index)));
      $amount  = myapeExpListGetAmount( $index);
      $comment = myapeExpListGetComment($index);

      $amountInt = (int) $amount;
      $total    += $amountInt;

      $amountLen = strlen($amount);
      $centPos   = $amountLen - 2;
      $amountStr = substr($amount, 0, $centPos) . "." . substr($amount, $centPos);


      if (($index % 2) == 0) print "         <tr class=\"altrow\">\n";
      else                   print "         <tr>\n";

      print "" .
         "         <td class=\"num\">" . $id         . "</td>\n" .
         "         <td class=\"num\">" . $date       . "</td>\n" .
         "         <td              >" . $type       . "</td>\n" .
         "         <td class=\"num\">" . $amountStr  . "</td>\n" .
         "         <td              >" . $comment    . "</td>\n" .
         "        </tr>\n";
   }

   // Print the total
   print "".
         "         <td class=\"num\">" . $index   . "</td>\n" .
         "         <td class=\"num\"></td>\n" .
         "         <td              >Total</td>\n" .
         "         <td class=\"num\">" . ($total / 100.0) . "</td>\n" .
         "         <td              ></td>\n" .
         "        </tr>\n";

   print <<< END
       </tbody>
      </table>
     </td>
END;

   ////////////////////////////////////////////////////////////////////////////
   // Print the rest of the page.
   print <<< END
    </tr>
   </tbody>
  </table>

  <form method="GET">
   <p><input name="cmd" id="cmd" type="text" size="150" autofocus /></p>
   <input type="submit" hidden />
  </form>

  <table>
   <tr>
    <th>Commands</th>
    <th class="desc">Description</th>
   </tr><tr>
    <td><nobr>l</nobr></td>
    <td>Switch between t)ask and p)roject lists</td>
   </tr>
   </tr><tr>
    <td><nobr>ya[year]</nobr></td>
    <td>Add a year and set it as the current year.</td>
   </tr><tr>
    <td><nobr>ys[year]</nobr></td>
    <td>Set the current year.</td>
   </tr><tr>
    <td><nobr>ta[name]</nobr></td>
    <td>Add a new expense type.</td>
   </tr><tr>
    <td><nobr>te[code][name]</nobr></td>
    <td>Edit the name of an expense type</td>
   </tr><tr>
    <td><nobr>ead[MMDD]t[code][a[value]]*`[comment]</nobr></td>
    <td>Add a new expense.</td>
   </tr><tr>
    <td><nobr>ee[id]d[MMDD]t[code]a[value]`[comment]</nobr></td>
    <td>Edit an expense.</td>
   </tr>
  </table>
  
 </body>

</html>
END;
}
   