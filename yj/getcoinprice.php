<?php
	include_once	"../lib/db.class.php";


	$DB_LP = new DBCLASS;



	# Define function endpoint
	$ch = curl_init("https://api.bithumb.com/public/ticker/ALL");

	# Setup request to send json via POST. This is where all parameters should be entered.
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);

	# Decode the received JSON string
	$resultdecoded = json_decode($result, true);

	$btc = $resultdecoded['data']['BTC']['closing_price'];
	$btc_s = $resultdecoded['data']['BTC']['opening_price'];

	$eth = $resultdecoded['data']['ETH']['closing_price'];
	$eth_s = $resultdecoded['data']['ETH']['opening_price'];

	$xrp = $resultdecoded['data']['XRP']['closing_price'];
	$xrp_s = $resultdecoded['data']['XRP']['opening_price'];

	$msb = $resultdecoded['data']['MSB']['closing_price'];
	$msb_s = $resultdecoded['data']['MSB']['opening_price'];

	$klay = $resultdecoded['data']['KLAY']['closing_price'];
	$klay_s = $resultdecoded['data']['KLAY']['opening_price'];



	$mapQry = "UPDATE coinprice SET BTC = '$btc', BTC_S = '$btc_s', ETH = '$eth', ETH_S = '$eth_s', XRP = '$xrp', XRP_S = '$xrp_s',MSB = '$msb', MSB_S = '$msb_s',KLAY = '$klay', KLAY_S = '$klay_s';";
	$DB_LP->select($mapQry);
		
	
		echo $mapQry;


	$DB_LP->close();



?>
