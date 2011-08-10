<?php 
	require_once('FacebookFeed.class.php');
	
	// Enter your Facebook ID and Access Token below to access your feed.
	$feed = new FacebookFeed('YOUR_FACEBOOK_ID','YOUR_ACCESS_TOKEN');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta content="text/html; charset=utf-8" />
<title>Facebook Feed</title>
</head>
<body>
	<?php 
		/**
		 * getFeed() accepts only 1 argument: how many posts to return. Default is 10.
		 * 
		 */
		$feed_items = $feed->getFeed(5);
		foreach ($feed_items as $post) {
			
			echo '<pre>';
			print_r($post);
			echo '</pre>';

		}
		
	?>
</body>
</html>