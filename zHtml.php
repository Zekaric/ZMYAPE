<?php
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

////////////////////////////////////////////////////////////////////////////////
// Start of the html document.
function zHtmlDoc($header, $body)
{
   return "".
      "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n" .
      "<html>\n" .
      $header . 
      $body . 
      "</html>\n";
}

////////////////////////////////////////////////////////////////////////////////
// Header creation
function zHtmlHead(...$content)
{
   $str = "" .
      "<head>\n" .
      "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">";

   foreach ($content as $s)
   {
      $str .= $s;
   }
   
   $str .= "</head>\n";
   
   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// Add a CSS link in the header.
function zHtmlHeadLinkCSS($file)
{
   return "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $file . "\" >\n";
}

////////////////////////////////////////////////////////////////////////////////
// Add a Javascript link in the header.
function zHtmlHeadLinkJS($file)
{
   return "<script href=\"" . $file . "\" ></script>\n";
}

////////////////////////////////////////////////////////////////////////////////
// Set the title in the header.
function zHtmlHeadTitle($title)
{
   return "<title>" . $title . "</title>\n";
}

////////////////////////////////////////////////////////////////////////////////
// Body creation
function zHtmlBody($class, ...$content)
{
   $str = "";
   
   if ($class == "") { $str .= "<body>\n"; }
   else              { $str .= "<body class=\"" . $class . "\">\n"; }
   
   foreach ($content as $s)
   {
      $str .= $s;
   }
   
   $str .= "</body>\n";
   
   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// Form creation
function zHtmlForm($isGet, $command, ...$content)
{
   $str = "<form action=\"" . $command . "\" method=";
      
   if ($isGet) { $str .= "GET  >\n"; }
   else        { $str .= "POST >\n"; }
   
   foreach ($content as $s)
   {
      $str .= $s;
   }
   
   $str .= "</form>\n";
   
   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// Display a button
function zHtmlFormInputButton($class, $nameStr, $valueStr)
{
   if ($class == "") { return "<input"                 . " type=button name=" . $nameStr . " value=\"" . $valueStr . "\" />\n"; }
                       return "<input class=" . $class . " type=button name=" . $nameStr . " value=\"" . $valueStr . "\" />\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display the form submit button.
function zHtmlFormInputButtonSubmit($class)
{
   if ($class == "") { return "<input"                 . " type=submit />\n"; }
                       return "<input class=" . $class . " type=submit />\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display a checkbox.
function zHtmlFormInputCheck($class, $nameStr, $valueStr)
{
   if ($class == "")
   {
      if ($valueStr) { return "<input"                 . " type=checkbox name=" . $nameStr . " checked />\n"; }
                       return "<input"                 . " type=checkbox name=" . $nameStr .         " />\n";
   }   
   if ($valueStr) {    return "<input class=" . $class . " type=checkbox name=" . $nameStr . " checked />\n"; }
                       return "<input class=" . $class . " type=checkbox name=" . $nameStr .         " />\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display a text field.
function zHtmlFormInputText($class, $nameStr, $valueStr="", $size="80")
{
   if ($class == "") { return "<input"                 . " type=text size=" . $size . " name=" . $nameStr . " value=\"" . $valueStr . "\" />\n"; }
                       return "<input class=" . $class . " type=text size=" . $size . " name=" . $nameStr . " value=\"" . $valueStr . "\" />\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display a password field.
function zHtmlFormInputPassword($class, $nameStr, $valueStr, $size="80")
{
   if ($class == "") { return "<input"                 . " type=password size=" . $size . " name=" . $nameStr . " value=\"" . $valueStr . "\" />\n"; }
                       return "<input class=" . $class . " type=password size=" . $size . " name=" . $nameStr . " value=\"" . $valueStr . "\" />\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display a link.
function zHtmlLink($class, $link, $content)
{
   if ($class == "") { return "<a"                 . " href=\"" . $link . "\">" . $content . "</a>\n"; }
                       return "<a class=" . $class . " href=\"" . $link . "\">" . $content . "</a>\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display a bunch of paragraphs.
function zHtmlPara($class, ...$content)
{
   $str = "";
   
   foreach ($content as $s)
   {
      if ($class == "") { $str .= "<p>"                      . $s . "</p>\n"; }
      else              { $str .= "<p class=" . $class . ">" . $s . "</p>\n"; }
   }

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// Display a header
function zHtmlParaHeader($class, $level, $content)
{
   if ($class == "") { return "<h" . $level . ">"                      . $content . "</h" . $level . ">\n"; }
                       return "<h" . $level . " class=" . $class . ">" . $content . "</h" . $level . ">\n";
}

////////////////////////////////////////////////////////////////////////////////
// change all the spaces to "&nbsp;".
function zHtmlStrNonBreaking($str)
{
   return str_replace(" ", "&nbsp;", $str);
}

////////////////////////////////////////////////////////////////////////////////
// Display a table.
function zHtmlTable($class, $content)
{
   if ($class == "") { return "<table"                 . "><tbody>\n" . $content . "</tbody></table>\n"; }
                       return "<table class=" . $class . "><tbody>\n" . $content . "</tbody></table>\n";
}

////////////////////////////////////////////////////////////////////////////////
// The rows of the table.
function zHtmlTableRow($class, ...$content)
{
   $str = "";
   foreach ($content as $s)
   {
      if ($class == "") { $str .= "<tr>\n"                      . $s . "</tr>\n"; }
      else              { $str .= "<tr class=" . $class . ">\n" . $s . "</tr>\n"; }                          
   }

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// The columns of the table.
function zHtmlTableCol($class, ...$content)
{
   $str = "";

   foreach ($content as $s)
   {
      if ($class == "") { $str .= "<td>"                      . $s. "</td>\n"; }
      else              { $str .= "<td class=" . $class . ">" . $s. "</td>\n"; }
   }

   return $str;
}

////////////////////////////////////////////////////////////////////////////////
// Display a bullet list.
function zHtmlListBullet($class, ...$content)
{
   if ($class == "") { $str = "<ul>\n"; }
   else              { $str = "<ul class=" . $class . ">\n"; }
   
   foreach ($content as $s)
   {
      if ($class == "") { $str .= "<li>"                      . $s . "</li>\n"; }
      else              { $str .= "<li class=" . $class . ">" . $s . "</li>\n"; }
   }
   
   $str .= "</ul>\n";
}

////////////////////////////////////////////////////////////////////////////////
// Display a numbered list.
function zHtmlListNumber($class, ...$content)
{
   if ($class == "") { $str = "<ol>\n"; }
   else              { $str = "<ol class=" . $class . ">\n"; }
   
   foreach ($content as $s)
   {
      if ($class == "") { $str .= "<li>"                      . $s . "</li>\n"; }
      else              { $str .= "<li class=" . $class . ">" . $s . "</li>\n"; }
   }
   
   $str .= "</ol>\n";
}
