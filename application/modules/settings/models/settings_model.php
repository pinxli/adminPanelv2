<?php

class Settings_model extends CI_Model {

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
	
	// Get all users
	function userlist()
	{
		$url = $this->restApiUrl. 'users/'.$this->locale.'/'.$this->apiAuthKey;
		return json_decode($this->call_rest_get($url,''));
	}
	
	// Get users information
	function user_info($userid)
	{
		$url = $this->restApiUrl. 'users/'.$this->locale.'/'.$this->apiAuthKey.'/' . $userid;
		return json_decode($this->call_rest_get($url,''));
	}
	
	
	// Edit user into the Admin Panel
	function edit_user($data)
	{
		$url = $this->restApiUrl. 'users/'.$this->locale.'/'.$this->apiAuthKey.'/' . $data['userid'];
		$json = json_encode($data);
		return json_decode($this->call_rest_put($url,$json));
	}
	
	// Delete user into the Admin Panel
	function delete_user($userid)
	{
		$url = $this->restApiUrl. 'users/'.$this->locale.'/'.$this->apiAuthKey.'/' . $userid;
		return json_decode($this->call_rest_delete($url,''));
	}
	
	// Add user into the Admin Panel
	function add_user($data)
	{
		$url = $this->restApiUrl. 'users/'.$this->locale.'/'.$this->apiAuthKey;
		return json_decode($this->call_rest_post($url,$data));
	}
		
	// GET Request REST CALL
	function call_rest_get($url,$data='')
	{
		$result = $this->curl->simple_get($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));	
		return $result;
	}
	
	// POST Request REST CALL
	function call_rest_post($url,$data='')
	{
		$result = $this->curl->simple_post($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));	
		return $result;
	}
	
	// PUT Request REST CALL
	function call_rest_put($url,$data='')
	{
		$result = $this->curl->simple_put($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));	
		return $result;
	}
	
	
	// DELETE Request REST CALL
	function call_rest_delete($url,$data='')
	{
		$result = $this->curl->simple_delete($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));	
		return $result;
	}
	
	function logList()
	{
		$data = array('timezone' => $this->timezone);
		$url  = $this->restApiUrl. 'users/accesslogs/'.$this->locale.'/'.$this->apiAuthKey;
		return json_decode($this->call_rest_get($url,$data));
	}

	function explogList()
	{
		$data = array('timezone' => $this->timezone);
		$url = $this->restApiUrl. 'users/accesslogs/'.$this->locale.'/'.$this->apiAuthKey;
		return $this->call_rest_get($url,$data);
	}
	
	function expApiLog()
	{
		$data = array('timezone' => $this->timezone);
		$url = $this->restApiUrl. 'logs/'.$this->locale.'/'.$this->apiAuthKey;
		return $this->call_rest_get($url,$data);
	}
	
	
	function apilogList()
	{
		$data = array('timezone' => $this->timezone);
		$url = $this->restApiUrl. 'logs/'.$this->locale.'/'.$this->apiAuthKey;
		return json_decode($this->call_rest_get($url,$data));
	}

}