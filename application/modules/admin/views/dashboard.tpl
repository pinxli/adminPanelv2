<div id="content" class="span10">
<!-- start: Content -->
			
			<div>
				<hr>
				<ul class="breadcrumb">
					<li>
						<a href="#">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Dashboard</a>
					</li>
				</ul>
				<hr>
			</div>
			
			<div class="row-fluid">
				
				
				<div class="circleStats">
                    
					
					<div class="offset2" "span2" onTablet="span4" onDesktop="span2">
						<div class="circleStatsItem yellow">
                        	<i class="fa-icon-user"></i>
                        	<input type="text" value="{$leads}" class="yellowCircle" />
                    	</div>
						<div class="box-small-title">Leads</div>
					</div>
					
                    <div class="noMargin span2" onTablet="span4" onDesktop="span2">
						<div class="circleStatsItem pink">
                        	<i class="fa-icon-globe"></i>
                        	<input type="text" value="{$dailyVisits}" class="pinkCircle" />
                    	</div>
						<div class="box-small-title">Daily Visits</div>
					</div>
					
                    <div class="span2" onTablet="span4" onDesktop="span2">
                    	<div class="circleStatsItem green">
                        	<i class="fa-icon-bar-chart"></i>
                        	<input type="text" value="{$totalVisits}" class="greenCircle" />
                    	</div>
						<div class="box-small-title">Total Visits</div>
					</div>
					
                    <div class="span2" onTablet="span4" onDesktop="span2">
						<div class="circleStatsItem lightorange">
                        	<i class="fa-icon-shopping-cart"></i>
                        	<input type="text" value="{$products}" class="lightOrangeCircle" />
                    	</div>
						<div class="box-small-title">Products</div>
					</div>
                 </div>
               </div>
			
			<hr>
			
			<div class="row-fluid">
				
				<div class="box span8" onTablet="span12" onDesktop="span8">
					<div class="box-header">
						<h2><i class="icon-signal"></i><span class="break"></span>Site Statistics</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="icon-wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<div id="stats-chart"  class="center" style="height:300px" ></div>
						<input type="hidden" id="leadStat" value="{$leadStat}">
						<input type="hidden" id="visitStat" value="{$visitStat}">
				</div>
			</div>
			
			<div class="box span4 noMargin" onTablet="span12" onDesktop="span4">
				<div class="box-header">
					<h2><i class="icon-list"></i><span class="break"></span>Weekly Stat</h2>
					<div class="box-icon">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="icon-remove"></i></a>
					</div>
				</div>
				<div class="box-content">
					<div class="sparkLineStats">

                        <ul class="unstyled">
                            <li>
								<span class="sparkLineStats1 "></span> 
								Leads: 
								<span class="number">{$leadStatWeekTotal}</span>
							</li>
                            <li>
                                <span class="sparkLineStats2"></span>
                                Visits: 
                                <span class="number">{$visitStatWeekTotal}</span>
                            </li>

                        </ul>

                    </div><!-- End .sparkStats -->
	</div>
</div><!--/span-->
						
			</div><br /><br />
			<!-- end: Content -->
</div><!--/#content.span10-->
						