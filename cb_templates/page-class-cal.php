<meta charset="UTF-8">
<link rel="stylesheet" href="<?=ASSETS_URL;?>bootstrap-calendar/css/calendar.css">
<div class="">
	<div class="">

		<div class="pull-right form-inline">
			<div class="btn-group">
				<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
				<button class="btn" data-calendar-nav="today">Today</button>
				<button class="btn btn-primary" data-calendar-nav="next">Next >></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-warning" data-calendar-view="year">Year</button>
				<button class="btn btn-warning active" data-calendar-view="month">Month</button>
				<button class="btn btn-warning" data-calendar-view="week">Week</button>
				<button class="btn btn-warning" data-calendar-view="day">Day</button>
			</div>
		</div>

		<h3></h3>
		
	</div>

	<div class="row">
		<div class="span9">
			<div id="calendar"></div>
		</div>
	</div>

	<div class="clearfix"></div>
	<script type="text/javascript" src="<?=ASSETS_URL;?>bootstrap-calendar/components/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="<?=ASSETS_URL;?>bootstrap-calendar/components/underscore/underscore-min.js"></script>
	<script type="text/javascript" src="<?=ASSETS_URL;?>bootstrap-calendar/components/bootstrap2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=ASSETS_URL;?>bootstrap-calendar/components/jstimezonedetect/jstz.min.js"></script>
	<script type="text/javascript" src="<?=ASSETS_URL;?>bootstrap-calendar/js/calendar.js"></script>
	<script type="text/javascript" src="<?=ASSETS_URL;?>bootstrap-calendar/js/app.js"></script>
</div>
