<?
/**
 *
 * PHP Multi-Processing Usage Example
 * This code reads in a file, splits it up, and then let's the Multi-Process Class handle the chunks of data.
 * @uses multi-processing.class.php to create PHP processes.
 * @uses pointer.php to execute tasks.
 * @author Jay Fortner, 2007
 * 
**/

$MT = new Terminal;
$MT->cmd = 'php /home/jayfortner/webroot/dev/tasking/pointer.php';
$MT->totalSockets = 15;

ini_set("memory_limit","128M");
$file ='http://www.website.com/file.txt';

$handle = fopen($file, "r");

$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}

$replace = array("\r\n", "\r", "\n");

$contents = str_replace($replace, ',', $contents);

$content = explode(',', $contents);

$MT->input = $content;

fclose($handle);

$MT->run();
exit();