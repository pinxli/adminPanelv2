<?php 
##==================================================
## Controller for Verticals
## @Author: Raphael Torres
## @Date: 22-OCT-2013 
##==================================================

class Verticals extends CI_Controller {
	
	public $msgClass = '';
	public $msgInfo  = ''; 
	
	public function __construct() {
		parent::__construct();
		// session_start();
		
		// $this->session->set_userdata($_SESSION);
		
		//check user session
		$this->hero_session->is_active_session();
		
		//template path
		$this->globalTpl  = $this->config->item('global_tpl');
		$this->apiAuthKey = $this->config->item('api_auth_key');
		
		//get flash data for error/info message
		$this->msgClass = ( $this->session->flashdata('msgClass') ) ? $this->session->flashdata('msgClass') : '';
		$this->msgInfo  = ( $this->session->flashdata('msgInfo') ) ? $this->session->flashdata('msgInfo') : '';
		
		//get locale (ph,my,hk,etc)
		$this->locale 	= $this->session->userdata('locale');
	}
	
	// display product list
	function verticaltype()
	{
				
		$this->product_type_id = ( $this->uri->segment(3) ) ? $this->uri->segment(3) : '';
		$this->vertical_option = ( $this->uri->segment(4) ) ? $this->uri->segment(4) : '';
		$this->lang 		   = ( $this->uri->segment(5) ) ? $this->uri->segment(5) : 'en';
		
		$res = $this->verticals_model->productList($this->lang);
		
		//check if product list is pulled. if not, list is set to empty array
		$productList 	= ( $res->rc > 0 ) ? $res->data->productlist : array();
		$productTypeId  =  $this->uri->segment(3);
		$productType 	= $this->verticals_model->productTypeInfo($productTypeId);
		$productType	= ($productType->rc > 0) ? $productType->data->producttypeinfo[0]->product_type : '';
		
		$data['mainContent'] = 'verticallist.tpl';
		
		$data['data'] = array(
			'baseUrl'			=> base_url(),
			'default_icon'		=> $this->config->item('path_upload_img_product').$this->config->item('default_product_img'),
			'title'				=> 'Verticals',
			'msgClass'			=> $this->msgClass,
			'msgInfo'			=> $this->msgInfo,
			'productList'		=> $productList,
			'productType'		=> $productType,
			'product_type_id'	=> $this->product_type_id,
			'vertical_option'	=> $this->vertical_option,
			'lang'				=> $this->lang,
			'productTypeId' 	=> $productTypeId
		);
		
		$this->load->view($this->globalTpl, $data);	
	}
	
	// display product list
	function verticaltypes()
	{
		$res = $this->verticals_model->productTypeList();
		
		//check if product type list is pulled. if not, list is set to empty array
		$productTypeList = ( $res->rc > 0 ) ? $res->data->producttypelist : array();
		
		$data['mainContent'] = 'producttype_list_view.tpl';
		
		$data['data'] = array(
			'baseUrl'			=> base_url(),
			'title'				=> 'Vertical Type',
			'msgClass'			=> $this->msgClass,
			'msgInfo'			=> $this->msgInfo,
			'productTypeList'	=> $productTypeList
		);
		
		$this->load->view($this->globalTpl, $data);	
	}
	
	function productlist()
	{
		$this->lang = ( $this->uri->segment(3) ) ? $this->uri->segment(3) : 'en';
		$res = $this->verticals_model->productList($this->lang);

		//check if product list is pulled. if not, list is set to empty array
		$productList = ( $res->rc > 0 ) ? $res->data->productlist : array();
		
		$data['mainContent'] = 'productlist.tpl';
		
		$data['data'] = array(
			'baseUrl'				=> base_url(),
			'default_product_img'	=> $this->config->item('path_upload_img_product').$this->config->item('default_product_img'),
			'title'					=> 'Product List',
			'msgClass'				=> $this->msgClass,
			'msgInfo'				=> $this->msgInfo,
			'lang'					=> $this->lang,
			'productList'			=> $productList
		);
		
		$this->load->view($this->globalTpl, $data);	
	}
	
	function productsearch()
	{
		// if ( isset($_POST['search']) ){
			$data_arr = array(
				'keyword' => $_POST['keyword'],
				'keyword_value' => $_POST['keyword_value']
			);
			$res = $this->verticals_model->productSearch($data_arr);
			echo json_encode($res);
			exit;
		// }
	}
	
	// display product list
	function productarea()
	{
		$res = $this->verticals_model->productAreasList();
		
		//check if product type list is pulled. if not, list is set to empty array
		$productAreaList = ( $res->rc > 0 ) ? $res->data->productarealist : array();
		
		$data['mainContent'] = 'productarea_list_view.tpl';
		
		$data['data'] = array(
			'baseUrl'			=> base_url(),
			'title'				=> 'Product Area',
			'msgClass'			=> $this->msgClass,
			'msgInfo'			=> $this->msgInfo,
			'productAreaList'	=> $productAreaList
		);
		
		$this->load->view($this->globalTpl, $data);	
	}
	
