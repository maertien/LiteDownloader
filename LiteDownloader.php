<?php

/**
 * Simple PHP downloader class
 * Author: Martin Kumst 
 * License: GNU GPL version 2
 */
class LiteDownloader {

	private $url;
	private $content;
	private $agent;
	private $contentType;
	private $responseCode;
	private $sizeLimit;
	private $downloadedBytes;

	/**
	 * Make new downloader
	 * @param string $url Url 
	 * @param string $agent HTTP-USER-AGENT identification
	 * @param mixed $sizeLimit Size limit of download
	 */
	public function __construct($url, $agent = "LiteDownloader/1.1", $sizeLimit = null) {

		$this->url = $url;
		$this->content = null;
		$this->agent = $agent;
		$this->contentType = null;
		$this->responseCode = null;
		$this->sizeLimit = $sizeLimit;

		$this->downloadedBytes = 0;
	}

	/**
	 * Returns the size of downloaded data
	 * @return int Downloaded bytes count
 	 */
	public function getDownloadedBytes() {

		return $this->downloadedBytes;
	}

	/**
	 * Perform download
	 * @throws Exception
	 */
	public function download() {

		if ($this->content !== null) {

			throw new Exception("Already downloaded", 2);
		}

		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // ;-) 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // ;-)
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_NOPROGRESS, false);
		curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, "checkSize"));

		$res = curl_exec($ch);

		if ($res === false) {
			
			$message = "Unable to download content";

			$curlErrorCode = curl_errno($ch);
			if ($curlErrorCode !== 0) {

				$message .= " - " . $curlErrorCode;
			}

			$curlErrorMessage = curl_error($ch);
			if ($curlErrorMessage !== "") {

				$message .= " - " . $curlErrorMessage;
			}
		
			throw new Exception($message, 3);
		}

		$this->contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$this->responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

		curl_close($ch);

		$this->content = $res;
	}

	/**
  	 * Returns downloaded content
	 * @return string Downloaded content
	 */
	public function getContent() {

		return $this->content;
	}

	/**
	 * Return content type of downloaded data
	 * @return string Content type
	 */
	public function getContentType() {

		return $this->contentType;
	}

	/**
	 * Return http response code
	 * @return int Reponse code
	 */
	public function getResponseCode() {

		return $this->responseCode;
	}

	/**
	 * Callback function for curl checking size of downloaded data
	 */
	private function checkSize($resource, $bytesExpected, $bytesDownloaded, $uploadExpected, $bytesUploaded) {

		$this->downloadedBytes = $bytesDownloaded;

		// Check if there is no limit set
		if ($this->sizeLimit === null) {

			return 0; // Download can continue
		}

		// Check size of downloaded data 
		if ($bytesDownloaded > $this->sizeLimit) {

			return -1; // Download stops now
		}

		return 0;
	}
}
