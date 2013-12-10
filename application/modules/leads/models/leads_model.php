<?php
##==================================================
## Model for Leads
## @Author: Pinky Liwanagan
## @Date: 07-NOV-2013 
##==================================================

class Leads_model extends CI_Model {

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
	
	//get list of leads
	function leadslist()
	{
		$data = array('timezone' => $this->timezone);
		$url  = $this->restApiUrl. 'leads/'.$this->locale.'/'.$this->apiAuthKey;
		$res  = $this->call_rest($url,$data,'get');
		return json_decode($res);
	}
	
	function leadsstatList($startDate=null,$endDate=null)
	{
		$url = $this->restApiUrl. 'leads/leadstats/'.$this->locale.'/'.$this->apiAuthKey;
		
		if ( !empty($startDate) && !empty($endDate) ){
			$url .= '/'.$startDate.'/'.$endDate;
		}
		
		$res = $this->call_rest($url,'','get');
		
		echo json_decode($res);
		return json_decode($res);
	}
	
	//get list of leads
	function leaduserlist()
	{
		$url = $this->restApiUrl. 'leaduser/'.$this->locale.'/'.$this->apiAuthKey;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}	
	
	//get list of leads
	function leadsearchlist()
	{
		$url = $this->restApiUrl. 'leadsearch/'.$this->locale.'/'.$this->apiAuthKey;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}	
	
	//add leads
	function leadsAdd($data='')
	{
		$data = array(
					'email' 			=> 'test@yahoo.com',
					'vertical_type_id'	=> 3,
					'bankName'			=> 'American Express'
		);
		
		$url = 'http://apidev.compargo.com/api/leads/hk/98740';
		
		#$url  = $this->restApiUrl. 'leads/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res  = $this->call_rest($url,$data,'post');
		return json_decode($res);
	}
	
	function call_rest($url,$data,$method)
	{
		$function = 'simple_'.$method;
		$result = $this->curl->$function($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));	
		return $result;
	}

}