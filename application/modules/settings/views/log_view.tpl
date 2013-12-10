<div id="content" class="span10">
			<!-- start: Content -->
			

			<div>
				<hr>
				<ul class="breadcrumb">
					<li>
						<a href="#">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Access Logs</a>
					</li>
				</ul>
				<hr>
			</div>
			   
               
					<div class="box-content">
						
						<a class="quick-button span4" href="{$baseUrl}settings/excelexport/logList">
							<i class="fa fa-download"></i>
							<p>EXPORT TO EXCEL</p>
						</a>
					
						<div class="clearfix"></div>
					</div> 
					
			   <div class="row-fluid sortable">
				<div class="box span12">      
					<div class="box-header" data-original-title>
						<h2><i class="icon-list"></i><span class="break"></span>Access Logs</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="icon-wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th width="10%"><center>User ID</center></th>
								  <th width="26%"><center>Email</center></th>
                                  <th width="10%"><center>IP</center></th>
								  <th width="26%"><center>Access Time</center></th>
                                  <th width="26%"><center>Exit Time</center></th>
								  
							  </tr>
						  </thead>   
						  <tbody>
							{foreach from=$logList item=loglist}
							{math equation="strtotime($loglist->access_time) + 8"}
							<tr>
								<td><center>{$loglist->userid}</center></td>
								<td>{$loglist->email}</td>
								<td><center>{$loglist->access_ip}</center></td>
								<td><center>{date("d M, Y H:i:s", strtotime($loglist->access_time))}</center></td>
								<td><center>
									{if !empty($loglist->exit_time)}
									{date("d M, Y H:i:s", strtotime($loglist->exit_time))}
									{/if}</center>
								</td>
							</tr>
							{/foreach}
						  </tbody>
					  </table> 
                          
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
		<hr>
			<!-- end: Content -->
</div><!--/#content.span10-->