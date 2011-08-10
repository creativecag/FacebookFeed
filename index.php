<?php 
	require_once('FacebookFeed.class.php');
	$feed = new FacebookFeed('1350464034','169322833130874%7C4558344e6ac0b5f484eb463c.1-1350464034%7CIJ72bkzUCknwksDBWYiSzcqZN2Q'); // FacebookFeed('YOUR_FACEBOOK_ID','YOUR_ACCESS_TOKEN')
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
		$feed_items = $feed->getFeed('3');
		foreach ($feed_items as $post) {
			
			echo '<pre style="background:black; color:white;">';
			print_r($post);
			echo '</pre>';

		}
		
	?>
</body>
</html>