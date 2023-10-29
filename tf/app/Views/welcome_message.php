<html>
<head>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.js" type="03970ee99cd7f1e876d4a844-text/javascript"></script>
<script src="/cdn-cgi/apps/head/gqeYpb68FHXfcEAWOjow2Q2B208.js"></script><link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/font/fa/css/all.css" />
<title>Team 404</title>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="03970ee99cd7f1e876d4a844-text/javascript" src="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/js/metro.min.js"></script>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro.css">
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-4.css">
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-icons.min.css">
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-responsive.min.css">
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/metro/css/metro-schemes.min.css">
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/css/gz.css">
<link as="style" rel="stylesheet preload prefetch" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/font/icomoon/styles.css" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/font/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/modal/remodal.css">
<link rel="stylesheet" href="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/modal/remodal-default-theme.css">
<script src="https://gzcdn.sgp1.cdn.digitaloceanspaces.com/assets/modal/remodal.js" async defer type="03970ee99cd7f1e876d4a844-text/javascript"></script>
</head>
<style>
	* {
		font-family: ubuntu;
		font-display: swap;
	}
	h1 {
		font-family: ubuntu;
	}
	h2 {
		font-family: ubuntu;
	}
	h3 {
		font-family: ubuntu;
	}
	h4 {
		font-family: ubuntu;
	}
	h5 {
		font-family: ubuntu;
	}
	h6 {
		font-family: ubuntu;
	}
	.gradback{
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		height: 0.125rem;
		background-image: linear-gradient(90deg, rgb(255, 75, 75), rgb(255, 253, 128));
		z-index: 999990;
	}
</style>
<body style="background-color: #0d1117; color: white">
<br><br>
<div class="container">
	<div class="gradback"></div>
	<h2 style="margin-left: 0">Team 404</h2>
	<br><br>
	<h4 style="margin-left: 0">Repository Link</h4>
	<div class="input-control text full-size">
		<input type="text" name="repolink" id="repolink" style="background-color: #262730; border: 1px solid white; color: white">
	</div>
	<br><br>
	<button class="button" onclick="sendScrapeRequest()" id="scrape_btn">Scrape</button>
	<br><br><br><br>
	<hr style="background-color: grey">
	<br><br><br><br>
	<div class="align-center">
		<h4>Scraping Status</h4>
	</div>
	<div id="indicator" class="align-center"></div>
	<script>
		function sendScrapeRequest(){
			var repoLink = document.getElementById('repolink').value;

			const regex = /^(?:https?:\/\/)?(?:www\.)?github\.com\/([^\/]+)\/([^\/]+)\/?$/i;

			// Use the regular expression to extract owner and repo
			const match = repoLink.match(regex);

			if (match) {
				// Extracted owner and repo names
				const owner = match[1];
				const repo = match[2];

				console.log(owner, repo);

				$.ajax({
					type: 'GET',
					url: "<?php echo site_url('scrape'); ?>/" + owner + "/" + repo,
					beforeSend: function () {
						var btn = document.getElementById("scrape_btn");
						btn.innerHTML = "Scraping...";
						btn.disabled = true;
					},
					success: function (data) {
						console.log(data);
						var btn = document.getElementById("scrape_btn");

						btn.disabled = false;
						btn.innerHTML = "Scrape";
					}
				});
			} else {
				// Invalid GitHub URL
				return null;
			}
		}
		function getUpdateState()
		{
			$.ajax({
				type: 'GET',
				url: "<?php echo site_url('scrape/updatestate'); ?>",
				success: function (data) {
					console.log(data);

					if(data == "inactive"){
						$("#indicator").html("<br><h5 style='color: grey'>Inactive</h5>");
					} else if(data == "active"){
						$("#indicator").html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block; shape-rendering: auto;" width="100px" height="100px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path fill="none" stroke="#1d3f72" stroke-width="8" stroke-dasharray="42.76482137044271 42.76482137044271" d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z" stroke-linecap="round" style="transform:scale(0.8);transform-origin:50px 50px"><animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0;256.58892822265625"></animate></path></svg>`);
					} else if(data == "completed"){
						$("#indicator").html("<br><h5 style='color: green'>Completed</h5>");
					}
				}
			});
		}

		var id = setInterval('getUpdateState();', 500);
	</script>
</div>
</body>
</html>