	// add product edited 10/24/2013
	function addproduct()
	{ 
		$this->load->library('form_validation');    

		// field name, error message, validation rules
		$this->form_validation->set_rules('product_type_id', 'Product Type', 'xss_clean|trim|required');
		$this->form_validation->set_rules('company_id', 'Company', 'xss_clean|trim|required|required');
		$this->form_validation->set_rules('product_name', 'Product Name', 'xss_clean|trim|required');
		$this->form_validation->set_rules('product_description', 'Product Description', 'xss_clean|trim|required');
		$this->form_validation->set_rules('featured', 'Featured', 'xss_clean|trim|required');
		$this->form_validation->set_rules('country_id', 'Country', 'xss_clean|trim|required');
		$this->form_validation->set_rules('area_id', 'Area', 'xss_clean|trim|required');
        $this->form_validation->set_rules('language', 'Language', 'xss_clean|trim|required');
		$this->form_validation->set_rules('product_link', 'Product Link', 'xss_clean|trim|required');
		$this->form_validation->set_rules('status', 'Status', 'xss_clean|trim|required');
	    
		if($this->form_validation->run() == FALSE){
	   
			$clist    		= $this->verticals_model->companyList();
			$type_list   	= $this->verticals_model->productTypeList();
			$area_list    	= $this->verticals_model->productAreasList();
			$country_list  	= $this->verticals_model->countryList();
	   
			$comlist   		= ( $clist->rc > 0 ) ? $clist->data->companylist : array();
			$typeList   	= ( $type_list->rc > 0 ) ? $type_list->data->producttypelist : array();
			$areaList  		= ( $area_list->rc > 0 ) ? $area_list->data->productarealist : array();
			$countryList 	= ( $country_list->rc > 0 ) ? $country_list->data->countrylist : array();
	   
			$countrylist[''] = 'Select Country';
			$countrySelected = false;
			foreach ($countryList as $country) {
				if($countrySelected == false) {
					$countrySelected = ( $country->iso2 == strtoupper($this->session->userdata('locale'))) ? $country->country_id : false;
				}
		   
				$countrylist[$country->country_id] = $country->short_name;
			}

			$arealist[''] = 'Select Area';
			foreach ($areaList as $area) {
				if($area->area_country_id == $countrySelected) {
					$arealist[$area->area_id] = $area->area_name;
				}
			}
			
			$product_type_list[''] = 'Select Product Type';
			foreach ($typeList as $type) {
				$product_type_list[$type->product_type_id] = $type->product_type;
			}
	   
			$company_list[''] = 'Select Company';
			foreach ($comlist as $company) {
				$company_list[$company->company_id] = $company->company_name;
			}

			$form_open      		= form_open_multipart('',array('class' => 'form-horizontal', 'method' => 'post'));
			$product_name     		= form_input(array('title' => 'Product Name','alt' => 'product_name','name' => 'product_name','class' => 'input-xlarge focused productAdd', 'placeholder' => 'Product Name'));
			$product_description  	= form_textarea(array('name' => 'product_description', 'class' => 'input-xlarge productAdd', 'id'=> 'textarea2' , 'rows' =>'3'));
			$product_icon     		= form_input(array('name' => 'product_icon', 'class' => 'input-xlarge focused productAdd' , 'id' => 'focusedInput', 'placeholder' => 'Product Icon'));
			$product_link     		= form_input(array('name' => 'product_link', 'class' => 'input-xlarge focused productAdd' , 'id' => 'focusedInput', 'placeholder' => 'Product Link'));
			$areaList     			= form_dropdown('area_id', $arealist, '', 'id="selectError1" data-rel="chosen"');
			$countryList   			= form_dropdown('country_id', $countrylist, $countrySelected, 'id="selectError2" data-rel="chosen" alt="'.base_url().'verticals/getprodarea" ');
			$productTypeList  		= form_dropdown('product_type_id', $product_type_list, '' , 'id="selectError3" data-rel="chosen" alt="'.base_url().'verticals/getverticaloption/"');
			$companyList   			= form_dropdown('company_id', $company_list, '', 'id="selectError4" data-rel="chosen"');
            $languageList			= form_dropdown('language', array('en'=>'English','zh'=>'Chinese'), null , 'data-rel="chosen"');
			$form_close 			= form_close();

			$data['mainContent'] = 'product_add_view.tpl';

			if ( validation_errors() ) {
				$this->msgClass = 'alert alert-error';
				$this->msgInfo  = validation_errors();
			}
	   
			$data['data'] = array(
				'baseUrl'    			=> base_url(),
				'locale'    			=> $this->session->userdata('locale'),
				'title'     			=> 'Add Product',
				'msgClass'   			=> $this->msgClass,
				'msgInfo'    			=> $this->msgInfo,
				'product_name'    		=> $product_name,
				'product_description' 	=> $product_description,
				'product_icon'   		=> $product_icon,
				'product_link'   		=> $product_link,
				'areaList'    			=> $areaList,
				'countryList'   		=> $countryList,
				'productTypeList'  		=> $productTypeList,
				'companyList'   		=> $companyList,
                'languageList'			=> $languageList,
				'form_open'    			=> $form_open,
				'form_close'   			=> $form_close
			);

			$this->load->view('includes/template', $data);
		}
		else  
		{ 
			$path_upload_img_product = base_url().$this->config->item('path_upload_img_product').$this->locale.'/';
			$default_product_img 	 = $this->config->item('default_product_img');
			
			$imgUp = $this->verticals_model->productImg('productImg');
			$prod_icon = ( $imgUp['data']['file_name'] ) ? $path_upload_img_product.$imgUp['data']['file_name'] : $path_upload_img_product.$default_product_img;
			
			$imgUpErrMsg = '';
			if($imgUp['rc'] == 0) {
				$imgUpErrMsg  = $imgUp['msgInfo'].'<br />';
			}
			
			$insert_data = array(
				'product_type_id' 		=> $this->input->post('product_type_id'),
				'company_id' 			=> $this->input->post('company_id'),
				'product_name' 			=> $this->input->post('product_name'),
				'product_description' 	=> $this->input->post('product_description'),
				'featured' 				=> $this->input->post('featured'),
				'country_id' 			=> $this->input->post('country_id'),
				'area_id' 				=> $this->input->post('area_id'),
				'product_icon' 			=> $prod_icon,
				'product_link' 			=> $this->input->post('product_link'),
				'status' 				=> $this->input->post('status'),
				'language' 				=> $this->input->post('language'),
				'product_alias'			=> $this->input->post('product_alias')
			);
			 
			$result = $this->verticals_model->productAdd($insert_data);

			if($result->rc > 0) {
				$product_id 	= $result->productId;
				$option 		= $this->input->post('option');
				$expiry_date 	= $this->input->post('expiry_date');
				$date 			= date("Y-m-d H:i:s");

				foreach ($option as $key=>$value){
					$explode = explode("-", $key);
					$expiry  = ($explode[0] != 'Promo') ? $this->add_date($date,$expiry_date[$explode[1]]) : 0;

					$option_arr = array(
						'product_id'   		=> $product_id,
						'vertical_optionid' => $explode[1],
						'option'   			=> $explode[0],
						'option_value'  	=> $value,
						'expiry_date'  		=> $expiry

					);
					$option_insert = $this->verticals_model->productOptionAdd($option_arr);
				}
				
				$msgClass = 'alert alert-success';
				$msgInfo  = ( $result->message[0] ) ? $imgUpErrMsg.$result->message[0] : $imgUpErrMsg.'Product has been added.';
			}
			else
			{
				$msgClass = 'alert alert-error';
				$msgInfo  = ( $result->message[0] ) ? $imgUpErrMsg.$result->message[0] : $imgUpErrMsg.'Add product failed.';
			}

			//set flash data for error/info message
			$msginfo_arr = array(
				'msgClass' => $msgClass,
				'msgInfo'  => $msgInfo,
			);
			$this->session->set_flashdata($msginfo_arr);

			redirect('verticals/productlist/');
		}
	  
	}
	
	// add product type
	function addverticaltype()
	{	
		$this->load->library('form_validation');				
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('product_type', 'product_type', 'xss_clean|trim|required');
		$this->form_validation->set_rules('description', 'description', 'xss_clean|trim|required|required');
		$this->form_validation->set_rules('url_slug', 'url_slug', 'xss_clean|trim');
		// $this->form_validation->set_rules('option_key', 'option_key', 'xss_clean|trim|required|required');
		// $this->form_validation->set_rules('option_description', 'option_description', 'xss_clean|trim|required|required');
		// echo "validation = ".$this->form_validation->run();
		if($this->form_validation->run() === FALSE)
		{
			$product_type = form_input(array('name' => 'product_type','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Product Type'));
			$description  = form_input(array('name' => 'description','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Description'));
			$url_slug	  = form_input(array('name' => 'url_slug','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Url Slugs'));
			
			//for vertical options table
			$option_key	  = form_input(array('name' => 'option_key','class' => 'input-xlarge focused','id' => 'option_key','placeholder' => 'Option Key'));
			$option_description  = form_input(array('name' => 'option_description','class' => 'input-xlarge focused','id' => 'option_description','placeholder' => 'Option Description'));
				
			$form_open 	  = form_open('',array('class' => 'form-horizontal', 'method' => 'post'));
			$form_close   = form_close();
			
			$data['mainContent'] = 'producttype_add_view.tpl';

			$data['data'] = array(
			'baseUrl'				=> base_url(),
			'title'					=> 'Product',
			'msgClass'				=> $this->msgClass,
			'msgInfo'				=> $this->msgInfo,
			'product_type'			=> $product_type,
			'description'			=> $description,
			'url_slug'				=> $url_slug,
			'form_open'				=> $form_open,
			'form_close'			=> $form_close,
			
			'option_key'			=> $option_key,
			'option_description'	=> $option_description
			);
			
			$this->load->view('includes/template', $data);
		}
		else 	
		{
			$insertproductType = array(
				'product_type'		=> $this->input->post('product_type'),
				'description'		=> $this->input->post('description'),
				'url_slug'			=> $this->input->post('url_slug')
			);			
			
			$resProductType 	= $this->verticals_model->productTypeAdd($insertproductType);	
			
			if($resProductType->rc > 0)
			{
				$verticalOptions = json_decode('['.$this->input->post('verticaloptions').']');
				
				foreach ( $verticalOptions as $rw ){
				
					$insertVerticalOption = array(
						'product_type_id'		=> $resProductType->producttypeId,
						'option_key'    		=> $rw->option_key,
						'option_description'	=> $rw->option_description,
						'option_autoload'		=> $rw->option_autoload
					);
					
					$resVerticalOption  = $this->verticals_model->verticalOptionAdd($insertVerticalOption);	
				}
				
				$msgClass = 'alert alert-success';
				$msgInfo  = ( $resProductType->message[0] ) ? $resProductType->message[0] : 'Vertical Type has been added.';
			}
			else 
			{	
				$msgClass = 'alert alert-error';
				$msgInfo  = ( $resProductType->message[0] ) ? $resProductType->message[0] : 'Add vertical type failed.';			
			}
			
			//set flash data for error/info message
			$msginfo_arr = array(
				'msgClass' => $msgClass,
				'msgInfo'  => $msgInfo,
			);
			$this->session->set_flashdata($msginfo_arr);
			
			redirect('verticals/verticaltypes/');
		}
		
	}	
	
