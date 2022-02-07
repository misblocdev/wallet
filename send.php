<?php
include 'inc_head.php';
include_once	"./lib/db.class.php";


require_once 'vendor/autoload.php';
use BlockSDK;


$coin = $_POST['coin'];
$to_addr = $_POST['cryaddr'];
$amount = $_POST['amount'];

echo $coin;
echo $to_addr;
echo $amount;

$no = 0;

$DB_LP = new DBCLASS;

if ( !$jb_login )
	header('Location: ./login.html');

$email = $_SESSION[ 'username' ];

$qry = "select * from user_info where email = '$email';";
$DB_LP->select($qry);
$row = $DB_LP->get_data();
$no = $row->no;

$ret = 0;

if ( $no )
{
	
	$qry = "select * from klay_addr where no = '$no';";
	$DB_LP->select($qry);
	$row = $DB_LP->get_data();
	$private_key = $row->private_key;
	
	echo $pk;

	$address = $row->address;



	$blockSDK = new BlockSDK("Iy5ZL1qnTEKX2OCITNMlBI2USFrMJC8SoJdEd8X2");
	$klayClient = $blockSDK->createKlaytn();

	$addressBalance  = $klayClient->getAddressBalance([
			"address" => $address
		]);



	$KLAY = $addressBalance['payload']['balance'];

	$kip7 = $klayClient->getKIP7Balance([
		"contract_address" => "0xbe5a48277233ec9abfa99b082d198fa397057c81",
		"from" => $address
	]);



	$MSB = $kip7['payload']['balance'];

	$qry = "update user_info set KLAY = '$KLAY' and MSB = '$MSB' where no = '$no';";
	$DB_LP->select($qry);
	$row = $DB_LP->get_data();

	if ( $coin == 'KLAY' )
	{
		if ( $KLAY < $amount )
		{
			$ret = 2;
		}
		else
		{
			$tx = $klayClient->sendToAddress([
				"from" => $address,
				"to" => $to_addr,
				"amount" =>$amount,
				"private_key" => $private_key,
				"gas_limit" => 21000,
				"gwei" => 286
			]);

			echo "<br>".$address."<br>".$toaddr."<br>".$private_key."<br>".$amount;
				

			
			$timestamp = $tx['payload']['timestamp'];
			$hash = $tx['payload']['hash'];
			$status = $tx['payload']['status'];
		}
	

	}
	else if ( $coin == 'MSB' )
	{

		if ( $MSB < $amount )
		{
			$ret = 2;
		}
		else
		{

			$tx = $klayClient->getKIP7Transfer([
				"contract_address" => "0xbe5a48277233ec9abfa99b082d198fa397057c81",
				"from" => $address,
				"to" => $to_addr,
				"amount" =>$amount,
				"private_key" => $private_key,
				"gas_limit" => 60000,
				"gwei" => 449
			]);

			
			$timestamp = $tx['payload']['timestamp'];
			$hash = $tx['payload']['hash'];
			$status = $tx['payload']['status'];
		}

	}


	if ( $ret == 0 )
	{
			
		$rdate = date('Y-m-d H:i:s');

		$qry = "insert into tx_log values ('0', '$rdate', '$timestamp', '$email','$coin','$hash', '1', '$to_addr', '$amount', '1');";
		$DB_LP->select($qry);

		echo $qry;
	}




}




$qry = "select * from coinprice;";
$DB_LP->select($qry);

$row = $DB_LP->get_data();

$BTCP = $row->BTC;
$ETHP = $row->ETH;
$MSBP = $row->MSB;
$KLAYP = $row->KLAY;

$BTC_S = $row->BTC_S;
$ETH_S = $row->ETH_S;
$MSB_S = $row->MSB_S;
$KLAY_S = $row->KLAY_S;

$total_kw = $KLAYP * $KLAY + $MSB * $MSBP;


$DB_LP->close();


if ( $ret == 2 )
{
	header('Location: https://anapatalk.com/wallet/asset_send.html?ret=2');
}
else
{
	header('Location: https://anapatalk.com/wallet/asset_send.html?ret=1');
}


?>
