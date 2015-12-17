<?
/**
 *
 * PHP Forced Download
 * Propalms.com needed to allow customers to download their software but first needed the customer to be verified.
 * This code checks if the user is verified and then forces the latest version of software to download to the client's machine.
 * @author Jay Fortner for Propalms, 2006-2007
 * 
**/

if (!isset($_GET['verify'])) {

	header('location: $url');
	die();

} else {

	include('connect.php');

	$verification_code = $_GET['verify'];

	$check_against = mysql_fetch_assoc(mysql_query("SELECT * FROM download_verification WHERE verification_code = '$verification_code' LIMIT 1 "));

	$compare = strcmp($verification_code, $check_against['verification_code']);

	if ($compare == 0) {

		$verified_code = 1;

		$submitted_date = strtotime($check_against['date']);

		$current_date = strtotime(time(), date('Y-m-d'));

		if ($current_date - $submitted_date <= 30) {

			$verified_date = 1;

		}

	}


	if ($verified_code == 1 && $verified_date == 1) {

		mysql_query("UPDATE download_verification SET download_count = download_count + 1 WHERE verification_code = '$verification_code' LIMIT 1") or die("Database Update Error");

		$version = $check_against['version'];
		$ftp_connection = "ftp://$url/";
		$enc_folder = "$encryption/";
		$size = filesize($enc_folder.$version.'.zip');
		ini_set('allow_url_fopen', true);
		header('HTTP/1.1 200 OK');
		$url = $ftp_connection.$version.'.zip';

		$mm_type="application/octet-stream";

		header("Cache-Control: public");
		header("Pragma: hack");
		header("Content-Type: " . $mm_type);
		header("Content-Length: " . $size );
		header("Content-Disposition: attachment; filename=\"Propalms-".$version.".zip\"");
		header("Content-Transfer-Encoding: binary\n");

		ini_set('allow_url_fopen', true);

		$buffer = "";
		$fp = fopen($url, 'rb');
		while (!feof($fp)) {
		  $buffer = fread($fp, $size);
		  print $buffer;
		}
		fclose ($fp);


		$update_count = mysql_query("UPDATE download_verification SET download_count++ WHERE verification_code = '$verification_code' LIMIT 1") or die("Database Update Error");

		header('location: $url');
		die();

	} else {

		header('location: $url');
		die();

	}

}