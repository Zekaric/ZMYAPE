<?php
/* zLockCreate *********************************************************************

Author: Robbert de Groot

Description:

This is a simple locking mechanism for PHP and web activity.  

To lock on anything you provide a string to lock.  Basically a name of the lock.

   $lock = zLockCreate("MyLockName");
   if ($lock == "")
   {
      // We did not get a lock.
   }
   else
   {
      // $lock is the actual lock name.
   }

What is happening is that we are trying to make a directory on the server's file
system.  This is an atomic operation that if two processes/threads are trying
to do the same thing will result in failure for one of them.  I.E. One process
will create the directory and succeed.  The other will try to do the same but
will fail because the directory has been created by the other process. 

If you are trying to lock a file there is a special function for that.

   $lock = zLockCreateFile("myfilename");
   if ($lock == "")
   {
      // We did not get a lock.
   }
   else
   {
      // $lock is the actual lock name.
   }

This will make a folder called "lock_file_" + "myfilename".  

Unlocking the named lock or file lock is done using...

   zLockDestroy($lock);

This just removes the folder that was created earlier when locking.  This allows
the other processes to then create a lock of the same name or file.  

As I said this is a simple locking mechanism.  This could easily starve a 
process if there are too many processes competing for the same lock.  So this
is not perfect and wasn't intended to be. Some other locking mechanism should
be employed if you are running into such a situation.

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
// Generic lock, can be anything you want to mutex on.
// Return the name of the lock.
function zLockCreate($name)
{
   $count = 0;

   $folderName = "lock_" . $name;
   
   while (true)
   {
      // mkdir is 'atomic' in that it will succeed if we created the directory.
      // it will fail if we did not create the directory.  
      if (mkdir($folderName))
      {
         // If we succeed we have the lock.
         break;
      }
      
      // If we fail we wait a bit and try again.
      $count++;
      if ($count == 10)
      {
         // timing out.
         return "";
      }
      
      usleep(500000);
   }
   
   return $folderName;
}

////////////////////////////////////////////////////////////////////////////////
// Mutexing on a file.  Ensuring only one process has access to the file at any
// given time.
function zLockCreateFile($fileName)
{
   // Locking on the file.
   return zLockCreate("file_" . $fileName);
}

////////////////////////////////////////////////////////////////////////////////
// Unlocking a generic lock.
function zLockDestroy($name)
{
   rmdir($name);
}
