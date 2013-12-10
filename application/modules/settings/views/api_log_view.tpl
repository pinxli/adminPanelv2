<div id="content" class="span10">
			<!-- start: Content -->
			<div>
				<hr>
				<ul class="breadcrumb">
					<li>
						<a href="{$baseUrl}dashboard/members_area">Home</a> <span class="divider">/</span>
					</li>
					
					<li>
						<a href="#">Settings</a> <span class="divider">/</span>
					</li>
					
					<li>
						<a href="#">API Logs</a>
					</li>
				</ul>
				<hr>
			</div>
			   
               
					<div class="box-content">
						
						<a class="quick-button span4" href="{$baseUrl}settings/excelexport/apilogList">
							<i class="fa fa-download"></i>
							<p>EXPORT TO EXCEL</p>
						</a>
					
						<div class="clearfix"></div>
					</div> 
					
			   <div class="row-fluid sortable">
				<div class="box span12">
                            
					<div class="box-header" data-original-title>
						<h2><i class="icon-list"></i><span class="break"></span>API Logs</h2>
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
								  <th>Client</th>
								  <th>Method</th>
								  <th>URL</th>
                                  <th width="15%">Access Time</th>
                                  <th width="20%" id="logquery">Query</th>
								  
							  </tr>
						  </thead>   
						  <tbody>
							{foreach from=$logList item=loglist}
							<tr>
								<td>{$loglist->clientID}</td>
								<td>{$loglist->log_method}</td>
								<td>{$loglist->log_url}</td>
								<td>{date("d M, Y H:i:s", strtotime($loglist->access_time))}</td>
								{if !empty($loglist->log_query)}
									<td class="center querytd" data-toggle="popover" data-placement="left" data-content="{$loglist->log_query}"  data-trigger="hover">
										{rtrim(substr($loglist->log_query,0,60))}...
									</td>
								{/if}
								{if empty($loglist->log_query)}
									<td>
										N/A
									</td>
								{/if}
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