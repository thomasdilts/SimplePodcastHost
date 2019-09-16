<?php

/**
 * Any references to the mp3 files should go through here so that statistics can be gathered in files in the 
 * upload directory called filename.mp3.stats.
 *
 * This php file expects an argument "filename=xxxxx.mp3" 
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
	if(!array_key_exists ('filename',$_REQUEST) || !file_exists(getcwd().'/upload/'.$_REQUEST['filename'])){
		echo 'file does not exist. '. $base_url.'/upload/'.$_REQUEST['filename'];
		return;
	}
	$statsFile = getcwd().'/upload/'.$_REQUEST['filename'].'.stats';
	$stats = 0;
	if(file_exists($statsFile)){
		try {
			$stats=unserialize(file_get_contents($statsFile));
		} catch (\Throwable $e) { // For PHP 7
			$stats=0;
		} catch (\Exception $e) { // For PHP 5
			$stats=0;
		}
	}
	$stats++;
	file_put_contents($statsFile, serialize($stats));

	$base_url = getFullHost();
	
	header("Location: ". $base_url.'/upload/'.$_REQUEST['filename']); 
	exit();	
?>