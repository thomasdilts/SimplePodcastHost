<?php
/**
 * This is the rss feed. You can call this file with a browser to test. This is what you submit to itunes 
 * and some podcast apps. 
 * Although this file is very short, the heavy work is done in the rsscore.php file. 
 * The reason for doing all the work in another file is because several other php files need
 * this rss feed data and this solution seemed the best and fastest way to get the job done.
 */
 
header('Content-Type: application/xml; charset=utf-8');
echo require('rsscore.php');
