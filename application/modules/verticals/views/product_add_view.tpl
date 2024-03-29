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
				<a href="#">Add Product</a>
			</li>
		</ul>
		<hr>
	</div>

	<!-- for error/success info message -->
	{if $msgInfo}
		<div class="{$msgClass}">
			<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
			<strong>{$msgInfo}</strong>
		</div>
	{/if} 

	<div class="row-fluid sortable">
		<div class="box span12">    
			<div class="box-header" data-original-title>
				<h2><i class="icon-edit"></i><span class="break"></span>New Product</h2>
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

					<div class="control-group"  id="category_type">
						<label class="control-label required-field" for="selectError3">Select Product Type</label>
						<div class="controls">{$productTypeList}</div>
					</div>

                    <div class="control-group">
						<label class="control-label required-field" for="focusedInput">Select Language:</label>
						<div class="controls">
							{$languageList}
						</div>
					</div>

					<div class="control-group required-field">
						<label class="control-label" for="focusedInput">Product Name:</label>
						<div class="controls">{$product_name}</div>
					</div>

					<div class="control-group input-prepend">
						<label class="control-label required-field" for="focusedInput">Product Link:</label>
						<div class="controls">
							<span class="add-on">www.</span>
							{$product_link}
						</div>
					</div>

					<div class="control-group" id="product_promo" style="display:none;">
						<label class="control-label">Promo</label>
						<div class="controls">
							<label class="radio">
								<input class="promo_options" type="radio" name="promo" id="optionsRadios1" value="1">
								Yes
							</label>
							<div style="clear:both"></div>
							<label class="radio">
								<input class="promo_options" type="radio" name="promo" id="optionsRadios2" value="0">
								No
							</label>
						</div>
						<div class="controls" id="promo_value"></div>
					</div>   


					<div class="control-group" id="uploadimage">
						<label class="control-label" for="fileInput">Upload Image</label>
						<div class="controls">
							<input name="productImg" class="input-file uniform_on" id="fileInput" type="file">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label required-field">Featured</label>
						<div class="controls">
							<input class="input-small focused autonum" type="number" name="featured" min="0" max="100">
							<!-- <label class="radio">
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

					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Create</button>
						<button type="reset" class="btn">Cancel</button>
					</div>
				</fieldset>
				{$form_close}
			</div>
		</div><!--/span-->
	</div><!--/row-->
<!-- end: Content -->
</div><!--/#content.span10-->

<script>
	function checkRadio()
	{
		var value = $('.promo_options').val();

		alert(value);
	}
</script>
