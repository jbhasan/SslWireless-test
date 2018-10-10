<?php
namespace Sayeed\Sslwireless;

class Sslwireless
{
	protected $config;

	public function __construct($config) {
		$this->config = $config;
	}

	public function payment_by_sslwireless($products, $tran_id = false) {
		/* PHP */ #v2.2
		$post_data = array();
		$post_data['store_id'] = $this->config->store_id;
		$post_data['store_passwd'] = $this->config->store_passwd;
		$post_data['currency'] = $this->config->currency;
		$post_data['success_url'] = $this->config->success_url;
		$post_data['fail_url'] = $this->config->fail_url;
		$post_data['cancel_url'] = $this->config->cancel_url;

		$post_data['total_amount'] = array_sum(array_map(function($item) { return $item['amount']; }, $products));;
		$post_data['tran_id'] = ($tran_id) ? $tran_id : "SSLCZ_TEST_".uniqid();
		# $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

		# EMI INFO
		$post_data['emi_option'] = "0";
		// $post_data['emi_max_inst_option'] = "9";
		// $post_data['emi_selected_inst'] = "9";

		# CUSTOMER INFORMATION
		// $post_data['cus_name'] = "Test Customer";
		// $post_data['cus_email'] = "test@test.com";
		// $post_data['cus_add1'] = "Dhaka";
		// $post_data['cus_add2'] = "Dhaka";
		// $post_data['cus_city'] = "Dhaka";
		// $post_data['cus_state'] = "Dhaka";
		// $post_data['cus_postcode'] = "1000";
		// $post_data['cus_country'] = "Bangladesh";
		// $post_data['cus_phone'] = "01711111111";
		// $post_data['cus_fax'] = "01711111111";

		# SHIPMENT INFORMATION
		// $post_data['ship_name'] = "Store Test";
		// $post_data['ship_add1 '] = "Dhaka";
		// $post_data['ship_add2'] = "Dhaka";
		// $post_data['ship_city'] = "Dhaka";
		// $post_data['ship_state'] = "Dhaka";
		// $post_data['ship_postcode'] = "1000";
		// $post_data['ship_country'] = "Bangladesh";

		# OPTIONAL PARAMETERS
		// $post_data['value_a'] = "ref001";
		// $post_data['value_b '] = "ref002";
		// $post_data['value_c'] = "ref003";
		// $post_data['value_d'] = "ref004";

		# CART PARAMETERS
		$post_data['cart'] = json_encode($products);
		$post_data['product_amount'] = $post_data['total_amount'];
		$post_data['vat'] = "0";
		$post_data['discount_amount'] = "0";
		$post_data['convenience_fee'] = "0";


		# REQUEST SEND TO SSLCOMMERZ
		$direct_api_url = $this->config->api_url;

		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $direct_api_url );
		curl_setopt($handle, CURLOPT_TIMEOUT, 30);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($handle, CURLOPT_POST, 1 );
		curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


		$content = curl_exec($handle );

		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		if($code == 200 && !( curl_errno($handle))) {
			curl_close( $handle);
			$sslcommerzResponse = $content;
		} else {
			curl_close( $handle);
			echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
			exit;
		}

		# PARSE THE JSON RESPONSE
		$sslcz = json_decode($sslcommerzResponse, true );

		if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
			# THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
			# echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
			echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
			# header("Location: ". $sslcz['GatewayPageURL']);
			exit;
		} else {
			echo "JSON Data parsing error!";
		}
	}
}
