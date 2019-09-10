<?php
/**
 * Parameters you need to change
 *
 *
 */
$websiteTitle="Pingstkyrkan Eskilstuna podcast";
$otherPodcastsTitle="Andra Pingstkyrkan Eskilstuna podcast";
$descriptionForAllFiles="Predikan från Pingstkyrkan Eskilstuna";
$listen_InLocalLanguage="Lyssna";

/* 
 * End of parameters you need to change. 
 * 
 * There should be no need to change below this line 
 */

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
	$base_url = getFullHost();
	header('Content-Type: text/html; charset=utf-8');
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$websiteTitle?></title>
<meta name=”viewport” content=”width=device-width, initial-scale=1″>
<link rel="shortcut icon" type="image/x-icon" href="<?=$base_url?>/fav.ico" />
<style>
@viewport {
  width: device-width ;
  zoom: 1.0 ;
}
@-ms-viewport {
  width: device-width ;
}
.descriptionbox{
	border-style: solid;
	border-width:1px;
	height:80px;
	flex-grow:1;
	overflow:hidden; 
	overflow-y: scroll;
}
.imagebox{
	width:150px;
	height:150px;
}
.imagemp3{
	max-width:150px;
}
.titlespan{
	font-size:1.5em;
}
.audiocontrol{
	width:350px;
	display:none;
}
.listenbutton{
	font-size:1.5em;margin:10px;
}
@media screen and (orientation:portrait) {
	.titlespan{
		font-size:3em;
	}
	.descriptionbox{
		font-size:2em;
		border-style: solid;
		border-width:1px;
		height:120px;
		flex-grow:1;
		overflow:hidden; 
		overflow-y: scroll;
	}
	.imagebox{
		width:250px;
		height:250px;
	}
	.imagemp3{
		max-width:250px;
	}
	.audiocontrol{
		transform: scale(2.0);
		margin-left:180px;
		margin-top:40px;
		display:none;
	}	
	.listenbutton{
		font-size:3em;
		margin:10px;
	}
}
</style>
</head>
<body>	
<a href="<?=$base_url.'/rss.php'?>"><img src="feedicon.png"/></a>	
<span  class="titlespan"><?=$websiteTitle?></span>
	<?php
		$getid3_dir = "getid3/";
		require_once($getid3_dir . 'getid3.php');
		$getid3_engine = new getID3;
		$files_dir = getcwd().'/upload/';
		$file_path = $files_dir .'/'.$_GET["podcast"].'.mp3';
		$id3_info = $getid3_engine->analyze($file_path);
		getid3_lib::CopyTagsToComments($id3_info);
		$date = date('l F d, Y', strtotime(date(DateTime::RFC2822, filemtime($file_path))));                    
        $title = $id3_info["comments_html"]["title"][0];		
		$description =  $descriptionForAllFiles;
		$image = $base_url.'/upload/'.$_GET['podcast'].'.jpg';
		$enclosure = $base_url.'/upload/'.$_GET['podcast'].'.mp3';
		?>
		<div style="border-style: groove;margin:10px;display:block">
			<div style="display:flex">
				<div class="imagebox" >
					<?php if(strlen($image)>0){ ?>
						<img class="imagemp3" src="<?=$image?>"/>
					<?php } ?>		
				</div>
				<div style="flex-grow:1">
					<div class="descriptionbox">
						<p><strong><?=$title?></strong></p>
						<p><?=$description?></p>
					</div>	
					<div style="">
						<audio class="audiocontrol" style="display:inline" controls='controls'>
						  <source src='<?=$enclosure?>' type="audio/mpeg">
						Your browser does not support the audio element.
						</audio>	
					</div>
				</div>
			</div>		
		</div>
		<div style="border-style: groove;margin-top:150px;display:block">	
		<span  class="titlespan"><?=$otherPodcastsTitle?></span>
		</div>		
<?php
	$rss = new DOMDocument();
	$myfile=require('rsscore.php');
	$rss->loadXml($myfile );
	$feed = array();	
	foreach ($rss->getElementsByTagName('item') as $node) {
		$imgObj = $node->getElementsByTagNameNS("http://www.itunes.com/dtds/podcast-1.0.dtd", "image")->item(0);
		$image=$imgObj?$imgObj->getAttribute('href'):'';
		$link=$node->getElementsByTagName('link')->item(0)->nodeValue;
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $link,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			'enclosure' => $node->getElementsByTagName('enclosure')->item(0)->getAttribute('url'),
			'image' => $image,
			);
		$feed[$link]=$item;
	}

	krsort($feed);
	$i=0;
	foreach($feed as $key=>$value){
		$i++;
		$title = str_replace(' & ', ' &amp; ', $value['title']);
		$link = $value['link'];
		$description = str_replace("\r\n","<br/>",$value['desc']);
		$description = str_replace("\n","<br/>",$description);
		$date = date('l F d, Y', strtotime($value['date']));
		$image = $value['image'];
		$enclosure = $value['enclosure'];?>
		<div style="border-style: groove;margin:10px;display:block">
			<div style="display:flex">
				<div class="imagebox" >
					<?php if(strlen($image)>0){ ?>
						<img class="imagemp3" src="<?=$image?>"/>
					<?php } ?>		
				</div>
				<div style="flex-grow:1">
					<div class="descriptionbox">
						<p><strong><?=$title?></strong></p>
						<p><?=$description?></p>
					</div>	
					<button class="listenbutton" type="button" 
					onclick="getElementById('abc<?=$i?>').setAttribute('src', '<?=$enclosure?>');getElementById('cba<?=$i?>').style.display = 'inline';getElementById('cba<?=$i?>').load();getElementById('cba<?=$i?>').play();this.style.display = 'none';">
					<?=$listen_InLocalLanguage?></button>
					<div style="display:block">
						<audio id="cba<?=$i?>" class="audiocontrol" controls='controls'>
						  <source id="abc<?=$i?>" src="" type="audio/mpeg">
						Your browser does not support the audio element.
						</audio>	
					</div>
				</div>
			</div>		
		</div>
<?php		
	}
?>
		
</body>	
</html>	