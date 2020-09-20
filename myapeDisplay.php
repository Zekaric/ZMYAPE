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
require_once "myapeAssList.php";
require_once "myapeExpTypeList.php";
require_once "myapeExpList.php";

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Display the page.
function myapeDisplay()
{
   if      (myapeVarIsDisplayListAsset())
   {
      myapeDisplayAsset();
   }
   else if (myapeVarIsDisplayListPay())
   {
      myapeDisplayPay();
   }
   else if (myapeVarIsDisplayListExpense())
   {
      myapeDisplayExpense();
   }
}

///////////////////////////////////////////////////////////////////////////////
// Display assets.
function myapeDisplayAsset()
{
   myapeAssListSort();

   ////////////////////////////////////////////////////////////////////////////
   // Print the header.
   print <<<END
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

 <head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="style_reset.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Zekaric:MYAPE:Asset</title>
 </head>

 <body>
  
  <h1>Zekaric : MYAPE : Asset</h1>

  <table>
   <tbody>
     <td>
      <table class="narrow">
       <tbody>
        <tr>
         <th><nobr>Code</nobr></th>
         <th><nobr>Type</nobr></th>
        </tr><tr>
         <td>as</td><td><nobr>Asset</nobr></td>
        </tr><tr>
         <td>bs</td><td><nobr>Bond Savings</nobr></td>
        </tr><tr>
         <td>br</td><td><nobr>Bond TFSA</nobr></td>
        </tr><tr>
         <td>bt</td><td><nobr>Bond RRSP</nobr></td>
        </tr><tr>
         <td>gs</td><td><nobr>GIC Savings</nobr></td>
        </tr><tr>
         <td>gr</td><td><nobr>GIC TFSA</nobr></td>
        </tr><tr>
         <td>gt</td><td><nobr>GIC RRSP</nobr></td>
        </tr><tr>
         <td>ms</td><td><nobr>Mutual Fund Savings</nobr></td>
        </tr><tr>
         <td>mr</td><td><nobr>Mutual Fund TFSA</nobr></td>
        </tr><tr>
         <td>mt</td><td><nobr>Mutual Fund RRSP</nobr></td>
        </tr><tr>
         <td>sa</td><td><nobr>Savings Cash</nobr></td>
        </tr><tr>
         <td>ss</td><td><nobr>Stock Savings</nobr></td>
        </tr><tr>
         <td>sr</td><td><nobr>Stock TFSA</nobr></td>
        </tr><tr>
         <td>st</td><td><nobr>Stock RRSP</nobr></td>
        </tr>
       </tbody>
      </table>
     </td>
     <td class="fillNoPad">
      <table class="wide">
       <tbody>
        <tr>
         <th             ><nobr>Id</nobr></th>
         <th             ><nobr>Type</nobr></th>
         <th             ><nobr>Start Date</nobr></th>
         <th             ><nobr>End Data</nobr></th>
         <th             ><nobr>Start Amount</nobr></th>
         <th             ><nobr>Current/End Amount</nobr></th>
         <th             ><nobr>Delta Amount</nobr></th>
         <th class="desc">Comment</nobr></th>
        </tr>
END;

   $count      = myapeAssListGetCount();
   $totalStart = 0;
   $totalStop  = 0;
   for ($index = 0; $index < $count; $index++)
   {
      $id            = myapeAssListGetId(          $index);
      $type          = myapeAssListGetTypeName(    myapeAssListGetTypeId($index));
      $dateStart     = myapeAssListGetDateStart(   $index);
      $dateStop      = myapeAssListGetDateStop(    $index);
      $amountStart   = myapeAssListGetAmountStart( $index);
      $amountStop    = myapeAssListGetAmountStop(  $index);
      $comment       = myapeAssListGetComment(     $index);

      $amountIntStart = (int) $amountStart;
      $totalStart    += $amountIntStart;

      $amountIntStop  = (int) $amountStop;
      $totalStop     += $amountIntStop;

      $amountStartStr = myapeDisplayAddAmountDecimal($amountStart);
      $amountStopStr  = myapeDisplayAddAmountDecimal($amountStop);

      if (($index % 2) == 0) print "         <tr class=\"altrow\">\n";
      else                   print "         <tr>\n";

      print "" .
         "         <td class=\"num\">" . $id             . "</td>\n" .
         "         <td        ><nobr>" . $type           . "</nobr></td>\n" .
         "         <td class=\"num\">" . $dateStart      . "</td>\n" .
         "         <td class=\"num\">" . $dateStop       . "</td>\n" .
         "         <td class=\"num\">" . $amountStartStr . "</td>\n" .
         "         <td class=\"num\">" . $amountStopStr  . "</td>\n" .
         "         <td class=\"num\">" . (($amountIntStop - $amountIntStart) / 100.0) . "</td>\n" .
         "         <td              >" . $comment        . "</td>\n" .
         "        </tr>\n";
   }


   // Print the total
   print "".
      "         <td class=\"num\">" . $index   . "</td>\n" .
      "         <td              ></td>\n" .
      "         <td class=\"num\"></td>\n" .
      "         <td              >Total</td>\n" .
      "         <td class=\"num\">" . ($totalStart / 100.0) . "</td>\n" .
      "         <td class=\"num\">" . ($totalStop  / 100.0) . "</td>\n" .
      "         <td class=\"num\"></td>\n" .
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
END;

   print <<< END
  <form method="GET">
   <p><input name="cmd" id="cmd" type="text" size="150" autofocus /></p>
   <input type="submit" hidden />
  </form>

  <table>
   <tr>
    <th>Commands</th>
    <th class="desc">Description</th>
   </tr><tr>
    <td><nobr>l[|a|p|e]</nobr></td>
    <td>Switch between a)sset, p)ay, and e)xpense lists. Just "l" cycles to the next list.</td>
   </tr><tr>
    <td><nobr>aa[t[code]][d[YYYYMMDD]][D[YYYYMMDD]][a[value]][A[value]]`[comment]</nobr></td>
    <td>Add a new asset.  't' Type of asset code.  'd', 'D' start and end dates.  'a', 'A' start and current/end amount.  Comment must be last.</td>
   </tr><tr>
    <td><nobr>ae[id][t[code]][d[YYYYMMDD]][D[YYYYMMDD]][a[value]][A[value]]`[comment]</nobr></td>
    <td>Edit an asset.</td>
   </tr><tr>
    <td><nobr>a-[id]</nobr></td>
    <td>Mark an asset as obsolete.</td>
   </tr>
  </table>
  
 </body>

