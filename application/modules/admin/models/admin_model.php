<?php

class Admin_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		// session_start();
		
		// $this->session->set_userdata($_SESSION);
		
		$this->locale 		= $this->session->userdata('locale') ? $this->session->userdata('locale') : $this->config->item('default_locale');
		$this->restApiUrl 	= $this->config->item('rest_api_url');
		$this->apiAuthKey 	= $this->config->item('api_auth_key');
		
		//get timezone base on locale
		$timezone = $this->config->item('timezone');
		$this->timezone = $timezone[$this->locale]; 
	}

	
	// validate user credentials
	function validate($data)
	{
		$url = $this->restApiUrl. 'users/login/'.$data['locale'].'/'.$this->apiAuthKey.'/';
		// echo $url; exit;
		return json_decode($this->call_rest_post($url,$data));
	}
	
	// logout user
	function logout()
	{
		$userid = $this->session->userdata('userid');
		$logid  = $this->session->userdata('logid');
		$locale = $this->session->userdata('locale');
		$url = $this->restApiUrl. 'users/logout/'.$locale.'/'.$this->apiAuthKey.'/'  . $userid . '/' . $logid;
		echo $url;
		return json_decode($this->call_rest($url,''));
	}
	
	//countryList
	function countryList()
	{
		$url = $this->restApiUrl. 'country/'.$this->locale.'/'.$this->apiAuthKey.'/';
		return json_decode($this->call_rest($url,''));
	}
	
	//get leads status
	function leadsstatList($startDate=null,$endDate=null)
	{
		$url = $this->restApiUrl. 'leads/leadstats/'.$this->locale.'/'.$this->apiAuthKey;
		
		if ( !empty($startDate) && !empty($endDate) ) {
			$url .= '/'.$startDate.'/'.$endDate;
		}
		
		$res = $this->call_rest($url,'','get');
		
		return json_decode($res);
	}
	
	//get list of leads
	function leadslist()
	{
		$data = array('timezone' => $this->timezone);
		$url  = $this->restApiUrl. 'leads/'.$this->locale.'/'.$this->apiAuthKey;
		$res  = $this->call_rest($url,$data,'get');
		return json_decode($res);
	}
	
	// get product list
	function productList()
	{
		$url = $this->config->item('rest_api_url') . 'products/'.$this->locale.'/en/'.$this->apiAuthKey;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}	
	
	//call rest API
	function call_rest($url,$data='')
	{
		$result = $this->curl->simple_get($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));
		return $result;
	}
	
	function call_rest_post($url,$data='')
	{
		$result = $this->curl->simple_post($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));
		// echo "<pre />";
		// print_r($result);
		return $result;
	}
	
	
}