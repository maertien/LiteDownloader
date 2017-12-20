<?php

require_once "./LiteDownloader.php";

try {
	// Test download without size limit
	$ld = new LiteDownloader("http://idem.cz", "POKUS");

	// Test download with size limit
	// $ld = new LiteDownloader("https://cdimage.debian.org/debian-cd/current/amd64/iso-cd/debian-9.3.0-amd64-netinst.iso", "POKUS", 1024 * 1024 * 5);
	$ld->download();

//	var_dump($ld->getContent());
//	var_dump($ld->getContentType());
	var_dump($ld->getResponseCode());
	var_dump($ld->getDownloadedBytes());
}
catch (Exception $e) {

	echo "Error:\n";
	var_dump($e);
}
