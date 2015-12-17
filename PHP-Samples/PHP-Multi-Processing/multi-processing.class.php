<?
/**
 *
 * PHP Multi-Processing Class
 * This code allows developers to parse large sets of data using a number of PHP processes created by this code.
 * @uses pointer.php to execute tasks.
 * @author Jay Fortner, 2007
 * 
**/

class Terminal {

	var $startTime;
	var $endTime;
	var $timeout;
	var $totalSockets;
	var $readBlock;
	var $cmd;
	var $execute;
	var $descriptorspec;
	var $sockets = array();
	var $streams = array();
	var $pipes = array();
	var $handles = array();
	var $__status = array();

	/********************************************************************/
	// Initializes Class
	// Build default values
	function __construct() {
		$this->endTime = 0;
		$this->timeout = 10;
		$this->totalSockets = 4;
		$this->readBlock = 1024;
		$this->descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "w")
			);
	} // function

	/********************************************************************/
	// Run Full Class
	// Use this if you want the startTime, endTime, and average loads.
	// Otherwise, use process();
	function run()
	{
		$this->startTime = date('Y-m-d H:i:s');
		$this->__status['start_loadavg'] = sys_getloadavg();
		$this->process();
		$this->__status['end_loadavg'] = sys_getloadavg();
		$this->endTime = date('Y-m-d H:i:s');
		return TRUE;
	} // function

	/********************************************************************/
	// Split input array into sections for each stream
	function split_input($i)
	{
		$input = $this->input;
		$sockets = $this->totalSockets;
		$offset = ceil(count($input) / $sockets);
		$a = $offset * $i;
		return array_slice($input, $a, $offset, TRUE);
	} // function

	/********************************************************************/
	// Handle Multi-Tasking
	// Run Multiple Tasks from Split Input
	function process()
	{

		for($i=0;$i<=$this->totalSockets-1;$i++) {
			$this->execute = '$inputArr = '.var_export($this->split_input($i), TRUE).';';

			$this->start_process($i, $this->cmd, $this->execute);
		} // for

		echo "\r\n".'Waiting on Processes to end...'."\r\n\r\n";

		while(count($this->streams)) {
			sleep(1);

			$read = $this->streams;
			foreach($read as $id => $r) {
				$this->kill_process($id);
			} // foreach
		} // while
		return TRUE;
	} // function

	/********************************************************************/
	// Handle Script Execution
	function start_process($id, $cmd, $execute = null)
	{		
	  $this->handles[$id] = $process = proc_open($cmd, $this->descriptorspec, $this->pipes[$id], null, null);
		$this->streams[$id] = $this->pipes[$id][1];
		stream_set_blocking($this->pipes[$id][2], 0);
		if(is_resource($process)) {
			echo 'Starting Process '.$id."\t";

			if(fwrite($this->pipes[$id][0], $execute)) {
				fclose($this->pipes[$id][0]);
				echo 'Started.'."\r\n";
			} else {
				echo 'Failed.'."\r\n";
			}// if

			return $process;
		} // if
		return TRUE;
	} // function

	/********************************************************************/
	// Handle Script Completion
	function kill_process($id)
	{		
		$status = proc_get_status($this->handles[$id]);
		if($status['running'] === false) {
			echo 'Closing Process '.$id."\t";

			fclose($this->pipes[$id][1]);
			fclose($this->pipes[$id][2]);

			proc_close($this->handles[$id]);

			echo 'Closed.'."\r\n";

			unset($this->streams[$id]);
			unset($this->pipes[$id]);
			unset($this->handles[$id]);
		} // if
		return TRUE;
	} // function

} // class