<?php
	include("dbconnect.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="ja" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ChrisZ's IIDX Scores</title>
  </head>
  <style>
    body {
      background-color: #fcfcfc;
    }
  </style>
  <body>
    <div class="container">
        <h1 style="margin-bottom: 10px;"align="center">ChrisZ's IIDX Scores</h1>
        <h5 style="padding: 0px; margin: 5px;" align="center">IIDX ID: 5168-2956</h5>
        <div>
          <img style="display: block;margin-left: auto;margin-right: auto;" src="img/rank.png"></img>
        </div>
        <div align="center" class="row">
        <div>
          <h2>Select a song</h2>
        </div>
    </div>
    <div class="row" align="center">
      <div>
        <select id="selector">
          <?php
            $i = 0;
            $results = mysqli_query($conn, "SELECT * FROM songlist ORDER BY `songlist`.`title` ASC");
            $num_rows = mysqli_num_rows($results);
            while ($i < $num_rows) {
              $row = $results->fetch_assoc();
              $title = $row['title'];
              $id = $row['ID'];
              echo "<option value=\"data.php?id=$id&diff=\">$title</option>\n";
              $i = $i + 1;
            }
          ?>
        </select>
        <select id="diff_selector">
          <option value="sph">SPH</option>
          <option value="spa" selected="selected">SPA</option>
        </select>
      </div>			
    </div>
    <iframe id="iframe" style="width: 100%; height: 750px; border: 0; overflow-x: hidden;"></iframe>		
  </div> 
  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script>
	// var select = document.createElement("select");
	var iframe = document.getElementById("iframe");
	// iframe.style.width = "100%";
	// iframe.height = "750";
	// iframe.style.border = "0";
	$(".container").append(iframe);
	iframe.src = $("#selector").val() + $("#diff_selector").val();
	$("#selector").change(function(e){
		iframe.src = $("#selector").val() + $("#diff_selector").val();
	})
	$("#diff_selector").change(function(e){
		iframe.src = $("#selector").val() + $("#diff_selector").val();
	})
	
	function detectmob() { 
		if( navigator.userAgent.match(/Android/i)
		|| navigator.userAgent.match(/webOS/i)
		|| navigator.userAgent.match(/iPhone/i)
		|| navigator.userAgent.match(/iPad/i)
		|| navigator.userAgent.match(/iPod/i)
		|| navigator.userAgent.match(/BlackBerry/i)
		|| navigator.userAgent.match(/Windows Phone/i)
		){
			return true;
		}
		else {
			return false;
		}
	}
	if(detectmob()){
		var selector = document.getElementById("selector");
		selector.style.width = '80%';
	}
	</script>
</html>
