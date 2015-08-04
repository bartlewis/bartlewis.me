<?php
/* Todo
 *
 * 1) aria props?
 * 2) would be nice to have a little arrow point left or right from social tooltip
 * 3) back to top links?
 * 4) META stuff for SEO
 * 5) Google Analytics
 */

require_once('common.php');
require_once('vendor/twitteroauth/twitteroauth.php');
require_once('vendor/phpFlickr.php');

// Get and cache recent tweets
if (!file_exists(TWITTER_CACHE_FILE) || filemtime(TWITTER_CACHE_FILE)<strtotime(TWITTER_CACHE_TIME)){
	// Connect to Twitter
	$twitter = new TwitterOAuth(
		TWITTER_CONSUMER_KEY,
		TWITTER_CONSUMER_SECRET,
		TWITTER_ACCESS_TOKEN,
		TWITTER_ACCESS_TOKEN_SECRET
	);

	$tweets = $twitter->get(
		'statuses/user_timeline',
		array(
			'screen_name' => 'bart_lewis',
			'count' => 1,
			'trim_user' => true
			)
	);

	//$tweets = @file_get_contents('http://api.twitter.com/1/statuses/user_timeline/bart_lewis.json');
	file_put_contents(TWITTER_CACHE_FILE, json_encode($tweets));
}
else{
	$tweets = json_decode(file_get_contents(TWITTER_CACHE_FILE));
}


// Get and cache recent Flickr photos
// $flickr = new phpFlickr(FLICKR_KEY);
// if (!file_exists(FLICKR_CACHE_FILE) || filemtime(FLICKR_CACHE_FILE)<strtotime(FLICKR_CACHE_TIME)){
// 	$flickrPhotos = serialize($flickr->people_getPublicPhotos(FLICKR_USER_ID, null, null, 6));
// 	file_put_contents(FLICKR_CACHE_FILE, $flickrPhotos);
// }
// else{
// 	$flickrPhotos = file_get_contents(FLICKR_CACHE_FILE);
// }
// $flickrPhotos = unserialize($flickrPhotos);

?>
<!DOCTYPE html>
<!--[if lt IE 9]><html lang="en" class="lte-ie8"><![endif]-->
<!--[if gt IE 8]><!--><html lang="en"><!--<![endif]-->
<head>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
       <meta name="author" content="Bart Lewis">
		<meta name="keywords" content="web developer, front end developer, mobile developer, southern california, antelope valley, jquery, html5, css3">

		<title>Bart Lewis | End to End Web Developer</title>

		<script src="<?php echo includeFileWithSlug('vendor/modernizr.js'); ?>"></script>

		<link rel="stylesheet" href="<?php echo includeFileWithSlug('vendor/normalize.css'); ?>" media="all" />
		<link rel="stylesheet" href="<?php echo includeFileWithSlug('css/master.css'); ?>" media="all" />

		<!--[if lt IE 9]>
			<script src="<?php echo includeFileWithSlug('vendor/respond.min.js'); ?>"></script>
		<![endif]-->

		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-29005625-1']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>
	<body>
		<header id="header" role="banner"> <!-- ID needed for anchor -->
			<div class="content">
				<h1>Bart Lewis</h1>
				<h2>End to End Web Developer</h2>

				<nav role="navigation">
					<ul>
						<li class="about"><a href="#about">About</a></li>
						<li class="resume"><a href="#resume">Resume</a></li>
						<li class="projects"><a href="#projects">Projects</a></li>
						<li class="contact"><a href="#contact">Contact</a></li>
						<li class="social"><a href="#social">Social</a></li>
					</ul>
				</nav>
			</div>
		</header>

		<section id="about" role="region" aria-label="About">
			<div class="content">
				<h3>Hi! My name is Bart.</h3>
				<img src="images/headshot.jpg" alt="Bart's Headshot" >
				<p>
					Professionally, I've been developing web sites and web applications of all shapes and sizes for over a decade. During this time, I've done both front end and back end development. Most of my back end experience revolves around PHP and MySQL (and a little Node.js more recently). Lately however, the front end development is getting a lot of my attention. I've been really interested in getting deeper into HTML5, CSS3, and JavaScript (jQuery, Backbone.js, Angular, Require.js, and etc). More than half of my career has been as a remote worker, out of a home office.
				</p>
				<p>
					I have been married to my wife, <a href="http://www.twitter.com/heathrlewis">Heather</a>, for thirteen years. Together, we have three gorgeous little girls (not at all biased or anything). We currently live in Southern California.
				</p>
				<p>
					In my free time, I enjoy hiking, reading, board games and video games. As a kid, I wasn't a huge reader, but lately I have become more and more enthralled with reading. As a by-product of reading, I've also become increasingly interested in personal finance.
				</p>
				<p>
					In both my work, and life in general, I have a hunger to always learn more and grow. I have a running joke with some close friends that nothing is ever a 10 (on a scale of 1-10). Philosophically, that would imply it could not be improved in any way. Continuous improvement is the way I live my life.
				</p>
				<span class="tweet">
					<?php
						// Replace links
						echo @ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $tweets[0]->text);
					?>
					<br><a href="http://twitter.com/bart_lewis" class="twit">@bart_lewis</a>
				</span>
