<?php

/**
 * (c) Copyright 2018 - 2020 Visa. All Rights Reserved.**
 *
 * NOTICE: The software and accompanying information and documentation (together, the “Software”) remain the property of and are proprietary to Visa and its suppliers and affiliates. The Software remains protected by intellectual property rights and may be covered by U.S. and foreign patents or patent applications. The Software is licensed and not sold.*
 *
 *  By accessing the Software you are agreeing to Visa's terms of use (developer.visa.com/terms) and privacy policy (developer.visa.com/privacy).In addition, all permissible uses of the Software must be in support of Visa products, programs and services provided through the Visa Developer Program (VDP) platform only (developer.visa.com). **THE SOFTWARE AND ANY ASSOCIATED INFORMATION OR DOCUMENTATION IS PROVIDED ON AN “AS IS,” “AS AVAILABLE,” “WITH ALL FAULTS” BASIS WITHOUT WARRANTY OR  CONDITION OF ANY KIND. YOUR USE IS AT YOUR OWN RISK.** All brand names are the property of their respective owners, used for identification purposes only, and do not imply product endorsement or affiliation with Visa. Any links to third party sites are for your information only and equally  do not constitute a Visa endorsement. Visa has no insight into and control over third party content and code and disclaims all liability for any such components, including continued availability and functionality. Benefits depend on implementation details and business factors and coding steps shown are exemplary only and do not reflect all necessary elements for the described capabilities. Capabilities and features are subject to Visa’s terms and conditions and may require development,implementation and resources by you based on your business and operational details. Please refer to the specific API documentation for details on the requirements, eligibility and geographic availability.*
 *
 * This Software includes programs, concepts and details under continuing development by Visa. Any Visa features,functionality, implementation, branding, and schedules may be amended, updated or canceled at Visa’s discretion.The timing of widespread availability of programs and functionality is also subject to a number of factors outside Visa’s control,including but not limited to deployment of necessary infrastructure by issuers, acquirers, merchants and mobile device manufacturers.*
 *
 */

function getDeal($cardnumber){
		
	$url = 'https://sandbox.api.visa.com/vmorc/offers/v1/byfilter?promoting_country=234&redemption_country=234';
	//$url = 'https://sandbox.api.visa.com/vmorc/offers/v1/byfilter?accountranges='.$cardnumber.'&promoting_country=234&redemption_country=234'; //Gets using cardnumber

	$username = "2PVW2YRPFEG3CCMAH8NQ217DEiZvaMB4dv6UJDt_tMWcYZUxw";
	$password = "YkVzQIrKFZkDkudA8keAA8";

	# THIS IS EXAMPLE ONLY how will cert and key look like
	# cert = 'cert.pem'
	# key = 'key_83d11ea6-a22d-4e52-b310-e0558816727d.pem'

	$cert = 'htdocs\creditdeals\cert.pem';
	$key = 'htdocs\creditdeals\key_0b9a7d60-d5e1-4a12-80d0-14f2dede7c13.pem';

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);

	curl_setopt($curl, CURLOPT_PORT, 443);
	curl_setopt($curl, CURLOPT_VERBOSE, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl, CURLOPT_SSLVERSION, 1);
	curl_setopt($curl, CURLOPT_SSLCERT, $cert);
	curl_setopt($curl, CURLOPT_SSLKEY, $key);

	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl);
	$response_info = curl_getinfo($curl);
	$json = json_decode($response, true);
	$offers = $json["Offers"];

	if ($response_info['http_code'] === 0) {
		$curl_error_message = curl_error($curl);

		// curl_exec can sometimes fail but still return a blank message from curl_error().
		if (!empty($curl_error_message)) {
			$error_message = "API call to $url failed: $curl_error_message";
		} else {
			$error_message = "API call to $url failed, but for an unknown reason. " .
				"This could happen if you are disconnected from the network.";
		}
		echo $error_message;
	} elseif ($response_info['http_code'] >= 200 && $response_info['http_code'] <= 299) {
		return $offers;
	} else {
		//echo "[HTTP Status: " .  . "]\n[" . $response . "]";
		echo "\nError connecting to the API ($url)";
	}
}