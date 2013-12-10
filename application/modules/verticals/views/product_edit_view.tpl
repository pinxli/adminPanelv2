<div id="content" class="span10">
	<!-- start: Content -->
			<div>
				<hr>
				<ul class="breadcrumb">
					<li>
						<a href="{$baseUrl}dashboard/members_area">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Verticals</a> <span class="divider">/</span>
					</li>
                    <li>
						<a href="{$baseUrl}verticals/productlist">Product List</a> <span class="divider">/</span>
					</li>
                    <li>
						<a href="#">Edit Product</a>
					</li>
				</ul>
				<hr>
			</div>
        
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="icon-edit"></i><span class="break"></span>Edit Product</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="icon-wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						
						{$form_open}
							<fieldset>
							  
							  <div class="control-group">
                            	<label class="control-label required-field" for="selectError2">Select Country</label>
                                <div class="controls">{$countryList}</div>
                            </div>
                            
                            <div class="control-group">
                            	<label class="control-label required-field" for="selectError4">Select Company Name</label>
                                <div class="controls">{$companyList}</div>
                            </div>                       
                                  
                            <div class="control-group">
                            	<label class="control-label required-field" for="selectError1">Select Area</label>
                                <div class="controls">{$areaList}</div>
                            </div>
                            
                            <div class="control-group">
                            	<label class="control-label required-field" for="selectError3">Select Product Type</label>
                                <div class="controls">{$productTypeList}</div>
                            </div>

                            <div class="control-group">
								<label class="control-label required-field" for="focusedInput">Select Language:</label>
								<div class="controls">
									{$languageList}
								</div>
							</div>
							  
							<div class="control-group">
								<label class="control-label required-field" for="focusedInput">Product Name:</label>
								<div class="controls">{$product_name}</div></div>
			  
									  
						 	 <div class="control-group input-prepend">
								<label class="control-label required-field" for="focusedInput">Product Link:</label>
								<div class="controls">
								<span class="add-on">www.</span>
								{$product_link}</div>
							  </div>
							  
							<div class="control-group">
							  <label class="control-label" for="fileInput">Change Image</label>
							  <div class="controls">
								<input name="productImg" class="input-file uniform_on" id="fileInput" type="file">
							  </div>
							</div>

							  <div class="control-group">
								<label class="control-label required-field">Featured</label>
								<div class="controls">
								<input class="input-small focused autonum" type="number" name="featured" min="0" max="100" value="{$featured}">
								<!--    <label class="radio">
									<input type="radio" name="featured" id="optionsRadios1" value="1" checked="">
									Yes
								  </label>
								  <div style="clear:both"></div>
								  <label class="radio">
									<input type="radio" name="featured" id="optionsRadios2" value="0">
									No
								  </label>-->
								</div>
							  </div>
							  
							  
							   <div class="control-group">
								<label class="control-label">Status</label>
								<div class="controls">
								  <label class="radio">
									<input type="radio" name="status" id="optionsRadios1" value="1" checked="">
									Yes
								  </label>
								  <div style="clear:both"></div>
								  <label class="radio">
									<input type="radio" name="status" id="optionsRadios2" value="0">
									No
								  </label>
								</div>
							  </div>
							  
							<div class="control-group hidden-phone">
							  <label class="control-label required-field" for="textarea2">Description</label>
							  <div class="controls">{$product_description}</div>
							</div>
							
					<hr>Product Option<br />

					<div class="box-content">
						<table id="myTable"
							class="table table-striped table-bordered bootstrap-datatable">
							<thead>
								<tr>
									<th width="10%">Option Key</th>
									<th width="30%">Option Value</th>
									<th width="10%">Expiry Date</th>
									<th width="5%">Action</th>
								</tr>
							</thead>
							<tbody>
								{if $productOptions eq true}
								{foreach $productOptions item=options}
								<tr>
									<td>{$options->option}</td>
									<td><span id="optionvalue{$options->option_id}">{$options->option_value}</span></td>
									<td>{$options->expiry_date}</td>
									<td>
										<a class="btn btn-info">
											<i class="icon-edit icon-white optionedit" id="{$options->option_id}" alt="{$options->option}:{$options->option_value}" title="Edit"></i>  
										</a>
									</td>
								</tr>
								{/foreach}
								{/if}
							</tbody>
						</table>
					</div>

							   <div class="form-actions">
								<button type="submit" class="btn btn-primary">Edit</button>
								<button type="reset" class="btn btn-cancel" alt="{$baseUrl}verticals/productlist">Cancel</button>
							  </div>
							</fieldset>
							<input type="hidden" name="product_id" value="{$product_id}">
							<input type="hidden" name="editnow" value="editnow">
							{$form_close}
					
			
			</div>
				</div><!--/span-->

			</div><!--/row-->
    
				
			<!-- end: Content -->
			</div><!--/#content.span10-->