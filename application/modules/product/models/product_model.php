<?php
##==================================================
## Model for Product
## @Author: Pinky Liwanagan
## @Date: 09-OCT-2013 
##==================================================

class Product_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->restApiUrl 	= $this->config->item('rest_api_url');
		$this->apiAuthKey 	= $this->config->item('api_auth_key');
		$this->locale 		= $this->session->userdata('locale') ? $this->session->userdata('locale') : $this->config->item('default_locale');
		$this->language		= $this->session->userdata('lang') ? $this->session->userdata('lang') : $this->config->item('default_lang');
	}
	
	// get product list
	function productList()
	{
		$url = $this->restApiUrl . 'products/'.$this->locale.'/'.$this->language.'/'.$this->apiAuthKey;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	function countryList()
	{
		$url = $this->restApiUrl . 'country/'.$this->locale.'/'.$this->apiAuthKey;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	function companyList()
	{
		$url = $this->restApiUrl . 'company/'.$this->locale.'/'.$this->language.'/'.$this->apiAuthKey;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	
	// get product information
	function productInfo($productId)
	{
		$url = $this->restApiUrl . 'products/'.$this->locale.'/'.$this->language.'/'.$this->apiAuthKey.'/' . $productId;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// add product
	function productAdd($data)
	{
		$url  = $this->restApiUrl . 'products/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res  = $this->call_rest($url,$data,'post');
		return json_decode($res);
	}
	
	// edit product
	function productEdit($data)
	{
		$url  = $this->restApiUrl . 'products/'.$this->locale.'/'.$this->apiAuthKey.'/' . $data['product_id'];
		$json = json_encode($data);
		$res  = $this->call_rest($url,$json,'put');
		return json_decode($res);
	}
	
	// get product type list
	function productTypeList()
	{
		$url = $this->restApiUrl . 'producttype/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// get product type information
	function productTypeInfo($productId)
	{
		$url = $this->restApiUrl . 'producttype/'.$this->locale.'/'.$this->apiAuthKey.'/' . $productId;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// add product type
	function productTypeAdd($data)
	{
		$url  = $this->restApiUrl . 'producttype/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res  = $this->call_rest($url,$data,'post');
		return json_decode($res);
	}
	
	// edit product type
	function productTypeEdit($data)
	{
		$url  = $this->restApiUrl . 'producttype/'.$this->locale.'/'.$this->apiAuthKey.'/' . $data['product_type_id'];
		$json = json_encode($data);
		$res  = $this->call_rest($url,$json,'put');
		return json_decode($res);
	}
	
	// get product option list
	function productOptionList()
	{
		$url = $this->restApiUrl . 'productoption/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// get product option information
	function productOptionInfo($productId)
	{
		$url = $this->restApiUrl . 'productoption/'.$this->locale.'/'.$this->apiAuthKey.'/' . $productId;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// add product type
	function productOptionAdd($data)
	{
		$url  = $this->restApiUrl . 'productoption/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res  = $this->call_rest($url,$json,'post');
		return json_decode($res);
	}
	
	// edit product option
	function productOptionEdit($data)
	{
		$url  = $this->restApiUrl . 'productoption/'.$this->locale.'/'.$this->apiAuthKey.'/' . $data['product_id'];
		$json = json_encode($data);
		$res  = $this->call_rest($url,$json,'put');
		return json_decode($res);
	}
	
	// get product option list
	function productAreasList()
	{
		$url = $this->restApiUrl . 'productarea/'.$this->locale.'/'.$this->language.'/'.$this->apiAuthKey.'/';
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// get product option information
	function productAreasInfo($productId)
	{
		$url = $this->restApiUrl . 'productarea/'.$this->locale.'/'.$this->language.'/'.$this->apiAuthKey.'/'.$productId;
		$res = $this->call_rest($url,'','get');
		return json_decode($res);
	}
	
	// add product type
	function productAreasAdd($data)
	{
		$url  = $this->restApiUrl . 'productarea/'.$this->locale.'/'.$this->apiAuthKey.'/';
		$res  = $this->call_rest($url,$data,'post');
		return json_decode($res);
	}
	
	// edit product option
	function productAreasEdit($data)
	{
		$url  = $this->restApiUrl . 'productarea/'.$this->locale.'/'.$this->apiAuthKey.'/' . $data['area_id'];
		$json = json_encode($data);
		$res  = $this->call_rest($url,$json,'put');
		return json_decode($res);
	}
	
	function productImg($file_element_name)
	{
		$config['upload_path'] 		= './assets/uploadimages/productImg';
		$config['allowed_types'] = 'jpg|png';
		$config['max_size']	= '100';
		$config['max_width'] = '50';
		$config['max_height'] = '50';
		
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload($file_element_name))
		{
			$response = array(
                'rc' => 0,
                'msgInfo' => $this->upload->display_errors('', '')
            );
		} else {
			$data = $this->upload->data();
			
			$response = array(
                'rc' => 1,
                'data' => $data
            );
		}
		
		return $response;
		
	}
	
	function call_rest($url,$data,$method)
	{
		$function = 'simple_'.$method;
		$result = $this->curl->$function($url , $data , array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST=> false));	
		return $result;
	}

}