<!-- 				<div class="flickr">
					<?php
						// $html = '';
						// foreach($flickrPhotos['photos']['photo'] as $photo){
						// 	$html .= '<a href="http://www.flickr.com/photos/'.$photo['owner'].'/'.$photo['id'].'">';
						// 	$html .= '<img src="'.$flickr->buildPhotoURL($photo, 'small').'" alt="'.$photo['title'].'" >';
						// 	$html .= '</a>';
						// }
						// echo $html;
					?>
				</div> -->
			</div>
			<div class="rip"><!-- How to do this without any extra markup? --></div>
		</section>

		<section id="resume" role="region" aria-labelledby="resume-header">
			<div class="content">
				<h3 id="resume-header">Resume</h3>
				<p>
					Outlined here are some quick and dirty details. For a full resume, along with recommendations and etc., please use one of the following services. Why re-create the wheel?
				</p>
				<ul class="links">
					<li><a href="http://www.linkedin.com/in/bartlewis" class="big-button">LinkedIn Profile</a></li>
					<li><a href="http://careers.stackoverflow.com/cv/publish/20162" class="big-button">StackOverflow Careers Profile</a></li>
				</ul>
				<h4>Specialties</h4>
				<ul>
					<li>HTML5</li>
					<li>CSS3</li>
					<li>JavaScript</li>
					<li>MySQL</li>
					<li>PHP</li>
				</ul>
				<h4>Experience</h4>
				<p>
					In 1996, I attended a one year technical program entitled "Multimedia Production". Immediately following this, I started developing CBT (computer based training) for delivery on CD-ROM. In 2000, I transitioned from developing programs for CD-ROMs, to developing for the Web. Since then, I've been heavily involved in web development. At times I focused specifically on back end development, and at other times front end development. Lately, it is more front end, but I still enjoy both.
				</p>
			</div>
		</section>

		<section id="projects" role="region" aria-labelledby="projects-header">
			<div class="content">
				<h3 id="projects-header">Projects</h3>

				<section aria-expanded="true">
					<header>
						<h4>PeggSite</h4>
	 					<ul>
							<li>Require.js</li>
							<li>Backbone</li>
							<li>HTML5</li>
							<li>CSS3</li>
							<li>jQuery</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/peggsite.jpg" width="260" height="140" alt="PeggSite thumbnail">
						<p>
							PeggSite is a one-page, rich-media platform that falls between endless feeds and static websites.
							I founded PeggSite with my good friend, <a href="http://twitter.com/kirklove">Kirk Love</a>.
							The idea for PeggSite, and the design, is all Kirk. The code (front end and back) is all me.
							PeggSite is a side project [for me], and is coming to life bit by bit (or feature by feature if you prefer).
						</p>
						<dl>
							<dt>When</dt>
							<dd>2013</dd>

							<dt>Where</dt>
							<dd><a href="http://www.peggsite.com/">www.peggsite.com</a></dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>XboxAddict</h4>
	 					<ul>
							<li>Bootstrap</li>
							<li>HTML5</li>
							<li>CSS3</li>
							<li>jQuery</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/xboxaddict.jpg" width="260" height="140" alt="XboxAddict thumbnail">
						<p>
							I consulted with XboxAddict.com on a front end, responsive redesign in late 2012. The main goal of this effort was to provide better support for tablets and phone based browsers.
							We used <a href="http://twitter.github.com/bootstrap/">Bootstrap</a> as a jumping point, as the time spent on this redesign was very constrained.
						</p>
						<dl>
							<dt>When</dt>
							<dd>2012</dd>

							<dt>Where</dt>
							<dd><a href="http://www.xboxaddict.com/">www.xboxaddict.com</a></dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>Michican SACWIS WBT</h4>
						<ul>
							<li>HTML5</li>
							<li>CSS3</li>
							<li>jQuery</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/misacwis-wbt.jpg" width="260" height="140" alt="Michigan SACWIS WBT thumbnail">
						<p>
							This is one of many Web-Based Training (WBT) products I helped to create, while at DRC.
							This particular WBT sample was created for the State of Michigan. It uses responsive web design,
							and "progressive enhancement" to make sure it is consumable by the broadest range of users. I did
							100% of the front end code (HTML, CSS, JavaScript) on this one.
						</p>
						<dl>
							<dt>When</dt>
							<dd>2011</dd>

							<dt>Where</dt>
							<dd><a href="projects/misacwis-wbt/sco_1305/">Local</a></dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>Gabby Gourmet iPhone app</h4>
	 					<ul>
							<li>Appcelerator</li>
							<li>SQLite</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/gabby-gourmet.jpg" width="260" height="140" alt="Gabby Gourmet thumbnail">
						<p>
							Gabby Gourmet is a restaurant critic in the Denver area. Hero Design Studio did all of the design work,
							and I did all of the code to bring this app to life
							(using <a href="http://www.appcelerator.com/">Appcelerator Titanium</a>).
						</p>
						<dl>
							<dt>When</dt>
							<dd>2011</dd>

							<dt>Where</dt>
							<dd><a href="http://itunes.apple.com/us/app/gabby-gourmet-guide/id420954367?mt=8">iTunes</a></dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>DT Landscape</h4>
						<ul>
							<li>HTML5</li>
							<li>CSS3</li>
							<li>jQuery</li>
							<li>PHP</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/dt-landscape.jpg" width="260" height="140" alt="DT Landscape thumbnail">
						<p>
							This is a website I built for a local contractor. I did all of the design, code (HTML, CSS, JavaScript, PHP), and even took most of the pictures.
							The gallery was all done as an integration with their Facebook page. This allows them to upload new photos and create new categories at will.
						</p>
						<dl>
							<dt>When</dt>
							<dd>2009</dd>

							<dt>Where</dt>
							<dd><a href="http://www.dtlandscape.com/">www.dtlandscape.com</a></dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>Doing What Works</h4>
						<ul>
							<li>HTML</li>
							<li>CSS</li>
							<li>jQuery</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/doing-what-works.jpg" width="260" height="140" alt="Doing What Works thumbnail">
						<p>
							This website was built by Little Planet Learning, for the U.S. Department of Education. I worked with Little Planet to help with the initial front end development. I turned their PSD designs into HTML / CSS templates, and their Back End developers took it from there.
						</p>
						<dl>
							<dt>When</dt>
							<dd>2009</dd>

							<dt>Where</dt>
							<dd><a href="http://dww.ed.gov/">dww.ed.gov</a></dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>DRC LMS Lite</h4>
						<ul>
							<li>HTML</li>
							<li>CSS</li>
							<li>Prototype</li>
							<li>PHP</li>
							<li>MySQL</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/drc-lms.jpg" width="260" height="140" alt="DRC LMS Lite thumbnail">
						<p>
							The LMS Lite is another web application that I designed and built for DRC. This web app is a learning management system (LMS) that DRC provides to its clients, to allow them to host their internal WBT. It was built using PHP and MySQL. DRC has deployed this LMS for several clients, the largest with a user base of over 50,000 users.
						</p>
						<dl>
							<dt>When</dt>
							<dd>2007</dd>
						</dl>
					</div>
				</section>

				<section aria-expanded="true" class="collapsed">
					<header>
						<h4>DRC XP-23</h4>
						<ul>
							<li>HTML</li>
							<li>CSS</li>
							<li>Prototype</li>
							<li>PHP</li>
							<li>MySQL</li>
						</ul>
					</header>
					<div>
						<img src="images/projects/thumbs/drc-xp23.jpg" width="260" height="140" alt="DRC XP-23 thumbnail">
						<p>
							The XP-23 is an internal DRC tool that I built from scratch using PHP and MySQL. This web application was designed to allow remote teams of instructional designers, subject matter experts, and graphic artists, to collaboratively work together online, and rapidly produce Web Based Training (WBT) courses.
						</p>
						<dl>
							<dt>When</dt>
							<dd>2005</dd>
						</dl>
					</div>
				</section>
			</div>
		</section>

		<section id="contact" role="region" aria-labelledby="contact-header">
			<div class="content">
				<form id="contact-form" action="contact.php" method="post">
					<h3 id="contact-header">Contact Me</h3>
					<p class="name">
						<label for="name">Name</label>
						<input type="text" id="name" name="name" placeholder="Name" required>
					</p>
					<p class="email">
						<label for="email">Email</label>
						<input type="email" id="email" name="email" placeholder="Email" required>
					</p>
					<p class="subject">
						<label for="subject">Subject</label>
						<input type="text" id="subject" name="subject" placeholder="Subject" required>
					</p>
					<p class="captcha">
						<label for="captcha">Do not check this box</label>
						<input type="checkbox" id="captcha" name="captcha">
					</p>
					<p class="url">
						<label for="url">URL</label>
						<input type="text" id="url" name="url">
					</p>
					<p class="message">
						<label for="message">Message</label>
						<textarea id="message" name="message" placeholder="Message" required></textarea>
					</p>
					<p class="submit">
						<button type="submit" class="big-button">Send Message</button>
					</p>
				</form>
			</div>
		</section>

		<section id="social" role="region" aria-labelledby="social-header">
			<div class="content">
				<h3 id="social-header">Social Links</h3>
				<ul class="list-a">
					<li class="linkedin"><a href="http://www.linkedin.com/in/bartlewis">LinkedIn</a></li>
					<li class="twitter"><a href="http://twitter.com/bart_lewis">Twitter</a></li>
					<li class="facebook"><a href="http://www.facebook.com/bartonlewis">Facebook</a></li>
					<li class="peggsite"><a href="http://peggsite.com/bart">PeggSite</a></li>
					<li class="stackoverflow"><a href="http://stackoverflow.com/users/158651/bart">StackOverflow</a></li>
					<li class="github"><a href="https://github.com/bartlewis">Github</a></li>
				</ul>
				<ul class="list-b">
					<li class="fitbit"><a href="http://www.fitbit.com/user/226WHP">FitBit</a></li>
					<li class="xbox"><a href="http://live.xbox.com/en-US/profile/profile.aspx?GamerTag=FragMaster%20B">Xbox Live</a></li>
					<li class="lastfm"><a href="http://www.last.fm/user/bartlewis">Last.fm</a></li>
					<li class="flickr"><a href="http://www.flickr.com/photos/bartlewis">Flickr</a></li>
					<li class="goodreads"><a href="http://www.goodreads.com/bartlewis">Goodreads</a></li>
					<li class="boardgamegeek"><a href="http://boardgamegeek.com/user/FragMaster+B">BoardGameGeek</a></li>
				</ul>
			</div>
		</section>

		<footer role="contentinfo">
			<div class="content">
				<a href="#header">&#94; Top &#94;</a>
				&#169;2013 Bart Lewis
			</div>
		</footer>

		<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAXm5Ob4FsilY54q9OgZpawBQ8YY0Mem9itPyl_vpBAWCXthcIBRRR7AuzfQpGBJ3EuwgvxZkrQD7Evg"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="<?php echo includeFileWithSlug('script/master.js'); ?>"></script>
	</body>
</html>