	// add product area
	function addproductarea()
	 { 
	  $this->load->library('form_validation');    
	  
	  // field name, error message, validation rules
	  $this->form_validation->set_rules('area_name', 'area_name', 'xss_clean|trim|required');
	  $this->form_validation->set_rules('area_description', 'area_description', 'xss_clean|trim|required|required');
	  $this->form_validation->set_rules('area_active', 'area_active', 'xss_clean|trim|required|required');
	  $this->form_validation->set_rules('area_country_id', 'area_country_id', 'xss_clean|trim|required');
	  
	  if($this->form_validation->run() == FALSE)
	  {
	   $country_list  = $this->verticals_model->countryList();
	   $countryList =   ( $country_list->rc > 0 ) ? $country_list->data->countrylist : array();

	   $countrylist[''] = 'Select Country';
	   $countrySelected = false;
	   foreach ($countryList as $country):
	   if($countrySelected == false)
	   {
		$countrySelected = ( $country->iso2 == strtoupper($this->session->userdata('locale'))) ? $country->country_id : false;
	   }
	   $countrylist[$country->country_id] = $country->short_name;
	   endforeach;
	   
	   $area_name      = form_input(array('name' => 'area_name','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Product Area'));
	   $area_description   = form_input(array('name' => 'area_description','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Description'));
	   $area_country_id = form_dropdown('area_country_id', $countrylist, $countrySelected, 'id="selectError2" data-rel="chosen"');
	   $form_open      = form_open('',array('class' => 'form-horizontal', 'method' => 'post'));
	   $form_close     = form_close();
	   
	   $data['mainContent'] = 'productarea_add_view.tpl';

	   $data['data'] = array(
	   'baseUrl'    => base_url(),
	   'locale'    => $this->session->userdata('locale'),
	   'title'     => 'Add Product Area',
	   'msgClass'    => $this->msgClass,
	   'msgInfo'    => $this->msgInfo,
	   'area_name'    => $area_name,
	   'area_description'  => $area_description,
	   'countryList'   => $area_country_id,
	   'form_open'    => $form_open,
	   'form_close'   => $form_close,
	   );
	  
	   $this->load->view($this->globalTpl, $data); 
	  }
	  else  
	  {
	   $insert_data = array(
		'area_country_id'   => $this->input->post('area_country_id'),
		'area_name'   => $this->input->post('area_name'),
		'area_description'  => $this->input->post('area_description'),
		'area_active'    => $this->input->post('area_active')
	   );
	   
	   
	   $result = $this->verticals_model->productAreasAdd($insert_data);
	   
	   if($result->rc > 0)
	   {
		$msgClass = 'alert alert-success';
		$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Product Area has been added.';
	   }
	   else 
	   { 
		$msgClass = 'alert alert-error';
		$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Add product area failed.';   
	   }
	   
	   //set flash data for error/info message
	   $msginfo_arr = array(
		'msgClass' => $msgClass,
		'msgInfo'  => $msgInfo,
	   );
	   $this->session->set_flashdata($msginfo_arr);
	   
	   redirect('verticals/productarea/');
	  }
	  
	 }	
	
	// edit product
	function editproduct()
	{		
		$this->language   = $this->uri->segment(3);
		$this->product_id = $this->uri->segment(4);	

		$product_info = $this->verticals_model->productInfo($this->language,$this->product_id);
		
		if($product_info->rc > 0)
		{
			if($this->input->post('editnow',TRUE) == 'editnow')
			{
				$path_upload_img_product = base_url().$this->config->item('path_upload_img_product').$this->locale.'/';
				$default_product_img 	 = $this->config->item('default_product_img');
				
				//$imgUp = $this->verticals_model->productImg('productImg');
				//$prod_icon = ( $imgUp['data']['file_name'] ) ? $path_upload_img_product.$imgUp['data']['file_name'] : '';
				
				$imgUp = $this->verticals_model->productImg('productImg');
				$prod_icon = ( $imgUp['data']['file_name'] ) ? $this->config->item('product_upload_img').$imgUp['data']['file_name'] : '';
				
				//Change Raphael
				$edit_data = array(
						'product_id'			=> $this->input->post('product_id'),
						'product_type_id' 		=> $this->input->post('product_type_id'),
						'company_id' 			=> $this->input->post('company_id'),
						'product_name' 			=> $this->input->post('product_name'),
						'product_description' 	=> $this->input->post('product_description'),
						'featured' 				=> $this->input->post('featured'),
						'country_id' 			=> $this->input->post('country_id'),
						'language' 				=> $this->input->post('language'),
						'area_id' 				=> $this->input->post('area_id'),
						'product_icon' 			=> $prod_icon,
						'product_link' 			=> $this->input->post('product_link'),
						'status' 				=> $this->input->post('status')
				);
				
				if($options = $this->input->post('options')) {
					foreach ( $options as $k => $v ) {
	                    $val = ( $v == '' ) ? ' ' : $v;

	                    $data = array(
	                        'option_id'     => $k,
	                        'option_value'  => $val
	                    );
	                    $resOp = $this->verticals_model->productOptionEdit($data);
	                }
				}
                
                $result = $this->verticals_model->productEdit($edit_data);
                
				if($result->rc > 0 )
				{
					if ( isset($resOp) ){
						if ( $resOp->rc > 0 ){
							$msgClass = 'alert alert-success';
							$msgInfo  = ( $resOp->message[0] ) ? $resOp->message[0] : 'Product Option has been modified.';
						}
						else{
							$msgClass = 'alert alert-success';
							$msgInfo  = ( $resOp->message[0] ) ? $resOp->message[0] : 'Edit Product Option failed.';
						}
					}
					else{
							$msgClass = 'alert alert-success';
							$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Product has been modified.';
					}
				}
				else
				{
					if ( isset($resOp) ){
						if ( $resOp->rc > 0 ){
							$msgClass = 'alert alert-success';
							$msgInfo  = ( $resOp->message[0] ) ? $resOp->message[0] : 'Product Option has been modified.';
						}
						else{
							$msgClass = 'alert alert-success';
							$msgInfo  = ( $resOp->message[0] ) ? $resOp->message[0] : 'Edit Product Option failed.';
						}
					}
					else{
						$msgClass = 'alert alert-error';
						$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Edit product failed.';
					}
				}
				
				//set flash data for error/info message
				$msginfo_arr = array(
					'msgClass' => $msgClass,
					'msgInfo'  => $msgInfo,
				);
				$this->session->set_flashdata($msginfo_arr);
                
                redirect($this->uri->uri_string);
				
			}
			else
			{
				$productOptions = $this->verticals_model->productOptionInfo($this->product_id);
				
				$productoption = ( $productOptions->rc > 0 ) ? $productOptions->data->optioninfo : array();
				
				$productInfo 	= $product_info->data->productinfo;

				//echo '<pre>'; print_r($productInfo);
				
				$clist			 = $this->verticals_model->companyList();
				$type_list		 = $this->verticals_model->productTypeList();
				$area_list 		 = $this->verticals_model->productAreasList();
				$country_list	 = $this->verticals_model->countryList();

				$comlist 		=	( $clist->rc > 0 ) ? $clist->data->companylist : array();
				$typeList 		= 	( $type_list->rc > 0 ) ? $type_list->data->producttypelist : array();
				$areaList		= 	( $area_list->rc > 0 ) ? $area_list->data->productarealist : array();
				$countryList	=   ( $country_list->rc > 0 ) ? $country_list->data->countrylist : array();
					
				$arealist[''] = 'Select Area';
				foreach ($areaList as $area) {
					$arealist[$area->area_id] = $area->area_name;
				}
					
				$countrylist[''] = 'Select Country';
				$countrySelected = false;
				
				foreach ($countryList as $country) {
					if($countrySelected == false)
					{
						$countrySelected = ( $country->iso2 == strtoupper($this->session->userdata('locale'))) ? $country->country_id : false;
					}
					
					$countrylist[$country->country_id] = $country->short_name;
				}
					
				$product_type_list[''] = 'Select Product Type';
				foreach ($typeList as $type) {
					$product_type_list[$type->product_type_id] = $type->product_type;
				}
					
					
				$company_list[''] = 'Select Company';
				foreach ($comlist as $company) {
					$company_list[$company->company_id] = $company->company_name;
				}
				
				$form_open 			 	= form_open_multipart('',array('class' => 'form-horizontal', 'method' => 'post', 'id' => 'myform'));
				$product_name 		 	= form_input(array('name' => 'product_name', 'class' => 'input-xlarge focused' , 'id' => 'focusedInput', 'placeholder' => 'Product Name', 'value' => $productInfo[0]->product_name));
				$product_description 	= form_textarea(array('name' => 'product_description', 'class' => 'input-xlarge', 'id'=> 'textarea2' , 'rows' =>'3' , 'value' => $productInfo[0]->product_description));
				$product_icon 		 	= form_input(array('name' => 'product_icon', 'class' => 'input-xlarge focused' , 'id' => 'focusedInput', 'placeholder' => 'Product Icon' , 'value' => $productInfo[0]->product_icon));
				$product_link 		 	= form_input(array('name' => 'product_link', 'class' => 'input-xlarge focused' , 'id' => 'focusedInput', 'placeholder' => 'Product Link' , 'value' => $productInfo[0]->product_link));
				$featured 		 		= $productInfo[0]->featured;
				$areaList			 	= form_dropdown('area_id', $arealist, $productInfo[0]->area_id , 'id="selectError1" data-rel="chosen"');
				$countryList			= form_dropdown('country_id', $countrylist, $productInfo[0]->country_id , 'id="selectError2" data-rel="chosen"');
				$productTypeList		= form_dropdown('product_type_id', $product_type_list, $productInfo[0]->product_type_id , 'id="selectError3" data-rel="chosen"');
				$companyList			= form_dropdown('company_id', $company_list, $productInfo[0]->company_id, 'id="selectError4" data-rel="chosen"');
				$product_alias 		 	= $productInfo[0]->product_alias;
				$languageList			= form_dropdown('language', array('en'=>'English','zh'=>'Chinese'), $productInfo[0]->language , 'data-rel="chosen"');
				$form_close = form_close();
								
				$data['mainContent'] = 'product_edit_view.tpl';

				$data['data'] = array(
					'baseUrl'				=> base_url(),
					'title'					=> 'Product',
					'msgClass'				=> $this->msgClass,
					'msgInfo'				=> $this->msgInfo,
					'product_name'  		=> $product_name,
					'product_description'	=> $product_description,
					'product_icon'			=> $product_icon,
					'product_link'			=> $product_link,
					'areaList'				=> $areaList,
					'countryList'			=> $countryList,
					'productTypeList'		=> $productTypeList,
					'companyList'			=> $companyList,
					'form_open'				=> $form_open,
					'form_close'			=> $form_close,
					'productOptions'		=> $productoption,
					'product_id'			=> $productInfo[0]->product_id,
					'languageList'			=> $languageList,
					'featured'				=> $featured
					
				);
				
				$this->load->view('includes/template', $data);
			}
		}
		else 
		{
			$msgClass = 'alert alert-error';
			$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Cannot get product info';
			
			//set flash data for error/info message
			$msginfo_arr = array(
				'msgClass' => $msgClass,
				'msgInfo'  => $msgInfo,
			);
			$this->session->set_flashdata($msginfo_arr);
			redirect('verticals/productlist/');
		}
	}
	
	
	function deleteproduct()
	{		
		$productId 		= $this->uri->segment(3);
		
		$res = $this->verticals_model->productDelete($productId);
		
		if ( $res->rc > 0 ){
			$msgClass = 'alert alert-success';
			$msgInfo  = ( $res->message[0] ) ? $res->message[0] : 'Product deleted.';
		}
		else{
			$msgClass = 'alert alert-error';
			$msgInfo  = ( $res->message[0] ) ? $res->message[0] : 'Delete product failed.';
		}
			
		//set flash data for error/info message
		$msginfo_arr = array(
			'msgClass' => $msgClass,
			'msgInfo'  => $msgInfo,
		);
		$this->session->set_flashdata($msginfo_arr);
		
		redirect('verticals/productlist/');
		
	}
	
	
	function viewproduct()
	{		
		$this->language   = $this->uri->segment(3);
		$this->product_id = $this->uri->segment(4);

		$product_info = $this->verticals_model->productInfo($this->language,$this->product_id);
		
		if($product_info->rc > 0)
		{
			$data['mainContent'] = 'product_view.tpl';
			
			$productOptions = $this->verticals_model->productOptionInfo($this->product_id);

			$productoption = ( $productOptions->rc > 0 ) ? $productOptions->data->optioninfo : array();
			
			$data['data'] = array(
				'baseUrl'				=> base_url(),
				'title'					=> 'View Products',
				'msgClass'				=> $this->msgClass,
				'msgInfo'				=> $this->msgInfo,
				'productOptions'		=> $productoption,
				'productInfo'			=> $product_info->data->productinfo[0]
			);
		
			$this->load->view($this->globalTpl, $data);	
		}
		else 
		{
			$msgClass = 'alert alert-error';
			$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Cannot get product info';
			
			//set flash data for error/info message
			$msginfo_arr = array(
				'msgClass' => $msgClass,
				'msgInfo'  => $msgInfo,
			);
			$this->session->set_flashdata($msginfo_arr);
			
			redirect('product/productlist/');
		}
		
		
	}
	
