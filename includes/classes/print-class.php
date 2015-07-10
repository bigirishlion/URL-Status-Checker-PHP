<?php

class printObj {

	public $resultsArray;
	public $status;
	public $redirect_num;
	public $url;

	public function  __construct($status, $redirect_num, $url) {
    	$this->status = $status;
    	$this->redirect_num = $redirect_num;
    	$this->url = $url;
  	}

	public function printToFile()
	{
		print_r($this->resultsArray);
		//return $this->resultsArray;
	}
} 