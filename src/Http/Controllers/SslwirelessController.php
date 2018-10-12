<?php
namespace Sayeed\Sslwireless\Http\Controllers;

use App\Http\Controllers\Controller;
use League\Flysystem\Filesystem;

class SslwirelessController extends Controller {

	public function setConfig() {
		$config_file_path = '../config/sayeed.php';
		if(file_exists($config_file_path)) {
			if (config('sayeed.sslwireless') == null) {
				$myfile = fopen($config_file_path, "w") or dd("Config file auto-update is not possible. Please update manually");
			} else {
				dd('Config file already updated');
			}
		} else {
			$myfile = fopen($config_file_path, "w");
		}
		$txt = "";
		$txt .= "<?php\n";
		$txt .= "\n";
		$txt .= "return [\n";
		$txt .= "	'sslwireless' => [\n";
		$txt .= "		'connection' => [\n";
		$txt .= "			'api_url' 		=> 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php', #sslcommerz URL\n";
		$txt .= "			'store_id' 		=> '123456789', #Your Store ID\n";
		$txt .= "			'store_passwd' 	=> '123456789@ssl', #Your Store Password\n";
		$txt .= "			'currency' 		=> 'BDT', #required\n";
		$txt .= "			'success_url' 	=> 'http://localhost/?success', #return success URL\n";
		$txt .= "			'fail_url' 		=> 'http://localhost/?fail', #return fail URL\n";
		$txt .= "			'cancel_url' 	=> 'http://localhost/?cancel', #return cancel URL\n";
		$txt .= "		]\n";
		$txt .= "	]\n";
		$txt .= "];\n";

		fwrite($myfile, $txt);
		fclose($myfile);

		dd('Successfully added config file');

	}

}