<?php
require_once 'vendor/autoload.php';
use BlockSDK;

$blockSDK = new BlockSDK("Iy5ZL1qnTEKX2OCITNMlBI2USFrMJC8SoJdEd8X2");
$klayClient = $blockSDK->createKlaytn();
$blockChain = $klayClient->getBlockChain();

$addresses = $klayClient->getAddresses([
    "offset" => 0,
    "limit" => 10
]);

$address = $klayClient->createAddress([
    "name" => "test address"
]);

print_r( $address );
?>