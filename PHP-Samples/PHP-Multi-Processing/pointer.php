<?
/**
 *
 * PHP Multi-Processing Pointer
 * This code accepts the chunks of data from the Multi-Processing Class and executes tasks.
 * @uses pointer.php to execute tasks.
 * @author Jay Fortner, 2007
 * 
**/

# read incoming command
if($fh = fopen('php://stdin','rb')) {
$val_in = stream_get_contents($fh);
  fclose($fh);
}

# execute incoming command

if($val_in)
	eval($val_in);

sleep(1);

# Run code for task. $inputArr given
$resource = mysql_connect('server', 'username','password');
mysql_select_db('database');
foreach($inputArr as $key => $value) {
	$query = "INSERT INTO tasking SET arrKey = '".addslashes($key)."'";
	mysql_query($query);
}
mysql_close($resource);

exit();