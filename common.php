<?php
define('TWITTER_CACHE_FILE', 'twitter.cache');
define('TWITTER_CACHE_TIME', '-5 minutes');
define('TWITTER_ACCESS_TOKEN', '');
define('TWITTER_ACCESS_TOKEN_SECRET', '');
define('TWITTER_CONSUMER_KEY', '');
define('TWITTER_CONSUMER_SECRET', '');
define('FLICKR_CACHE_FILE', 'flickr.cache');
define('FLICKR_CACHE_TIME', '-1 day');
define('FLICKR_KEY', '');
define('FLICKR_SECRET', '');
define('FLICKR_TOKEN', '');
define('FLICKR_USER_ID', '');

function includeFileWithSlug($path){
	return $path.'?dlm='.filemtime($path);
}
?>
