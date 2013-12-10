<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gapi_functions extends Gapi
{
	
	public function __construct() {
	}
	
	public function authenticate()
	{
		$CI =& get_instance();
		$gapiEmail 		= $CI->config->item('gapiEmail');
		$gapiPassword 	= $CI->config->item('gapiPassword');
		
		$gapi = new Gapi($gapiEmail,$gapiPassword);
		
		$gapi = new Gapi($gapiEmail,$gapiPassword);	
		
		return $gapi;
	}
	
	public function getReportData()
	{
		$CI =& get_instance();
		$gapiProfileId 	= $CI->config->item('gapiProfileId');
		
		$res = $this->authenticate();
		$res->requestReportData($gapiProfileId,array('browser','browserVersion'),array('pageviews','visits'));
		
		return $res->getVisits();
	}
	
	public function getTotalVisits()
	{
		$CI =& get_instance();
		$gapiProfileId 	= $CI->config->item('gapiProfileId');
		
		$res = $this->authenticate();
		$res->requestReportData($gapiProfileId,array('browser','browserVersion'),array('pageviews','visits'));
		
		return $res->getVisits();
	}
	
	public function getDailyVisits($startDate='', $endDate='')
	{
		$CI =& get_instance();
		$gapiProfileId 	= $CI->config->item('gapiProfileId');
		
		$res = $this->authenticate();
		$res->requestReportData($gapiProfileId,array('browser','browserVersion'),array('pageviews','visits'),'','',$startDate,$endDate);
		
		return $res->getVisits();
	}
	
	public function getWeeklyVisit()
	{
		$CI =& get_instance();
		$gapiProfileId 	= $CI->config->item('gapiProfileId');
		
		$res = $this->authenticate();
		$res->requestReportData($gapiProfileId,array('browser','browserVersion'),array('pageviews','visits'),'','',$startDate,$endDate);
		
		return $res->getVisits();
	}
}
?>