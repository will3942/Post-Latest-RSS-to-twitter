<?php 
set_time_limit(0); 
require_once('twitteroauth.php');
    define('CONSUMER_KEY', 'YOUR CONSUMER KEY');
    define('CONSUMER_SECRET', 'YOUR CONSUMER SECRET');
    define('ACCESS_TOKEN', 'YOUR ACCESS TOKEN');
    define('ACCESS_TOKEN_SECRET', 'YOUR ACCESS TOKEN SECRET');
	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
$rssurl = "RSS FEED URL";
$feeditems = array();
	$feedlinks = array();
	
	function getFeed($feed_url) {

	$content = file_get_contents($feed_url);
	$x = new SimpleXmlElement($content);
	global $feeditems;
	global $feedlinks;
		$feeditems=array();
		$feedlinks=array();
	foreach($x->channel->item as $entry) {
		$feeditems[] = $entry->title;
		$feedlinks[] = $entry->link;
		
	}
}
$currentrsstitle = "";
$newrsstitle = "";
function checkfornewloop() {
	echo "Looping...\r\n";
				checkfornew();
}
function checkfornew() {
				echo "Polling RSS...\r\n";
				global $feeditems;
				global $feedlinks;
				global $newrsstitle;
				global $currentrsstitle;
				global $rssurl;
				global $twitter;
				getFeed($rssurl);
				$newrsstitle = strval($feeditems[0]);
				if ($currentrsstitle == $feeditems[0]) {
				echo "No new post...\r\n";
				sleep(10);
				checkfornewloop();
				}
				else {
				echo "Posting a new post to twitter...";
				$twitter->host = "https://api.twitter.com/1/";
				$msg = "NEW RSS: $feeditems[0] $feedlinks[0] \r\n";
				$update_status = $twitter->post('statuses/update',array('status' => $msg));
				$currentrsstitle = $newrsstitle;
				sleep(10);
				checkfornewloop();
				}
				}
				checkfornewloop();
?>