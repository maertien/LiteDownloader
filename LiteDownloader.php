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

	/**
	 * Make new downloader
	 * @param string $url Url 
	 * @param string $agent HTTP-USER-AGENT identification
	 */
	public function __construct($url, $agent = "LiteDownloader/1") {

		$this->url = $url;
		$this->content = null;
		$this->agent = $agent;
		$this->contentType = null;
		$this->responseCode = null;
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
		$res = curl_exec($ch);

		if ($res === false) {
		
			throw new Exception("Unable to download content", 3);
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
}