</html>
END;
}

function myapeDisplayPay()
{
   myapeAssListSort();

   ////////////////////////////////////////////////////////////////////////////
   // Print the header.
   print <<<END
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

 <head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="style_reset.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Zekaric:MYAPE:Pay</title>
 </head>

 <body>
  
  <h1>Zekaric : MYAPE : Pay</h1>

  <p>Todo</p>

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
    <td>Switch between a)sset, p)ay, and e)xpense lists. Just "l" cycles to the next list.</td>
   </tr>
   </tr><tr>
    <td><nobr>ya[year]</nobr></td>
    <td>Add a year and set it as the current year.</td>
   </tr><tr>
    <td><nobr>ys[year]</nobr></td>
    <td>Set the current year.</td>
   </tr><tr>
    <td><nobr>tA[name]</nobr></td>
    <td>Add a new credit type.</td>
   </tr><tr>
    <td><nobr>ta[name]</nobr></td>
    <td>Add a new debit type.</td>
   </tr><tr>
    <td><nobr>te[code][name]</nobr></td>
    <td>Edit the name of an expense type</td>
   </tr><tr>
    <td><nobr>pad[MMDD]t[code][a[value]]*`[comment]</nobr></td>
    <td>Add a new pay record.</td>
   </tr><tr>
    <td><nobr>pe[id]d[MMDD]t[code]a[value]`[comment]</nobr></td>
    <td>Edit a pay record.</td>
   </tr>
  </table>
  
 </body>

</html>
END;
}

///////////////////////////////////////////////////////////////////////////////
// Display expenses.
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

      $amountStr = myapeDisplayAddAmountDecimal($amount);

      if (($index % 2) == 0) print "         <tr class=\"altrow\">\n";
      else                   print "         <tr>\n";

      print "" .
         "         <td class=\"num\">" . $id         . "</td>\n" .
         "         <td class=\"num\">" . $date       . "</td>\n" .
         "         <td        ><nobr>" . $type       . "</nobr></td>\n" .
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
END;

   print "" .
      "   <table>\n" .
      "    <tr>\n" .
      "     <td>Defaults:</td>" .
      "<td>Date:</td> <td>" . myapeVarGetDefaultExpDate() . "</td>" .
      "<td>Type:</td> <td>" . myapeVarGetDefaultExpType() . "</td>\n" .
      "<td class=\"fill\" />\n" .
      "    </tr>\n" .
      "   </table>\n";

   print <<< END
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
    <td>Switch between a)sset, p)ay, and e)xpense lists. Just "l" cycles to the next list.</td>
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

///////////////////////////////////////////////////////////////////////////////
// Add in the decimal point.
function myapeDisplayAddAmountDecimal($amount)
{
   $amountLen = strlen($amount);
   $centPos   = $amountLen - 2;
   return substr($amount, 0, $centPos) . "." . substr($amount, $centPos);
}
