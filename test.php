<?php

require_once "./LiteDownloader.php";

try {

	$ld = new LiteDownloader("http://idem.cz", "POKUS");
	$ld->download();

	var_dump($ld->getContent());
	var_dump($ld->getContentType());
	var_dump($ld->getResponseCode());
}
catch (Exception $e) {

	echo "Error:\n";
	var_dump($e);
}