	// edit product
	function editverticaltype()
	{		
		$product_info = $this->verticals_model->productTypeInfo($this->uri->segment(3));
	
		if($product_info->rc > 0)
		{
			if($this->input->post('editnow',TRUE) == 'editnow')
			{
				$edit_data = array(
					'product_type_id'	=> $this->input->post('product_type_id'),
					'product_type' 		=> $this->input->post('product_type'),
					'description'  		=> $this->input->post('description'),
					'url_slug'			=> $this->input->post('url_slug')
				);
					
				$result = $this->verticals_model->productTypeEdit($edit_data);
			
				if($result->rc > 0)
				{
				
					//for adding vertical options during edit vertical type
					$verticalOptions = json_decode('['.$this->input->post('verticaloptions').']');
					foreach ( $verticalOptions as $rw ){
						$insertVerticalOption = array(
							'product_type_id'		=> $this->input->post('product_type_id'),
							'option_key'    		=> $rw->option_key,
							'option_description'	=> $rw->option_description,
							'option_autoload'		=> $rw->option_autoload
						);
						$resVerticalOption  = $this->verticals_model->verticalOptionAdd($insertVerticalOption);	
					}
					
					$msgInfoeditVerticalOption = ($resVerticalOption->message[0]) ? 'Product Type '.strtoupper($this->input->post('product_type')).': '.$resVerticalOption->message[0] : '';
					
					$msgClass = 'alert alert-success';
					$msgInfo  = ( $result->message[0] ) ? $result->message[0] .'<br />'. $msgInfoeditVerticalOption : $msgInfoeditVerticalOption ;
				}
				else 
				{	
					$msgClass = 'alert alert-error';
					$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Edit product type failed.';			
				}
				
				//set flash data for error/info message
				$msginfo_arr = array(
					'msgClass' => $msgClass,
					'msgInfo'  => $msgInfo,
				);
				$this->session->set_flashdata($msginfo_arr);
				
				redirect('verticals/verticaltypes/');
				
			}
			else
			{
				$productType = $product_info->data->producttypeinfo[0];

				$product_type 		= form_input(array('name' => 'product_type','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Product Type' , 'value' => $productType->product_type));
				$description  		= form_input(array('name' => 'description','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Description', 'value' => $productType->description));
				$url_slug  			= form_input(array('name' => 'url_slug','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'URL Slug', 'value' => $productType->url_slug));
				$product_type_id	= $productType->product_type_id;
				
				//for vertical options table
				$option_key	  		= form_input(array('name' => 'option_key','class' => 'input-xlarge focused','id' => 'option_key','placeholder' => 'Option Key'));
				$option_description = form_input(array('name' => 'option_description','class' => 'input-xlarge focused','id' => 'option_description','placeholder' => 'Option Description'));
				
				$form_open 	  		= form_open('',array('class' => 'form-horizontal', 'method' => 'post'));
				$form_close   		= form_close();
			
				$vertical_options  = $this->verticals_model->verticaloptionInfo($product_type_id);
				
				$verticalOptions  = ( $vertical_options->rc > 0 ) ? $vertical_options->data->verticaloptioninfo : false;
				
				$data['mainContent'] = 'producttype_edit_view.tpl';

				$data['data'] = array(
					'baseUrl'				=> base_url(),
					'title'					=> 'Product',
					'msgClass'				=> $this->msgClass,
					'msgInfo'				=> $this->msgInfo,
					'product_type'			=> $product_type,
					'description'			=> $description,
					'url_slug'				=> $url_slug,
					'product_type_id'		=> $product_type_id,
					'verticalOptions'		=> $verticalOptions,
					'option_key'			=> $option_key,
					'option_description'	=> $option_description,
					'form_open'				=> $form_open,
					'form_close'			=> $form_close,
					);
		
					$this->load->view($this->globalTpl, $data);	
			}
		}
		else 
		{
			$msgClass = 'alert alert-error';
			$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Cannot get product type info';
			
			//set flash data for error/info message
			$msginfo_arr = array(
				'msgClass' => $msgClass,
				'msgInfo'  => $msgInfo,
			);
			$this->session->set_flashdata($msginfo_arr);
			
			redirect('product/producttype/');
		}
	}
	
	// edit product area
	function editproductarea()
	{		
		$product_info = $this->verticals_model->productAreasInfo($this->uri->segment(3));
		
		if($product_info->rc > 0)
		{
			if($this->input->post('editnow',TRUE) == 'editnow')
			{
				$edit_data = array(
					'area_id'			=> $this->input->post('area_id'),	
					'area_name' 		=> $this->input->post('area_name'),
					'area_description'  => $this->input->post('area_description'),
					'area_active'  		=> $this->input->post('area_active')
				);
					
				$result = $this->verticals_model->productAreasEdit($edit_data);
			
				if($result->rc > 0)
				{
					$msgClass = 'alert alert-success';
					$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Product Area has been modified.';
				}
				else 
				{	
					$msgClass = 'alert alert-error';
					$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Edit product area failed.';			
				}
				
				//set flash data for error/info message
				$msginfo_arr = array(
					'msgClass' => $msgClass,
					'msgInfo'  => $msgInfo,
				);
				$this->session->set_flashdata($msginfo_arr);
				redirect('verticals/productarea/');
				
			}
			else
			{

				$productAreasInfo   = $product_info->data->productareainfo[0];
				
				$area_id			= $productAreasInfo->area_id;
				$area_name 		  	= form_input(array('name' => 'area_name','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Product Area', 'value' => $productAreasInfo->area_name));
				$area_description  	= form_input(array('name' => 'area_description','class' => 'input-xlarge focused','id' => 'focusedInput','placeholder' => 'Description' , 'value' => $productAreasInfo->area_description));
				$form_open 	  		= form_open('',array('class' => 'form-horizontal', 'method' => 'post'));
				$form_close   		= form_close();
					
				$data['mainContent'] = 'productarea_edit_view.tpl';

				$data['data'] = array(
					'baseUrl'				=> base_url(),
					'title'					=> 'Edit Product Area',
					'messageInfo'			=> '',
					'area_id'				=> $area_id,
					'area_name'				=> $area_name,
					'area_description'		=> $area_description,
					'form_open'				=> $form_open,
					'form_close'			=> $form_close,
				);
		
				$this->load->view($this->globalTpl, $data);	
	
			}
		}
		else 
		{
			$msgClass = 'alert alert-error';
			$msgInfo  = ( $result->message[0] ) ? $result->message[0] : 'Cannot get product area info';
			
			//set flash data for error/info message
			$msginfo_arr = array(
				'msgClass' => $msgClass,
				'msgInfo'  => $msgInfo,
			);
			$this->session->set_flashdata($msginfo_arr);
			
			redirect('verticals/productarea/');
		}
	}
	
	//csv upload last edited 10/24/2014
	function csvupload()
	{
		$this->load->library('form_validation');				

		$file_element_name = 'productcsv';
			
		$config['upload_path'] 		= './assets/data';
		$config['allowed_types'] 	= 'csv|txt';
		$config['max_size'] 		= 1024 * 8;

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload($file_element_name))
		{
			$form_open 	= form_open_multipart('',array('class' => 'form-horizontal', 'method' => 'post'));
			$form_close = form_close();

			$data['mainContent'] = 'csv_upload_view.tpl';

			$data['data'] = array(
			'baseUrl'		=> base_url(),
			'locale'		=> $this->session->userdata('locale'),
			'title'			=> 'CSV Upload',
			'msgClass'		=> $this->msgClass,
			'msgInfo'		=> $this->msgInfo,
			'form_open'		=> $form_open,
			'form_close'	=> $form_close
			);
		
			$this->load->view('includes/template', $data);
		}
		else
		{

			$data = $this->upload->data();
			$file_handle = fopen($data["full_path"], "rb");
			$ctr = 0;
		
			$upload = false;

			while (!feof($file_handle) ) {
				$line_of_text = fgets($file_handle);
				$parts = explode(',', $line_of_text);

				if (count($parts) > 1 && $ctr > 0)
				{
					$check['producttype'] = $this->verticals_model->checkProductType($parts[5]);
					$check['country']	  = $this->verticals_model->checkCountry($parts[2]);
					$check['company']	  = $this->verticals_model->checkCompany($parts[3]);
					$countryId			  = ($check['country']->rc > 0) ? $check['country']->country_id : '';
					$producttypeId		  = ($check['producttype']->rc > 0) ? $check['producttype']->product_type_id : '';
					$check['area']		  = $this->verticals_model->checkArea($parts[4],$countryId);
					$check['options']	  = '';
					
					$chunk  = array_chunk($parts, 11);
					
					$option_ctr = 0;
					
					while (count($chunk[1]) > $option_ctr)
					{
						$check['options'] = $this->verticals_model->checkOptions($chunk[1][$option_ctr],$producttypeId);
						$option_ctr += 3;
					}
					
					$error = 0;
					$error_msg = '';
					
					//check for erros in CHECK array
					foreach ($check as $key => $value)
					{
						if ($check[$key]->rc > 0)
						{
							$error = 1;
							$error_msg .= $check[$key]->message . ',';


						}
					}
					
					if($error == 0) // Check for error.
					{
						$insert_array =	array(
											'product_type_id' 		=> $check['producttype']->product_type_id,
											'company_id'    		=> $check['company']->company_id,
											'product_name'	 		=> trim($parts[6]),
											'product_description'	=> $parts[10],
											'featured'				=> $parts[1],
											'country_id'			=> $countryId,
											'area_id'				=> $check['area']->area_id,
											'product_icon' 			=> base_url(). 'assets/uploadimages/productImg/' .$parts[8],

											'product_link' 			=> $parts[7],
											'status'				=> $parts[9]
						);
						
						$result = $this->verticals_model->productAdd($insert_array);
						
						if($result->rc > 0)
						{
							$optionctr 	  = 0;
							$temp		  = 0;
							$option_value = array('option','option_value','expiry_date');
							$option_arr   = array();

								
							while (count($chunk[1]) > $optionctr)
							{
								if(($temp + 1) % 3 == 0)
								{
									$date = date("Y-m-d H:i:s");
									$option_arr['product_id'] 			= $result->productId;
									$option_arr['vertical_optionid']	= $producttypeId;
									$option_arr[$option_value[$temp]]   = $this->add_date($date,$chunk[1][$optionctr]);
									$option_insert = $this->verticals_model->productOptionAdd($option_arr);
									$temp = 0;
									$option_arr = array();
										
								}
								else
								{
									$option_arr[$option_value[$temp]] = $chunk[1][$optionctr];
									$temp++;
								}
								
								$optionctr++;
							}
						}
					}
					else
					{

						$msginfo_arr = array(
								'msgClass' => 'alert alert-error',
								'msgInfo'  => 'Line ' . $ctr .' : ' . substr($error_msg, 0 , -1)
						);
						
						fclose($file_handle);
						unlink($data["full_path"]);
						
						$this->call_redirect('verticals/csvupload/', $msginfo_arr);
					}
					
				}
				
				$ctr++;
			}
			//endofwhile FILE
			fclose($file_handle);
			unlink($data["full_path"]);
			
			$msginfo_arr = array(
					'msgClass' => 'alert alert-success',
					'msgInfo'  => 'CSV upload success.'
			);
			
			$this->call_redirect('verticals/csvupload/', $msginfo_arr);
		}
	
	}
	
	function call_redirect($url,$msginfo)
	{
		$msginfo_arr = array(
				'msgClass' => $msginfo['msgClass'],
				'msgInfo'  => $msginfo['msgInfo'],
			);
		
		$this->session->set_flashdata($msginfo_arr);
			
		redirect($url);	

	}
	
	function add_date($givendate,$day=0,$mth=0,$yr=0) {
		$cd = strtotime($givendate);
		$newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
		date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
		date('d',$cd)+$day, date('Y',$cd)+$yr));
		return $newdate;
	}
	
	function getprodarea()
	{
		$country_id = $_POST['id'];
		$api_url 	= $this->config->item('rest_api_url');
		
		$res = file_get_contents($api_url.'productarea/countryArea/'.$this->locale.'/'.$this->apiAuthKey.'/'.$country_id);
		echo $res;
	}
	
	function getverticaloption()
	{
		$id  = $this->uri->segment(3);
		// echo $id;
		$url = $this->config->item('rest_api_url').'verticaloption/'.$this->locale.'/'.$this->apiAuthKey.'/'.$id;
		
		$res = file_get_contents($url);
		echo $res;
	}
	

	function csvChinese()
	{
		$this->load->helper('text');
			
		$file_handle = fopen('http://admindev.compargo.com/assets/colFormat-lastPIPE_final.csv', "rb");
		$ctr = 0;
		$option = '';
				
		while (!feof($file_handle) ) {
			$line_of_text = fgets($file_handle);
 			$parts = explode('|', $line_of_text);
 			 			
 			if($ctr == 0)
 			{
 				$option = array_slice($parts, 6, 32);
 			}
 			elseif (count($parts) > 1 && $ctr > 0)
 			{
 				
 				$insert_data = array(
				'product_type_id' 		=> 3,
				'company_id' 			=> 1,
				'product_name' 			=> $parts[0],
				'product_description' 	=> $parts[0],
				'featured' 				=> 24,
				'country_id' 			=> 99,
				'area_id' 				=> 2,
				'product_icon' 			=> 'http://dev.moneyhero.com.hk/assets/img/creditcards/' .$parts[2],
				'product_link' 			=> $parts[1],
				'status' 				=> 1
 				);
 				
 				$result = $this->verticals_model->productAdd($insert_data);
 				 				
 				$slice  = array_slice($parts, 6, 32);
 						
 				$slice_ctr = 0;
		
				foreach ($slice as $key=>$value)
				{
					$date = date("Y-m-d H:i:s");
					
					$option_arr = array(
									'product_id' 		=> $result->productId,
									'vertical_optionid' => 3,
									'option'			=> $option[$slice_ctr],
									'option_value'      => $value,
									'expiry_date'		=> $this->add_date($date,0));
						
					$option_insert = $this->verticals_model->productOptionAdd($option_arr);
															
					$slice_ctr++;
				}
								
 				
 			}
 			

 			$ctr++;
		}
		
		fclose($file_handle);
	}
	
	function moneyheroCSV()
	{
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
			
					
			//load database based on locale
			#$this->db	= $this->load->database('monyhero_hk_dev',TRUE);
			$query = $this->db->get('products');
			
			#echo "<pre />";
			#print_r($query->result());
			#exit();
			
			$file_handle = fopen('http://admindev.compargo.com/assets/DATA_HK_CC_V6.csv', "rb");
			$ctr = 0;
			$option = '';
			$data = array();

			while (!feof($file_handle) ) {
				$line_of_text = fgets($file_handle);
				$parts = explode('|', $line_of_text);

				if($ctr == 0)
				{
					$option = array_slice($parts, 6, 32);
				}
				elseif (count($parts) > 1 && $ctr > 0)
				{
					
					$link = $parts[1] != '' ? $parts[1] : 'No Link';
						
					$insert_data = array(
					 'product_type_id' 		=> 3,
					 'company_id' 			=> $parts[5],
					 'product_name' 		=> $parts[0],
					 'product_description' 	=> $parts[0],
					 'featured' 			=> $parts[4],
					 'country_id' 			=> 99,
					 'area_id' 				=> 2,
					 'product_icon' 		=> 'http://dev.moneyhero.com.hk/assets/img/creditcards/' .$parts[2],
					 'product_link' 		=> $link,
					 'status' 				=> 1
					 );
					 	
					$result = $this->verticals_model->productAdd($insert_data);
						
					$slice  = array_slice($parts, 6, 32);
						
					$slice_ctr = 0;

					$date = date("Y-m-d H:i:s");
						
					$option_arr = array(
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[0],
										 'option_value'     	=> $slice[0],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[1],
										 'option_value'    	    => $slice[1],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[3],
										 'option_value'    	  	=> $slice[3],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[4],
										 'option_value'      	=> $slice[4],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[5],
										 'option_value'      	=> $slice[5],
										 'expiry_date'			=> $this->add_date($date,0)),
											
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[6],
										 'option_value'      	=> $slice[6],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[7],
										 'option_value'      	=> $slice[7],
										 'expiry_date'			=> $this->add_date($date,0)),
																
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[8],
										 'option_value'      	=> $slice[8],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[9],
										 'option_value'      	=> $slice[9],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[10],
										 'option_value'      	=> $slice[10],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[11],
										 'option_value'      	=> $slice[11],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[12],
										 'option_value'      	=> $slice[12],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[13],
										 'option_value'      	=> $slice[13],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[14],
										 'option_value'      	=> $slice[14],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[15],
										 'option_value'      	=> $slice[15],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[16],
										 'option_value'      	=> $slice[16],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[17],
										 'option_value'      	=> $slice[17],
										 'expiry_date'			=> $this->add_date($date,0)),
																				
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[18],
										 'option_value'      	=> $slice[18],
										 'expiry_date'			=> $this->add_date($date,0)),
																														
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[19],
										 'option_value'      	=> $slice[19],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[20],
										 'option_value'      	=> $slice[20],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[21],
										 'option_value'      	=> $slice[21],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[22],
										 'option_value'      	=> $slice[22],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[23],
										 'option_value'      	=> $slice[23],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[24],
										 'option_value'      	=> $slice[24],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[25],
										 'option_value'      	=> $slice[25],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[26],
										 'option_value'      	=> $slice[26],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[27],
										 'option_value'      	=> $slice[27],
										 'expiry_date'			=> $this->add_date($date,0)),
										);
						
								
										$this->db->insert_batch('products_options',$option_arr);				
				}
				
				echo 'Row: ' . $ctr . ' Inserted <br> ';

				$ctr++;
			}
				
			fclose($file_handle);
			
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime = ($endtime - $starttime);
			echo "This page was created in ".$totaltime." seconds";
			
	}
	
	function chineseCSV()
	{
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
			
					
			//load database based on locale
			#$this->db	= $this->load->database('monyhero_hk_dev',TRUE);
			$query = $this->db->get('products');
			
			#echo "<pre />";
			#print_r($query->result());
			#exit();
			
			$file_handle = fopen('http://admindev.compargo.com/assets/DATA_HK_CC_V11.csv', "rb");
			$ctr = 0;
			$option = '';
			$product_id = '';
			$data = array();

			while (!feof($file_handle) ) {
				$line_of_text = fgets($file_handle);
				$parts = explode('|', $line_of_text);

				if($ctr == 0)
				{
					$option = array_slice($parts, 6, 32);
				}
				elseif (count($parts) > 1 && $ctr > 0)
				{
					
					$link = $parts[1] != '' ? $parts[1] : 'No Link';

					
					/*if ( $ctr & 1 ) {
						$language = 'en';
					} else {
						$language = 'zh';
					}*/
					
					
					$insert_data = array(
					 'product_type_id' 		=> 1,
					 'company_id' 			=> $parts[5],
					 'product_name' 		=> $parts[0],
					 'product_description' 	=> $parts[0],
					 'featured' 			=> $parts[4],
					 'country_id' 			=> 99,
					 'area_id' 				=> 1,
					 'product_icon' 		=> 'http://dev.moneyhero.com.hk/assets/img/creditcards/' .$parts[2],
					 'product_link' 		=> $link,
					 'status' 				=> 1,
					 'language'				=> 'zh',
					 'product_alias'		=> 0
					 );
					 			 				 
					 
					$result = $this->verticals_model->productAdd($insert_data);

					/*if ( $ctr & 1 ) {
						$product_id = $result->productId;
					} else {
						$product_id = '';
					}*/
										
					$slice  = array_slice($parts, 6, 32);
						
					$slice_ctr = 0;

					$date = date("Y-m-d H:i:s");
						
					$option_arr = array(
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[0],
										 'option_value'     	=> $slice[0],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[1],
										 'option_value'    	    => $slice[1],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[3],
										 'option_value'    	  	=> $slice[3],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[4],
										 'option_value'      	=> $slice[4],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[5],
										 'option_value'      	=> $slice[5],
										 'expiry_date'			=> $this->add_date($date,0)),
											
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[6],
										 'option_value'      	=> $slice[6],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[7],
										 'option_value'      	=> $slice[7],
										 'expiry_date'			=> $this->add_date($date,0)),
																
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[8],
										 'option_value'      	=> $slice[8],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[9],
										 'option_value'      	=> $slice[9],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[10],
										 'option_value'      	=> $slice[10],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[11],
										 'option_value'      	=> $slice[11],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[12],
										 'option_value'      	=> $slice[12],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[13],
										 'option_value'      	=> $slice[13],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[14],
										 'option_value'      	=> $slice[14],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[15],
										 'option_value'      	=> $slice[15],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[16],
										 'option_value'      	=> $slice[16],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[17],
										 'option_value'      	=> $slice[17],
										 'expiry_date'			=> $this->add_date($date,0)),
																				
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[18],
										 'option_value'      	=> $slice[18],
										 'expiry_date'			=> $this->add_date($date,0)),
																														
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[19],
										 'option_value'      	=> $slice[19],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[20],
										 'option_value'      	=> $slice[20],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[21],
										 'option_value'      	=> $slice[21],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[22],
										 'option_value'      	=> $slice[22],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[23],
										 'option_value'      	=> $slice[23],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[24],
										 'option_value'      	=> $slice[24],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[25],
										 'option_value'      	=> $slice[25],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[26],
										 'option_value'      	=> $slice[26],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[27],
										 'option_value'      	=> $slice[27],
										 'expiry_date'			=> $this->add_date($date,0)),
										);
						
								
										$this->db->insert_batch('products_options',$option_arr);				
				}
				
				echo 'Row: ' . $ctr . ' Inserted <br> ';

				$ctr++;
			}
				
			fclose($file_handle);
			
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime = ($endtime - $starttime);
			echo "This page was created in ".$totaltime." seconds";
			
	}
	
	function moneymaxCSV()
	{
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
					
			//load database based on locale
			$this->db	= $this->load->database('monyhero_ph_dev',TRUE);
			$query = $this->db->get('products');
			
			#echo "<pre />";
			#print_r($query);
			#exit();
			
			$file_handle = fopen('http://admindev.compargo.com/assets/DATA_PH_CC_with_pic.csv', "rb");
			$ctr = 0;
			$option = '';
			$data = array();

			while (!feof($file_handle) ) {
				$line_of_text = fgets($file_handle);
				$parts = explode('|', $line_of_text);

				if($ctr == 0)
				{
					$option = array_slice($parts, 6, 33);

				}
				elseif (count($parts) > 1 && $ctr > 0)
				{
					
					$link = $parts[1] != '' ? $parts[1] : 'No Link';
						
					$insert_data = array(
					 'product_type_id' 		=> 1,
					 'company_id' 			=> $parts[5],
					 'product_name' 		=> $parts[0],
					 'product_description' 	=> $parts[0],
					 'featured' 			=> $parts[4],
					 'country_id' 			=> 174,
					 'area_id' 				=> 1,
					 'product_icon' 		=> $parts[2],
					 'product_link' 		=> $link,
					 'status' 				=> 1
					 );

					 
					 
					$result = $this->verticals_model->productAdd($insert_data);

					$slice  = array_slice($parts, 6, 33);
						
					$slice_ctr = 0;

					$date = date("Y-m-d H:i:s");
						
					$option_arr = array(
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[0],
										 'option_value'     	=> $slice[0],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[1],
										 'option_value'    	    => $slice[1],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[3],
										 'option_value'    	  	=> $slice[3],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[4],
										 'option_value'      	=> $slice[4],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[5],
										 'option_value'      	=> $slice[5],
										 'expiry_date'			=> $this->add_date($date,0)),
											
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[6],
										 'option_value'      	=> $slice[6],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[7],
										 'option_value'      	=> $slice[7],
										 'expiry_date'			=> $this->add_date($date,0)),
																
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[8],
										 'option_value'      	=> $slice[8],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[9],
										 'option_value'      	=> $slice[9],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[10],
										 'option_value'      	=> $slice[10],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[11],
										 'option_value'      	=> $slice[11],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[12],
										 'option_value'      	=> $slice[12],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[13],
										 'option_value'      	=> $slice[13],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[14],
										 'option_value'      	=> $slice[14],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[15],
										 'option_value'      	=> $slice[15],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[16],
										 'option_value'      	=> $slice[16],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[17],
										 'option_value'      	=> $slice[17],
										 'expiry_date'			=> $this->add_date($date,0)),
																				
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[18],
										 'option_value'      	=> $slice[18],
										 'expiry_date'			=> $this->add_date($date,0)),
																														
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[19],
										 'option_value'      	=> $slice[19],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[20],
										 'option_value'      	=> $slice[20],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[21],
										 'option_value'      	=> $slice[21],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[22],
										 'option_value'      	=> $slice[22],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[23],
										 'option_value'      	=> $slice[23],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[24],
										 'option_value'      	=> $slice[24],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[25],
										 'option_value'      	=> $slice[25],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[26],
										 'option_value'      	=> $slice[26],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[27],
										 'option_value'      	=> $slice[27],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[28],
										 'option_value'      	=> $slice[28],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[29],
										 'option_value'      	=> $slice[29],
										 'expiry_date'			=> $this->add_date($date,0)),
																			
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[30],
										 'option_value'      	=> $slice[30],
										 'expiry_date'			=> $this->add_date($date,0)),
																												
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[31],
										 'option_value'      	=> $slice[31],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[32],
										 'option_value'      	=> $slice[32],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										);
										
										
						
								
										$this->db->insert_batch('products_options',$option_arr);				
				}
				
				echo 'Row: ' . $ctr . ' Inserted <br> ';

				$ctr++;
			}
				
			fclose($file_handle);
			
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime = ($endtime - $starttime);
			echo "This page was created in ".$totaltime." seconds";
	}
	
	function loansCSV()
	{
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
					
			//load database based on locale
			#$this->db	= $this->load->database('moneymax_ph',TRUE);
			$query = $this->db->get('products');
			
			#echo "<pre />";
			#print_r($query);
			#exit();
			
			$file_handle = fopen('http://admindev.compargo.com/assets/personal-loans-v2.csv', "rb");
			$ctr = 0;
			$option = '';
			$product_id = '';
			$data = array();

			while (!feof($file_handle) ) {
				$line_of_text = fgets($file_handle);
				$parts = explode('|', $line_of_text);

				if($ctr == 0)
				{
					$option = array_slice($parts, 6, 36);
	
				}
				elseif (count($parts) > 1 && $ctr > 0)
				{
					/*if ( $ctr & 1 ) {
						$language = 'en';
					} else {
						$language = 'zh';
					}*/
					
					$link = $parts[1] != '' ? $parts[1] : 'No Link';
						
					$insert_data = array(
					 'product_type_id' 		=> 4,
					 'company_id' 			=> $parts[5],
					 'product_name' 		=> $parts[0],
					 'product_description' 	=> $parts[0],
					 'featured' 			=> $parts[4],
					 'country_id' 			=> 99,
					 'area_id' 				=> 1,
					 'product_icon' 		=> $parts[2],
					 'product_link' 		=> $link,
					 'status' 				=> 1,
					 'language'				=> 'zh',
					 'product_alias'		=> 0
					 );

					 	 
					$result = $this->verticals_model->productAdd($insert_data);
					
					/*if ( $ctr & 1 ) {
						$product_id = $result->productId;
					} else {
						$product_id = '';
					}*/
					
					$slice  = array_slice($parts, 6, 36);
						
					$slice_ctr = 0;

					$date = date("Y-m-d H:i:s");
						
					$option_arr = array(
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[0],
										 'option_value'     	=> $slice[0],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[1],
										 'option_value'    	    => $slice[1],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[3],
										 'option_value'    	  	=> $slice[3],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[4],
										 'option_value'      	=> $slice[4],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[5],
										 'option_value'      	=> $slice[5],
										 'expiry_date'			=> $this->add_date($date,0)),
											
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[6],
										 'option_value'      	=> $slice[6],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[7],
										 'option_value'      	=> $slice[7],
										 'expiry_date'			=> $this->add_date($date,0)),
																
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[8],
										 'option_value'      	=> $slice[8],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[9],
										 'option_value'      	=> $slice[9],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[10],
										 'option_value'      	=> $slice[10],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[11],
										 'option_value'      	=> $slice[11],
										 'expiry_date'			=> $this->add_date($date,0)),
						
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[12],
										 'option_value'      	=> $slice[12],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[13],
										 'option_value'      	=> $slice[13],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[14],
										 'option_value'      	=> $slice[14],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[15],
										 'option_value'      	=> $slice[15],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[16],
										 'option_value'      	=> $slice[16],
										 'expiry_date'			=> $this->add_date($date,0)),

										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[17],
										 'option_value'      	=> $slice[17],
										 'expiry_date'			=> $this->add_date($date,0)),
																				
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[18],
										 'option_value'      	=> $slice[18],
										 'expiry_date'			=> $this->add_date($date,0)),
																														
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[19],
										 'option_value'      	=> $slice[19],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[20],
										 'option_value'      	=> $slice[20],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[21],
										 'option_value'      	=> $slice[21],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[22],
										 'option_value'      	=> $slice[22],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[23],
										 'option_value'      	=> $slice[23],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[24],
										 'option_value'      	=> $slice[24],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[25],
										 'option_value'      	=> $slice[25],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[26],
										 'option_value'      	=> $slice[26],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[27],
										 'option_value'      	=> $slice[27],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[28],
										 'option_value'      	=> $slice[28],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[29],
										 'option_value'      	=> $slice[29],
										 'expiry_date'			=> $this->add_date($date,0)),
																			
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[30],
										 'option_value'      	=> $slice[30],
										 'expiry_date'			=> $this->add_date($date,0)),
																												
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[31],
										 'option_value'      	=> $slice[31],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[32],
										 'option_value'      	=> $slice[32],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[33],
										 'option_value'      	=> $slice[33],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[34],
										 'option_value'      	=> $slice[34],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[35],
										 'option_value'      	=> $slice[35],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										array(
										 'product_id' 			=> $result->productId,
										 'vertical_optionid' 	=> 3,
										 'option'				=> $option[36],
										 'option_value'      	=> $slice[36],
										 'expiry_date'			=> $this->add_date($date,0)),
										
										);
										
										
						
								
										$this->db->insert_batch('products_options',$option_arr);				
				}
				
				echo 'Row: ' . $ctr . ' Inserted <br> ';

				$ctr++;
			}
				
			fclose($file_handle);
			
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime = ($endtime - $starttime);
			echo "This page was created in ".$totaltime." seconds";
	}
    
    public function parseData() {
        switch (ENVIRONMENT) {
            case 'development':
                $subdomain = 'http://dev.';
            break;
            
            case 'staging':
                $subdomain = 'http://staging.';
            break;
            
            default:
                $subdomain = 'http://www.';
            break;
        }
        
        $opts = array(
            'product_type_id' => 1,
            'country_id' => 99,
            'area_id' => 1,
            'domain' => $subdomain.'moneyhero.com.hk',
            'icon_prefix' => '/assets/img/creditcards/'
        );
        
        $inputFileName = 'HK_CC.xlsx';
        
        // Set country data based on filename
        if (strrpos(strtolower($inputFileName), 'hk')) {
            $opts['country_id'] = 99;
            $opts['area_id'] = 1;
            $opts['domain'] = $subdomain.'moneyhero.com.hk';
        } else if(strrpos(strtolower($inputFileName), 'ph')) {
            $opts['country_id'] = 174;
            $opts['area_id'] = 4;
            $opts['domain'] = $subdomain.'moneymax.ph';
        }
        
        // Set product options based on filename
        if (strrpos(strtolower($inputFileName), 'cc')) {
            $opts['product_type_id'] = 1;
        } else if(strrpos(strtolower($inputFileName), 'pl')) {
            $opts['product_type_id'] = 4;
        }
        
        // Identify file type
        include APPPATH.'third_party/PHPExcel/IOFactory.php';
        $inputFileType = PHPExcel_IOFactory::identify(dirname(APPPATH).'/assets/csv/'.$inputFileName);
        $inputFilePath = dirname(APPPATH).'/assets/csv/'.$inputFileName;
        
        // Read data file into memory
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFilePath);
        
        // Convert data file into array
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        
        // Get column names from first row
        $columns = $sheetData[1];
        $data = array();
        
        // Format data into associative array with named keys
        foreach ($sheetData as $index => $row) {
        	if($index > 1) {
        		foreach ($row as $key => $value) {
                    $data[$index][$columns[$key]] = $value;
                    $row = $data[$index][$columns[$key]];
        		}
        	}
        }
        
        /*
        // Get verticle option list to save product options
        $getVerticalOptionList = $this->verticals_model->verticaloptionList();
        
        // Set up array of id => value for lookup
        $verticalOptionList = array();
        foreach ($getVerticalOptionList->data->verticaloptionlist as $row) {
            $verticalOptionList[$row->id] = $row->option_key;
        }
        */
        
        // Loop over formatted data
        $i = 1;
        $log = array();
        $errors = array();
        $pos = array();
        foreach ($data as $row) {
            $company = $this->verticals_model->companyExist(array(
                'company_name' => $row['company_name'],
                'company_country_id' => $opts['country_id'],
                'language' => $row['language']
            ));
            
            $company_id = $company->companyId;
            
			$product = array(
                'product_type_id' 		=> $opts['product_type_id'],
                'company_id' 			=> $company_id,
                'product_name'          => $row['productName'],
                'product_description'   => $row['productName'],
                'featured'              => $row['ranking'],
                'country_id' 			=> $opts['country_id'],
                'area_id' 				=> $opts['area_id'],
                'product_icon'          => ($row['logo'] != '') ? $opts['domain'].$opts['icon_prefix'].$row['logo'] : '',
                'product_link'          => ($row['link'] != '0') ? $row['link'] : '',
                'status' 				=> 1,
                'language'				=> $row['language'],
                'product_alias'         => 0
			);
            
            // format individual fields
            if (isset($row['promoExpiry']) && !empty($row['promoExpiry'])) {
                $dt = explode('/', $row['promoExpiry']);
                if (count($dt) > 2) { // date is in the format day/month/year
                    $row['promoExpiry'] = mktime(
                        $hour=0,
                        $min=0,
                        $sec=0,
                        $month=$dt[1],
                        $day=$dt[0],
                        $year=$dt[2]
                    );
                } else {
                    $row['promoExpiry'] = 'FALSE';
                }
            } else {
                $row['promoExpiry'] = 'FALSE';
            }
            
            unset($row['company_name']);
            unset($row['productName']);
            unset($row['productId']);
            unset($row['link']);
            unset($row['ranking']);
            unset($row['logo']);
            unset($row['language']);
            unset($row['company_name']);
            
			$result = $this->verticals_model->productAdd($product);
            
            // Product successfully added
			if(isset($result) && ($result->rc > 0)) {
                $log[] = 'Saving product '.$product['product_name'];
                foreach ($row as $option => $value) {
                    // $optionId = array_search($option, $verticalOptionList);
                    
					$option_arr = array(
						'product_id'   		=> $result->productId,
						'vertical_optionid' => 3,
						'option'   			=> $option,
						'option_value'  	=> $value,
						'expiry_date'  		=> date('Y-m-d H:i:s', strtotime('+3 months'))
					);
                    
                    $pos[] = $option_arr;
                }
                
                $log[] = 'Saved product '.$result->productId;
            } else {
                $errors[] = "Count not save record ".$i;
            }
            
            $i++;
            
            if ($i % 20 === 0) {
                sleep(1);
                $log[] = 'Sleep for 1 second';
            }
        }
        
        if (isset($pos) && count($pos) > 0) {
            $j = 1;
            foreach ($pos as $po) {
                $log[] = 'Saving PO '.$po['option'].' for '.$po['product_id'];
                
                $poResult = $this->verticals_model->productOptionAdd($po);
                
                if (isset($poResult) && ($poResult->rc > 0)) {
                    $log[] = 'Saved PO '.$po['option'].' for '.$po['product_id'];
                }
                $j++;
                
                if ($j % 20 === 0) {
                    sleep(1);
                    $log[] = 'Sleep for 1 second';
                }
            }
        }
        
        echo "<pre>";
        print_r($log);
        echo "</pre>";
        
        echo "<pre>";
        print_r($errors);
        echo "</pre>";
        die;
    }
	
}