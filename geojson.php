<?php

	function doit($address) {
		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, "http://maps.googleapis.com/maps/api/geocode/json?address=" . $address. "&sensor=false");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// grab URL and pass it to the browser
		$output  = curl_exec($ch);
		//echo $output;
	
		// close cURL resource, and free up system resources
		curl_close($ch);

		$feed = json_decode( $output, true);

		$retString = $feed['results'][0]['geometry']['location']['lat'] . ',' . $feed['results'][0]['geometry']['location']['lng'];
		echo $retString;
		
		


	}

	doit('crenshaw+and+55th+los%20angles,+CA');



?>