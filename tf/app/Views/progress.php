<html>
<head>
	<script src="/cdn-cgi/apps/head/gqeYpb68FHXfcEAWOjow2Q2B208.js"></script><link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/font/fa/css/all.css" />
	<title>Team 404</title>

	<script src="https://code.jquery.com/jquery-3.1.1.min.js">
	<script type="03970ee99cd7f1e876d4a844-text/javascript" src="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/js/metro.min.js"></script>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
	<link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro.css">
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-4.css">
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-icons.min.css">
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-responsive.min.css">
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-schemes.min.css">
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/css/gz.css">
	<link as="style" rel="stylesheet preload prefetch" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/font/icomoon/styles.css" crossorigin="anonymous" />
	<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/font/font-awesome/css/font-awesome.min.css" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.js" type="03970ee99cd7f1e876d4a844-text/javascript"></script>
	<link rel="stylesheet" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/modal/remodal.css">
	<link rel="stylesheet" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/modal/remodal-default-theme.css">
	<script src="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/modal/remodal.js" async defer type="03970ee99cd7f1e876d4a844-text/javascript"></script>
</head>
<body>
<br><br>
<div class="container">
	<h2>Team 404</h2>
	<br><br>
	<div class="align-center">
		<h4>Scraping Status</h4>
	</div>
	<div id="indicator" class="align-center"></div>
	<div data-role='preloader' data-type='cycle' data-style='color'></div>
	<script>
		function getUpdateState()
		{
			$.ajax({
				type: 'GET',
				url: "<?php echo site_url('scrape/updatestate'); ?>",
				success: function (data) {
					console.log(data);

					if(data == "inactive"){
						$("#indicator").html("<h5 style='color: grey'>Inactive</h5>");
					} else if(data == "active"){
						$("#indicator").html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block; shape-rendering: auto;" width="100px" height="100px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path fill="none" stroke="#1d3f72" stroke-width="8" stroke-dasharray="42.76482137044271 42.76482137044271" d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z" stroke-linecap="round" style="transform:scale(0.8);transform-origin:50px 50px"><animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0;256.58892822265625"></animate></path></svg>`);
					} else if(data == "completed"){
						$("#indicator").html("<h5 style='color: green'>Completed</h5>");
					}
				}
			});
		}

		var id = setInterval('getUpdateState();', 500);
	</script>
</div>
</body>
</html>