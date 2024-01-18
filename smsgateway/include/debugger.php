<?php
define('ENABLE_DEBUG', true);
define('DEBUGPATH', "/workspace/html/jibas-3.2/debug.log");

function DebugLog($variable, $type)
{
   if (!ENABLE_DEBUG)
      return;
   
   $fp = fopen(DEBUGPATH, 'a');
   fwrite($fp, "--- $type INFORMATION FROM " . $_SERVER['SCRIPT_NAME'] . " (" . date('Y-m-d H:i:s') . ") ---\r\n");
   foreach($variable as $key => $value)
   {
      $keyArr[] = $key;
   }
   
   sort($keyArr);
   
   for($i = 0; $i < count($keyArr); $i++)
   {
      $key = $keyArr[$i];
      fwrite($fp, "  " . $key . " = " . $variable[$key] . "\r\n");
   }
   
   fwrite($fp, "\r\n");
   fclose($fp);
}

function DebugSession()
{
   DebugLog($_SESSION, "SESSION");
}

function DebugRequest()
{
   DebugLog($_REQUEST, "REQUEST");
}

function DebugArray($array)
{
   DebugLog($array, "ARRAY");
}

function DebugVar($variable)
{
   $arr[0] = $variable;
   DebugLog($arr, "VARIABLE");
}

function ShowExit($value): never
{
	echo $value;
	exit();
}

function ShowPreExit($value): never
{
	echo "<pre>";
	print_r($value);
	echo "</pre>";
	exit();
}


?>