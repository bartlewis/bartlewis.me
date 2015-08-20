<?php
date_default_timezone_set('America/Los_Angeles');

define('GOOGLE_ANALYTICS_ID', 'UA-29005625-1');
define('TWITTER_CACHE_FILE', 'twitter.cache');
define('TWITTER_CACHE_TIME', '-5 minutes');
define('TWITTER_ACCESS_TOKEN', '');
define('TWITTER_ACCESS_TOKEN_SECRET', '');
define('TWITTER_CONSUMER_KEY', '');
define('TWITTER_CONSUMER_SECRET', '');

function includeFileWithSlug($path){
	return $path.'?dlm='.filemtime($path);
}
?>
