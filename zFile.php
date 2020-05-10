<?php
/* zFile *********************************************************************

Author: Robbert de Groot

Description:

This file are some helper functions when dealing with files on the server.  It
does handle locking of the files via zLock; so that only one html connection
can only read/write a file at any given time.  

Often the lock flag is optional and defaults to true, meaning we try and lock
before doing anything with the file.

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

////////////////////////////////////////////////////////////////////////////////
// Include
require_once "zDebug.php";
require_once "zLock.php";

////////////////////////////////////////////////////////////////////////////////
// Create a directory.
function zDirCreate($dirName)
{
   return mkdir($dirName);
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if the directory exists.
function zDirIsExisting($dirName)
{
   return file_exists($dirName);
}

////////////////////////////////////////////////////////////////////////////////
// Append to a file
function zFileAppendText($filePath, $string, $isLocking)
{
   // Open the file.
   $fileCon = zFileConnect($filePath, "a", $isLocking);
   if (!zFileConnectIsGood($fileCon))
   {
      return false; // "zFileStore: ERROR: Unable to open file '" . $filePath . "' for writing.";
   }

   // Write the file contents.
   fwrite($fileCon["file"], $string);
   
   // Close the file.
   zFileDisconnect($fileCon);

   return true;
}


////////////////////////////////////////////////////////////////////////////////
// Open a file.
function zFileConnect($filePath, $mode, $isLocking)
{
   $fileCon = array();

   $fileCon["name"]       = $filePath;
   $fileCon["isLocking"]  = $isLocking;
   $fileCon["lock"]       = "";
   $fileCon["file"]       = false;

   if ($isLocking)
   {
      $fileCon["lock"] = zLockCreateFile($filePath);
      if ($fileCon["lock"] == "")
      {
         return $fileCon;
      }
   }

   $fileCon["file"] = fopen($filePath, $mode);
   
   // File open failed.
   if (!$fileCon["file"])
   {
      if ($isLocking)
      {
         // Clean up
         zLockDestroy($fileCon["lock"]);
      }
      $fileCon["file"] = false;
   }
   
   return $fileCon;
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if the file open succeeded.
function zFileConnectIsGood($fileCon)
{
   return ($fileCon["file"] != false);
}

////////////////////////////////////////////////////////////////////////////////
// Close the open file.
function zFileDisconnect($fileCon)
{
   if ($fileCon["file"] != false)
   {
      fclose($fileCon["file"]);

      if ($fileCon["isLocking"] &&
          $fileCon["lock"] != "")
      {
         zLockDestroy($fileCon["lock"]);
      }
      
      $fileCon["lock"] = "";
      $fileCon["file"] = false;
   }
}

////////////////////////////////////////////////////////////////////////////////
// Check to see if a file exists.
function zFileIsExisting($filePath)
{
   return file_exists($filePath);
}

////////////////////////////////////////////////////////////////////////////////
// Read in a complete text file as one big string.  Does not trim new lines.
function zFileLoadText($filePath, $isLocking)
{
   $text = "";
   
   // Open the file.
   $fileCon = zFileConnect($filePath, "r", $isLocking);
   if (!zFileConnectIsGood($fileCon))
   {
	   return "zFileLoad: ERROR: Unable to open file '" . $filePath . "' for reading.";
   }

   // Read in the file contents.
   while (true)
   {
      $line = fgets($fileCon["file"]);
      if ($line == false)
      {
         break;
      }
        
      $text .= $line;
   }
      
   // Close the file.
   zFileDisconnect($fileCon);
   
   // Return the file contents.
   return $text;
}

////////////////////////////////////////////////////////////////////////////////
// Read in a complete text file as an array of strings.
function zFileLoadTextArray($filePath, $isLocking)
{
   $lineArray = array();
   
   // Open the file.
   $fileCon = zFileConnect($filePath, "r", $isLocking);
   if (!zFileConnectIsGood($fileCon))
   {
	   return "zFileLoad: ERROR: Unable to open file '" . $filePath . "' for reading.";
   }

   // Read in the file contents.
   while (true)
   {
      $line = fgets($fileCon["file"]);
      if ($line == false)
      {
         break;
      }
        
      array_push($lineArray, rtrim($line, "\n"));
   }
      
   // Close the file.
   zFileClose($filePath, $fileCon, $lock);
   
   // Return the array of lines.
   return $lineArray;
}

////////////////////////////////////////////////////////////////////////////////
// Store the file contents from a single string.
function zFileStoreText($filePath, $string, $isLocking)
{
   // Open the file.
   $fileCon = zFileConnect($filePath, "w", $isLocking);
   if (!zFileConnectIsGood($fileCon))
   {
      return false; // "zFileStore: ERROR: Unable to open file '" . $filePath . "' for writing.";
   }

   // Write the file contents.
   fwrite($fileCon["file"], $string);
   
   // Close the file.
   zFileDisconnect($fileCon);

   return true;
}

////////////////////////////////////////////////////////////////////////////////
// Store the file contents from a string array.
function zFileStoreTextArray($filePath, $lineArray, $isLocking)
{
   // Open the file.
   $fileCon = zFileConnect($filePath, "w", $isLocking);
   if (!zFileConnectIsGood($fileCon))
   {
      return false; // "zFileStore: ERROR: Unable to open file '" . $filePath . "' for writing.";
   }

   // Write all the lines in the array to the file.
   $lineCount = count($lineArray);
   for ($index = 0; $index < $lineCount; $index++)
   {
      fwrite($fileCon["file"], $lineArray[$index] . "\n");
   }
      
   // Close the file.
   zFileDisconnect($fileCon);

   return true;
}

////////////////////////////////////////////////////////////////////////////////
// Store the file contents from a single string.
function zFileWriteText($fileCon, $string)
{
   // Write the file contents.
   fwrite($fileCon["file"], $string);

   return true;
}
