<?php

/**
 * Class to parse your Facebook feed from the Facebook Graph API.
 *
 * @author    Chris Greninger
 * @link      http://www.creativecag.com
 * @version   1.2
 */
class FacebookFeed {

	/**
	 * Constructor
	 * 
	 * @param string $uid
	 * @param string $token 
	 */
	public function __construct($uid, $token) {
		$this->_token = $token;
		$this->_uid = $uid;
		$this->_url = 'https://graph.facebook.com/' . $this->_uid . '/feed?access_token=' . $this->_token; //feed url
		$this->feed_array = array();
	}

	/**
	 * Collects your feed from getData() and converts it to a single-dimensional PHP array
	 *
	 * @param int $limit Number of posts to return
	 * @param int $extras Show how many likes/comments on posts
	 * @param int $type Method used to get the feed (file_get_contents or cURL)
	 */
	public function getFeed($limit = 10) {

		// Validate the limit, halt if the limit is not a valid integer.
		if (!is_numeric($limit)) {
			trigger_error('Limit must be an integer. Please check the getFeed() argument and correct this.', E_USER_NOTICE);
			$limit = 10;
		}
		
		// Set the limit
		$this->limit = $limit;
		
	 	// Gets the actual feed contents
		$this->_getData();
		
		// Call _buildArray() to build the array
		return $this->_buildArray();

	}

	/**
	 * Build a single-dimensional PHP array out of the _getData() result
	 * 
	 * @access private
	 * @return array 
	 */
	private function _buildArray() {
		// Set the loop count to zero
		$status_count = 0;
		
		// Loop thru each feed item
		foreach ($this->feed->data as $status) {
			
			// Continue if the limit has not been exceeded
			if ($this->limit > $status_count) {
				
				// Continue if the user id matches $this->_uid. This filters out other friends posts on the users wall.
				if ($status->from->id == $this->_uid) {
					
					// Convert the date
					$date = strtotime($status->created_time);
					
					// Create an array with all of the items to be included in the final array
					$feed_keys = array('created_time','link','picture','caption','message','likes','comments','actions');
					
					// Loop thru the $feed_keys array
					foreach($feed_keys as $key=>$val) {
						
						 // if property doesn't exist, create null value for it.
						if (!property_exists($status,$val)) $status->$val = '';
						
						// format these special fields
						if ($val == 'created_time') $status->created_time = date('F j, Y', $date);
						if ($val == 'comments' && property_exists($status->comments,'count')) $status->comments = $status->comments->count;
						if ($val == 'likes' && property_exists($status->likes,'count')) $status->likes = $status->likes->count;
						if ($val == 'actions' && !empty($status->actions) && property_exists($status->actions[0],'link')) $status->actions = $status->actions[0]->link;

						// add this item to the array
						$this->feed_array[$status_count][$val] = $status->$val;
						
					}
					
					// Add one to loop count.
					$status_count++;
				}
			}
		}
		
		// Return final array
		return $this->feed_array;
	}

	/**
	 * Captures the feed and creates an object containing the data.
	 *
	 * @return mixed
	 */
	private function _getData() {
		
		// Check to see if file_get_contents is available and set up correctly.
		$file_get_contents = ini_get('allow_url_fopen') ? true : false;
		
		// Checks if cURL is installed.
		$curl = function_exists('curl_version') ? true : false;
		
		// If file_get_contents() is configured properly, use it.
		if ($file_get_contents) {
			
			// Get feed contents from Graph API
			$data = file_get_contents($this->_url);
		
		// Use cURL instead of file_get_contents()	
		} elseif ($curl) {
			
			// Initialize cURL and fetch feed contents from Graph API
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $this->_url);
			$data = curl_exec($ch);
			curl_close($ch);
		
		// cURL also unavailable, display error.
		} else {
			
			// Stop and return error.
			die('You must enable file_get_contents() or cURL to continue.');
			
		}

		// Sets object to feed contents
		$this->feed = json_decode($data);
	}
}