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

///////////////////////////////////////////////////////////////////////////////
// global
// function

///////////////////////////////////////////////////////////////////////////////
// Display the page.
function myapeDisplay()
{
   myapeYearListSort();

   ////////////////////////////////////////////////////////////////////////////
   // Print the header.
   print <<<END
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

 <head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="style_reset.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Zekaric:MYAPE</title>
 </head>

 <body>
  
  <h1>Zekaric : MYAPE 
END;
   
   // Print what we are displaying.

   print <<<END
</h1>

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
   // Asset List / Pay List / Expenses List

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
   </tr>
  </table>
  
 </body>

</html>
END;
}
   