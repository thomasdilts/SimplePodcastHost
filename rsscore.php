<?php
/**
 * This file is not to be called by a browser but only by other files.
 * The rss feed can be found by calling the rss.php file from a browser. 
 * This file only returns a string that other php files use.
 *
 * REQUIREMENTS: 
 * 1. You must have a sound file ending with '.mp3' and an image file ending with '.jpg' 
 * where the rest of the sound file name and image name are identical. For instance 'abc.mp3' and 'abc.jpg'.
 * These files must be in the subdirectory 'upload'.
 * This script will not work if you don't fullfill this requirement.
 *
 * 2. You must have a 'title' inside of your sound mp3 file. 
 * You can use the free program ffmpeg and the parameter -metadata title="Sound Track Title"  to add a title to 
 * your sound mp3 file. 
 */

/**
 * Parameters you need to change
 *
 *
 */
////////// channel information. 

$channel_title='Pingstkyrkan Eskilstuna';
// a link to your favorite website that promotes your podcast.
$channel_link='http://pingsteskilstuna.se';
$channel_author='Pingstkyrkan Eskilstuna';
// valid ISO 639-1 code like 'us_en' for US english
$channel_language='se_sv';
$channel_copyright='℗ &amp; © 2019 Pingstkyrkan Eskilstuna &amp;';
$channel_itunes_subtitle='Predikan';
$channel_summary='Alla inspelade möten på kyrkan';
$channel_description='Det vanligaste en ny besökare i vår kyrka ser, är många människor i olika åldrar och med olika stilar. Vi vill som församling vara en ”Hela livets kyrka”. En kyrka som inkluderar och välkomnar alla, oavsett var i livet du befinner dig';
$channel_owner_name='Pingstkyrkan Eskilstuna';
$channel_owner_email='info@pingsteskilstuna.se';
// link to channel image. Minimum size of 1400 x 1400 pixels and a maximum size of 3000 x 3000 pixels, 72 dpi, 
// in JPEG or PNG format with appropriate file extensions (.jpg, .png), and in the RGB colorspace
$channel_image=$base_url . '/pingstkyrkan.jpg';
// I assume this is ALWAYS 'no'. Used for both channel and mp3 file.
$channel_itunes_explicit='no';
// this MUST be a valid itunes category in xml format. This is used for both the channel and the mp3 files.
$itunes_category='<itunes:category text="Religion &amp; Spirituality"><itunes:category text="Religion"/></itunes:category>';

/////////////// mp3 file information

// this should be a valid itunes category
$category_general ='Religion';
// if no author 'artist' information is in the mp3 file then this will be used.
$mp3_default_author='Pingstkyrkan Eskilstuna';
// this is put as a description on EVERY mp3 file.
$mp3_description='Predikan från Pingstkyrkan Eskilstuna';

/* 
 *
 *  End of parameters you need to change. 
 *
 *  There should be no need to change below this line 
 */

function getFullHostCore()
{
    $protocole = $_SERVER['REQUEST_SCHEME'].'://';
    $host = $_SERVER['HTTP_HOST'] . '/';
    $project = explode('/', $_SERVER['REQUEST_URI']);
    array_pop($project);
    array_shift($project);
    $path = implode('/', $project);
    return $protocole . $host . $path;
}
	$base_url = getFullHostCore();
$files_dir = getcwd().'/upload/';

$files_url = $base_url.'/upload/';
// Location of getid3 folder, leave blank to disable. TRAILING SLASH REQ'D.
$getid3_dir = "getid3/";

// If getid3 was requested, attempt to initialise the ID3 engine
$getid3_engine = NULL;
if(strlen($getid3_dir) != 0) {
    require_once($getid3_dir . 'getid3.php');
    $getid3_engine = new getID3;
}
ob_start();
echo '<?xml version="1.0" encoding="utf-8" ?>';
?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">
    <channel>
        <title><?=$channel_title?></title>
        <link><?=$channel_link?></link>
        <language><?=$channel_language?></language>
        <copyright><?=$channel_copyright?></copyright>
        <itunes:subtitle><?=$channel_itunes_subtitle?></itunes:subtitle>
        <itunes:author><?=$channel_author?></itunes:author>
        <itunes:summary><?=$channel_summary?></itunes:summary>
        <description><?=$channel_description?></description>
        <itunes:owner>
            <itunes:name><?=$channel_owner_name?></itunes:name>
            <itunes:email><?=$channel_owner_email?></itunes:email>
        </itunes:owner>
        <itunes:image href="<?=$channel_image?>"/>
		<?=$itunes_category?>
        <itunes:explicit><?=$channel_itunes_explicit?></itunes:explicit>
<?php
		$directory = opendir($files_dir) or die($php_errormsg);
        // Step through file directory
        while(false !== ($file = readdir($directory))) {
            $file_path = $files_dir . $file;

            if(is_file($file_path) && strrchr($file_path, '.') == ".mp3") {
                // Initialise file details to sensible defaults
                $file_title = $file;
                $file_url = $files_url . $file;
                $file_duration = "";
                $file_description = $mp3_description;
                $file_date = date(DateTime::RFC2822, filemtime($file_path));
                $file_size = filesize($file_path);
				$feed_image = substr($file_url,0,strlen($file_url)-4) . '.jpg';
				$file_author =$mp3_default_author;
                // Read file metadata from the ID3 tags
                if(!is_null($getid3_engine)) {
                    $id3_info = $getid3_engine->analyze($file_path);
                    getid3_lib::CopyTagsToComments($id3_info);
                    
                    $file_title = $id3_info["comments_html"]["title"][0];
                    $file_author = array_key_exists($id3_info["comments_html"],'artist') 
							&& count($id3_info["comments_html"]["artist"])>0 
							&& strlen($id3_info["comments_html"]["artist"][0])>0 
							? $id3_info["comments_html"]["artist"][0] 
							: $mp3_default_author;
                    $file_duration = $id3_info["playtime_string"];
                }
        ?>		
        <item>
            <title><?= $file_title; ?></title>
            <link><?= $file_url; ?></link>
            
            <itunes:author><?=$file_author?></itunes:author>
			<?=$itunes_category?>
            <category><?=$category_general?></category>
            <duration><?= $file_duration; ?></duration>
            
            <description><?= $file_description; ?></description>
            <pubDate><?= $file_date; ?></pubDate>

            <enclosure url="<?= $file_url; ?>" length="<?= $file_size; ?>" type="audio/mpeg" />
            <guid><?= $file_url; ?></guid>
            <author><?= $file_author; ?></author>
            <itunes:image href="<?= $feed_image; ?>"/>
			<itunes:duration><?= $file_duration; ?></itunes:duration>
			<itunes:explicit><?= $channel_itunes_explicit; ?></itunes:explicit>
        </item>
        <?php
            }
        }
        closedir($files_dir);
        ?>		
    </channel>
</rss>
 <?php
$value = ob_get_contents();
ob_end_clean();
return $value;
		