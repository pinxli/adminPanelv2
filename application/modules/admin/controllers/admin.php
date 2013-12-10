<?php

class Admin extends CI_Controller {
	
	
	public function __construct() {
		parent::__construct();
		session_start();
		
		$this->load->model('admin_model');
		
		//template path
		$this->globalTpl = $this->config->item('global_tpl');
		$this->globalhdr = $this->config->item('global_hdr');
		
		//get flash data for error/info message
		$this->msgClass = ( $this->session->flashdata('msgClass') ) ? $this->session->flashdata('msgClass') : '';
		$this->msgInfo  = ( $this->session->flashdata('msgInfo') ) ? $this->session->flashdata('msgInfo') : '';
	}
	
	
	//loads the login form
	function index()
	{
		if ( $this->session->userdata('userid') ){
			redirect('dashboard/members_area');
			exit;
		}
	
		$this->load->library('form_validation');  
		
		$this->form_validation->set_rules('username', 'Username or Email', 'xss_clean|trim|required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'xss_clean|trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			//set forms open, close and inputs
			$form_open	= form_open_multipart('',array('class' => 'form-horizontal', 'method' => 'post'));
			$form_close	= form_close();
			$username	= form_input(array('name' => 'username', 'class' => 'input-large span10' , 'id' => 'focusedInput', 'placeholder' => 'Enter Email'));
			$password	= form_password(array('name' => 'password', 'class' => 'input-large span10' , 'id' => 'focusedInput', 'placeholder' => 'Enter Password'));
			
			$res = $this->admin_model->countryList();
			
			if ( validation_errors() ){
				$this->msgClass = 'alert alert-error';
				$this->msgInfo  = validation_errors();
			}
			
			$data['title']			= 'Login';
			$data['countryList'] 	= $res->data->countrylist;
			$data['baseUrl'] 		= base_url();
			$data['msgClass'] 		= $this->msgClass;
			$data['msgInfo'] 		= $this->msgInfo;
			$data['username'] 		= $username;
			$data['password'] 		= $password;
			$data['formOpen'] 		= $form_open;
			$data['formClose'] 		= $form_close;
			$data['logo']			= $this->config->item('default_locale');
			
			ini_set('display_errors',1);
			
			$this->parser->parse('login_form.tpl', $data);
		}
		else{
			$login_data = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'locale'   => strtolower($this->input->post('locale'))
			);	
			
			$login = $this->admin_model->validate($login_data);
				
			// echo "<pre />";
			// print_r($login);
			// exit;
			if ( $login->rc > 0 ) {
				$data = array(
					'userid' 		=> trim((string)$login->data->user->userid),
					'email'	 		=> trim((string)$login->data->user->email),
					'fname'	 		=> trim((string)$login->data->user->fname),
					'lname'			=> trim((string)$login->data->user->lname),
					'logid' 		=> trim((string)$login->data->user->log_id),
					'locale' 		=> trim((string)$login->data->user->locale),
					'user_level_id' => trim((string)$login->data->user->user_level_id),
					'is_logged_in'  => true
				);
				
				// $this->session->set_userdata($data);
				$_SESSION = $data;
				
				redirect('dashboard/members_area');
			} else {
				$msgClass = 'alert alert-error';
				$msgInfo  = ( $login->message[0] ) ? $login->message[0] : 'Invalid Username and/or Password.';	
				
				//set flash data for error/info message
				$msginfo_arr = array(
					'msgClass' => $msgClass,
					'msgInfo'  => $msgInfo,
				);
				$this->session->set_flashdata($msginfo_arr);
				
				// echo "<pre />";
				// print_r($msginfo_arr);
				redirect('admin');
			}	
		}

	}
	
	function circlestats()
	{
		$thisweek = date('W');
		$thisyear = date('Y');

		$dayTimes 	= $this->getDaysInWeek($thisweek, $thisyear);
		$startDate 	= date('Y-m-d', $dayTimes[0]);
		$endDate 	= date('Y-m-d', $dayTimes[6]);
		
		$this->load->library('gapi_functions');	
		
		$dailyVisits = $this->gapi_functions->getDailyVisits(date('Y-m-d'),date('Y-m-d'));
		$totalVisits = $this->gapi_functions->getTotalVisits();
		
		if ( strtotime($endDate) > strtotime(date('Y-m-d')) ){
			$endDate = date('Y-m-d');
		}
		else{
			$endDate = $endDate;
		}
		
		$visitStatWeekTotal = $this->gapi_functions->getDailyVisits($startDate,$endDate);
		
		//leads summary
		$res_leads = $this->admin_model->leadslist();
		$leads = count($res_leads->data->leadslist);
		
		//products summary
		$res_products = $this->admin_model->productList();
		$products = count($res_products->data->productlist);
		
		$data = '{"leads":'.$leads.',"products":'.$products.',"dailyVisits":'.$dailyVisits.',"totalVisits":'.$totalVisits.',"visitStatWeekTotal":'.$visitStatWeekTotal.'}';
		
		return $data;
	}
	
	
	// display dashboard upon validating credentials
	function dashboard()
	{
		$this->session->set_userdata($_SESSION);
		$gapi = $this->gApi();
	
		$dailyVisits 		= (string) $gapi->dailyVisits;
		$totalVisits 		= (string) $gapi->totalVisits;
		$visitStatWeekTotal = (string) $gapi->visitStatWeekTotal;
	
		$thisweek = date('W');
		$thisyear = date('Y');

		$dayTimes 	= $this->getDaysInWeek($thisweek, $thisyear);
		$startDate 	= date('Y-m-d', $dayTimes[0]);
		$endDate 	= date('Y-m-d', $dayTimes[6]);
		
		$leadsstatList  = $this->admin_model->leadsstatList($startDate,$endDate);
        
		$leadStatWeekTotal = 0;
		if ( $leadsstatList->rc > 0 ) {
			foreach ( $leadsstatList->data->leadstat as $row ) {
				$leadStatWeekTotal += $row->countlead;
			}
		}
				
		$visitStat = array();
		$leadStat  = array();
		// for ( $i=$weekStartDate; $i<$weekEndDate+1; $i++ ){
			// $date  = $today['year'].'-'.$today['mon'].'-'.$i;
			
			// $stats 		= $this->gapi_functions->getDailyVisits($date,$date);
			// $resLeads 	= $this->admin_model->leadsstatList($date,$date);
			
			// if ( $resLeads->rc > 0 ) {
				// $ldata = $resLeads->data->leadstat[0]->countlead;
			// }
			// else{
				// $ldata = 0;
			// }
			
			// array_push($visitStat, $stats);
			// array_push($leadStat, $ldata);
		// }
   
		//leads summary
		$res_leads = $this->admin_model->leadslist();
		$leads = count($res_leads->data->leadslist);
		
		//products summary
		$res_products = $this->admin_model->productList();
		$products = count($res_products->data->productlist);
		
		$this->hero_session->is_active_session();
		$data['mainContent'] = 'dashboard.tpl';
		
		$data['data'] = array(
			'baseUrl'		=> base_url(),
			'title'   		=> 'Dashboard',
			'msgClass'  	=> $this->msgClass,
			'msgInfo'   	=> $this->msgInfo,
			'leads'			=> $leads,
			'products'		=> $products,
			'totalVisits'	=> $totalVisits,
			'dailyVisits'	=> $dailyVisits,
			'leadStat'		=> json_encode($leadStat),
			'visitStat'		=> json_encode($visitStat),
			'leadStatWeekTotal'		=> $leadStatWeekTotal,
			'visitStatWeekTotal' 	=> $visitStatWeekTotal
		);
		
		$this->load->view($this->globalTpl, $data);		
	}
	
	// logout user from the system, record exit time in the API
	function logout()
	{
		$this->load->model('admin_model');
		$logout = $this->admin_model->logout();
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('is_logged_in');
		$this->session->sess_destroy();
		session_destroy();
		redirect('admin');
	}	

	function postProduct()
	{
		$url = 'http://localhost/MoneyMaxPH/api/products/98740/';
		
		$insert_data = array(
				'product_name'			 => 'Iphone',
				'product_description'	 => 'Iphone5',
				'product_type_id'		 => 1,
				'featured'	 			 => 0,
				'country_id' 			 => 1,
				'company_id' 			 => 1,
				'area_id' 				 => 1,
				'product_icon' 			 => 'ico',
				'product_link' 			 => 'asdadad',
			);
			
		$result = $this->curl->simple_post($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));

		echo "<pre />";
		print_r($result);
	}
	
	function gApi()
	{			
		$now = gmDate("Ymd");
		$logfile = 'assets/data/gapi'.$now.'.json';
		
		if(file_exists($logfile)){ 
			$fp = fopen($logfile, "rb");
			$line_of_text = fgets($fp);
			$json = json_decode($line_of_text);
			fclose($fp);
		}
		else{ 
			$data = $this->circlestats();
			$fp = fopen($logfile, 'w'); 
			$pr_rsp = print_r($data,true);
			fwrite($fp, $pr_rsp);
			fclose($fp);
			
			$json = json_decode($data);
		}
		
		return $json;
				
		
	}
	
	function getDaysInWeek ($weekNumber, $year, $dayStart = 1) {    
		// Count from '0104' because January 4th is always in week 1
		// (according to ISO 8601).
		$time = strtotime($year . '0104 +' . ($weekNumber - 1).' weeks');
		// Get the time of the first day of the week
		$dayTime = strtotime('-' . (date('w', $time) - $dayStart) . ' days', $time);
		// Get the times of days 0 -> 6
		$dayTimes = array ();
		for ($i = 0; $i < 7; ++$i) {
	   $dayTimes[] = strtotime('+' . $i . ' days', $dayTime);
		}
		// Return timestamps for mon-sun.
		return $dayTimes;
	}
}