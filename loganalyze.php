<?php

/**
 * Analyzes the log files found in the 'logs' directory
 *
 */	
	$logDir=getcwd().'/logs';
	if(!file_exists($logDir)){
		mkdir($logDir);
	}
	
	$logs = scandir($logDir);
	$hitsPerDay=array();
	$hitsPerDayPerFile=array();
	$hitsPerFile=array();
	$hitsPerUser=array();
	$hitsPerReferer=array();	
	$allLogs=array();	
	foreach($logs as $log){
		if(endsWith($log,'.log')){
			$fileDate=substr($log,0,strlen($log)-4);
			$handle = fopen(getcwd().'/logs/'.$log, "r");
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					// process the line read.
					$line_split = explode(',',$line);
					if(count($line_split)==4){
						$timeStart=$line_split[0];
						$filename=$line_split[1];
						$ip=$line_split[2];
						$referer=$line_split[3];
						if(!array_key_exists($fileDate,$hitsPerDay)){
							$hitsPerDay[$fileDate]=0;
						}
						$hitsPerDay[$fileDate]++;
						
						if(!array_key_exists($filename,$hitsPerFile)){
							$hitsPerFile[$filename]=0;
						}
						$hitsPerFile[$filename]++;
						
						if(!array_key_exists($fileDate.','.$filename,$hitsPerDayPerFile)){
							$hitsPerDayPerFile[$fileDate.','.$filename]=0;
						}
						$hitsPerDayPerFile[$fileDate.','.$filename]++;
						
						if(!array_key_exists($ip,$hitsPerUser)){
							$hitsPerUser[$ip]=0;
						}
						$hitsPerUser[$ip]++;
						
						if(!array_key_exists($referer,$hitsPerReferer)){
							$hitsPerReferer[$referer]=0;
						}
						$hitsPerReferer[$referer]++;

						$allLogs[$fileDate.' '.$timeStart]=array($filename,$ip,$referer);
					}
				}

				fclose($handle);
			} else {
				// error opening the file.
			} 
		}			
	}
	arsort($hitsPerDay);
	arsort($hitsPerDayPerFile);
	arsort($hitsPerUser);
	arsort($hitsPerReferer);
	ksort($allLogs);
	$display= array(array('Hits per day','Day',$hitsPerDay),array('Hits per file','File',$hitsPerFile),array('Hits per day per file','Day-File',$hitsPerDayPerFile),array('Hits per user','User IP',$hitsPerUser),array('Hits per referer','Referer',$hitsPerReferer));
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name=”viewport” content=”width=device-width, initial-scale=1″>
<title><?=$websiteTitle?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?=getFullHost()?>/fav.ico" />
<style>

* {
	margin: 0px; 
	padding: 0px; 
	box-sizing: border-box;
}

body, html {
	height: 100%;
	font-family: sans-serif;
}

/* ------------------------------------ */
a {
	margin: 0px;
	transition: all 0.4s;
	-webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
}

a:focus {
	outline: none !important;
}

a:hover {
	text-decoration: none;
}

/* ------------------------------------ */
h1,h2,h3,h4,h5,h6 {margin: 0px;}

p {margin: 0px;}

ul, li {
	margin: 0px;
	list-style-type: none;
}


/* ------------------------------------ */
input {
  display: block;
	outline: none;
	border: none !important;
}

textarea {
  display: block;
  outline: none;
}

textarea:focus, input:focus {
  border-color: transparent !important;
}

/* ------------------------------------ */
button {
	outline: none !important;
	border: none;
	background: transparent;
}

button:hover {
	cursor: pointer;
}

iframe {
	border: none !important;
}



/*//////////////////////////////////////////////////////////////////
[ Table ]*/

.limiter {
  width: 100%;
  margin: 0 auto;
}

.container-table100 {
  width: 100%;
  min-height: 100vh;
  background: #d1d1d1;

  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  padding: 33px 30px;
}

.wrap-table100 {
  width: 700px;
}

/*//////////////////////////////////////////////////////////////////
[ Table ]*/
table {
  width: 100%;
  background-color: #fff;
}

th, td {
  font-weight: unset;
  padding-right: 10px;
}

.column100 {
  width: 130px;
  padding-left: 25px;
}

.column100.column1 {
  width: 265px;
  padding-left: 42px;
}

.row100.head th {
  padding-top: 24px;
  padding-bottom: 20px;
}

.row100 td {
  padding-top: 18px;
  padding-bottom: 14px;
}

.table100.ver3 tbody tr {
  border-bottom: 1px solid #e5e5e5;
}

.table100.ver3 td {
  font-family: Montserrat-Regular;
  font-size: 14px;
  color: #808080;
  line-height: 1.4;
}

.table100.ver3 th {
  font-family: Montserrat-Medium;
  font-size: 12px;
  color: #fff;
  line-height: 1.4;
  text-transform: uppercase;

  background-color: #6c7ae0;
}

.table100.ver3 .row100:hover td {
  background-color: #fcebf5;
}

.table100.ver3 .hov-column-ver3 {
  background-color: #fcebf5;
}

.table100.ver3 .hov-column-head-ver3 {
  background-color: #7b88e3 !important;
}

.table100.ver3 .row100 td:hover {
  background-color: #e03e9c;
  color: #fff;
}
</style>
<script>
</script>
</head>
<body>	
<div class="limiter">
<div class="container-table100">
<div class="wrap-table100">
<div class="table100 ver3 m-b-110">
	<?php foreach($display as $subject) { ?>
		<h1 style="margin-top:50px;color:#6c7ae0;"><?=$subject[0] ?></h1>
		<table class="table100.ver1">
		<tr class="row100 head">
			<th>
				<?=$subject[1] ?>
			</th>
			<th>
				Hits
			</th>
		</tr>
		<?php foreach($subject[2] as $key=>$value) { ?>
		<tr class="row100">
			<td class="column100 column1">
				<?=$key ?>
			</td>
			<td class="column100 column2">
				<?=$value ?>
			</td>
		</tr>
		<?php } ?>
		</table>
	<?php } ?>
	
		<h1 style="margin-top:50px;color:#6c7ae0;">Entire Log</h1>
		<table class="table100.ver1">
		<tr class="row100 head">
			<th>
				Date-time
			</th>
			<th>
				File
			</th>
			<th>
				IP address
			</th>
			<th>
				Refering URL
			</th>
		</tr>
		<?php foreach($allLogs as $key=>$value) { ?>
		<tr class="row100">
			<td class="column100 column1">
				<?=$key ?>
			</td>
			<td class="column100 column2">
				<?=$value[0] ?>
			</td>
			<td class="column100 column2">
				<?=$value[1] ?>
			</td>
			<td class="column100 column2">
				<?=$value[2] ?>
			</td>
		</tr>
		<?php } ?>
		</table>	
	
</div>	
</div>	
</div>	
</div>	
</body>	
</html>		

<?php
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
	
function getFullHost()
{
    $protocole = $_SERVER['REQUEST_SCHEME'].'://';
    $host = $_SERVER['HTTP_HOST'] . '/';
    $project = explode('/', $_SERVER['REQUEST_URI']);
    array_pop($project);
    array_shift($project);
    $path = implode('/', $project);
    return $protocole . $host . $path;
}
?>