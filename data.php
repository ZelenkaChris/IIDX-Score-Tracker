<?php
	include("dbconnect.php");
	$songID = 0;
	$diff = null;
	
	if (isset($_GET["id"]) && (!(empty($_GET["id"])))) {
		$songID = htmlentities($_GET["id"]);
		$title = mysqli_query($conn, "SELECT * FROM `songlist` WHERE ID = $songID")->fetch_assoc()["title"];
	}
	
	if (isset($_GET["diff"]) && (!(empty($_GET["diff"])))) {
		$diff = htmlentities($_GET["diff"]);
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>

	<meta charset="utf-8">
	<meta http-equiv="Content-Language" content="ja" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo "ChrisZ's $title Score";?></title>
    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	</head>
	<style>
		body {
			background-color: #fcfcfc;
		}
		.table {
			text-align: center;   
		}
		th {
			text-align: center;
		}
		p {
			text-align: center;
		}
		h2 {
			text-align: center;
		}
	</style>
	<body>
		<div class="container">
			<?php
				$date_arr = array();
				$score_arr = array();
				$grade_arr = array("img/F.png", "img/E.png", "img/D.png", "img/C.png", "img/B.png", "img/A.png", "img/AA.png", "img/AAA.png");
				$clear_arr = array("No Play", "Failed", "Assist", "Easy", "Normal", "Hard", "ExHard", "Full Combo");
				$i = 0;
				if($diff != null) {
					$results = mysqli_query($conn, "SELECT * FROM `songdata` WHERE ID = $songID and Diff = '$diff' ORDER BY `songdata`.`Date` ASC");
					$note_res = mysqli_query($conn, "SELECT * FROM `songdata` WHERE ID = $songID and Diff = '$diff' ORDER BY `songdata`.`Date` DESC");
				} else {
					$results = mysqli_query($conn, "SELECT * FROM `songdata` WHERE ID = $songID ORDER BY `songdata`.`Date` ASC");
					$note_res = mysqli_query($conn, "SELECT * FROM `songdata` WHERE ID = $songID and Diff = '$diff' ORDER BY `songdata`.`Date` DESC");
				}

				$num_rows = mysqli_num_rows($results);
				echo "<h2><b>$title</b></h2>\n";
				if($num_rows > 0){
					$row = $note_res->fetch_assoc();
					$level = $row['Level'];
					$notes = $row['Notes'];
					$diff = strtoupper($row['Diff']);
					echo "<p><b>Difficulty:</b> $diff</p>\n";
					echo "<p><b>Level:</b> $level</p>\n";
					echo "<p><b>Notes:</b> $notes</p>\n";
					echo "<div>";					
					echo "<table class=\"table table-hover table-condensed table-bordered\">\n";
					echo "<thead>\n<tr>\n<th>Score</th>\n<th>Ratio</th>\n<th>Grade</th>\n<th>Miss</th>\n<th>Clear</th>\n<th>Date</th></tr>\n</thead>\n<tbody>\n";
					while ($i < $num_rows) {
						$row = $results->fetch_assoc();
						$score =  $row['Score'];
						$miss = $row['Miss'];
						$clear = $row['Clear'];
						$date = $row['Date'];
						$new_date = preg_split("/-/", $date);
						$new_date = $new_date[1] ."/". $new_date[2] ."/". substr($new_date[0], 2, 3);
						$date_arr[] = $date;
						$score_arr[] = $score;
						$ratio = round(100 * $score / ($notes*2), 2);
						$ratio_str = round(100 * $score / ($notes*2), 1);
						echo "<tr>\n";
						echo "<td>$score</td>\n";
						echo "<td>$ratio_str%</td>\n";
						$grade_index =  floor( $ratio / 11.11 ) - 1;
						$grade_index = ($grade_index < 0 ? 0 : $grade_index);
						echo "<td><img src=$grade_arr[$grade_index]></img></td>\n";
						echo "<td>$miss</td>\n";
						echo "<td>$clear_arr[$clear]</td>\n";
						echo "<td>$new_date</td>\n";
						echo "</tr>\n";
						$i = $i + 1;
					}
					echo "</tbody>\n</table>\n</div>\n";
					
				} else {
					echo "<h2>No Data</h2>";
					echo "<p>Select different difficulty</p>";
				}
			?>
			<div align="center" id="chart">
			</div>
		</div> 
		<script type="text/javascript">
			var score_arr = <?php echo json_encode($score_arr); ?>;
			var date_arr = <?php echo json_encode($date_arr); ?>;
			var date_arr2 = [];
			for (var i=0; i<date_arr.length; i++) {
				var a = date_arr[i].split('-');
				date_arr2.push(new Date(a[0], parseInt(a[1]) - 1, a[2]));
			}
			var table_array = [['Date', 'Score']];
			for (var i=0; i<score_arr.length; i++){
				table_array[i+1] = new Array(date_arr2[i], parseInt(score_arr[i]));
			}
			google.charts.load('current', {packages: ['corechart']});
			if(table_array.length > 1)
				google.charts.setOnLoadCallback(drawChart);		
			function drawChart() {
				var data = google.visualization.arrayToDataTable(table_array);
				var options = {
				  title: '<?php echo addslashes($title) ?>',
				  legend: { position: 'bottom' },
				  backgroundColor: '#fcfcfc',
				  colors: ['#FFD700']
				};			
				var chart =  new google.visualization.LineChart(document.getElementById('chart'));
				chart.draw(data, options);
				function resizeChart () {
					chart.draw(data, options);
				}
				if (document.addEventListener) {
					window.addEventListener('resize', resizeChart);
				}
				else if (document.attachEvent) {
					window.attachEvent('onresize', resizeChart);
				}
				else {
					window.resize = resizeChart;
				}
				}
		</script>	
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-75024850-1', 'auto');
		  ga('send', 'pageview');

		</script>

	</body>
</html>