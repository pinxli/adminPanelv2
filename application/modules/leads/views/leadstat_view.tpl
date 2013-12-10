<div id="content" class="span10">
			<!-- start: Content -->
            	<hr>
            
				<ul class="breadcrumb">
					<li>
						<a href="{$baseUrl}dashboard/members_area">Home</a> <span class="divider">/</span>
					</li>
                    <li>
						<a href="#">Leads</a>
					</li>
				</ul>
            
				<hr>
		
				<!-- for error/success info message -->
		        {if $msgInfo}
		        	<br />
		        	<div class="{$msgClass}">
		            	<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
		                <strong>{$msgInfo}</strong>
		            </div>
		        {/if}  
                
                <div class="row-fluid sortable">	
				<div class="box span12">
					<div class="box-header">
						<h2><i class="icon-list"></i><span class="break"></span>Leads Stats</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="icon-wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form method="POST">
							<table class="table table-striped table-bordered">
								<tr>
									<td>
										Start Date: <input id="startDate" name="startDate" class="input-medium focused" type="text" value="">		
										End Date: <input id="endDate" name="endDate" class="input-medium focused" type="text" value="">						
										 <button class="btn btn-primary" name="searchbtn" id="searchbtn" type="submit" alt="{$baseUrl}lead/leadstat" value="searchbtn">Search</button>
									</td>
								</tr>
							</table>
						</form>	
						
						<div class="box-content">
						<ul class="dashboard-list">
							{assign var=total value=0}
							{assign var=temp value=0}
							{foreach from=$leadsstatList item=stat}
							{if $temp < $stat->countlead}
								{assign var=color value=green}
								{assign var=icon  value="fa-icon-arrow-up"}
							{elseif $temp == $stat->countlead}
									{assign var=color value=blue}
									{assign var=icon  value="fa-icon-exchange"}
							{else}	
									{assign var=color value=red}
									{assign var=icon  value="fa-icon-arrow-down"}	
							{/if}		
							<li>
								<a href="#">
									<i class="{$icon} {$color}"></i>                               
									<span class="{$color}">{$stat->countlead}</span>
									 {$stat->stamptime|date_format:'F d , Y'}                                  
								</a>
							</li>
							{assign var=total value=$total+$stat->countlead}
							{assign var=temp value=$stat->countlead}
						    {/foreach}
						  <!-- <li>
							<a href="#">
							  <i class="fa-icon-arrow-down red"></i>
							  <span class="red">15</span>
							  New Registrations
							</a>
						  </li> -->
	
						  <li>
							<a href="#">
							  <i class="fa-icon-phone black"></i>
							  <span class="black">{$total}</span>
							  Total Leads                                    
							</a>
						  </li>
						    
						</ul>
					</div>
				
							
						<!--  <table class="table table-striped table-bordered bootstrap-datatable" id="datable-prodlist">
							  <thead>
								  <tr>
                                  	  <th>Date</th>
									  <th>Count</th>                                     
								  </tr>
							  </thead>   
							  <tbody>
							  	{assign var=total value=0}
							  	{foreach from=$leadsstatList item=stat}
							  	<tr>
							  		<td>{$stat->stamptime}</td>
							  		<td>{$stat->countlead}</td>
							  		{assign var=total value=$total+$stat->countlead}
							  	</tr>
							  	{/foreach}
							  	<tr>
							  		<th>Total: </th>
							  		<th>{$total}</th>
							  	</tr>
							  </tbody>
						 </table>     --> 
					</div>
				</div><!--/span-->
			</div>

				<!--/row-->
    			
			<!-- end: Content -->
</div><!--/#content.span10-->