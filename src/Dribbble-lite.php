<?php

/**
 * Dribbble API PHP Lite Wrapper : A PHP wrapper for the Dribbble API v1 using only the Client Access Token
 * 
 * @package   OM-Dribbble-API-PHP-Lite
 * @author    Oscar Marcelo
 * @since     12.03.2015
 * @version   1.0
 * @license   MIT License
 * @link      http://github.com/oscarmarcelo/Dribbble
 */

class Dribbble {

	const API_URL = 'https://api.dribbble.com/v1/';

	private $access_token;

	private $curl_options = array(
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 60,
		CURLOPT_USERAGENT      => 'om-dribbble-api-php-lite'
	);


	/**
	 * The Class constructor
	 * @param string $token The Client Access Token to be used.
	 * @return string
	 */
	function __construct($token) {

		if (!in_array('curl', get_loaded_extensions()))
			throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');

		if (!isset($token))
			throw new Exception('Make sure you are passing in the correct parameters');

		$this->access_token = $token;

	}


	/**
	 * Makes a GET call.
	 * @param string $endpoint The endpoint.
	 * @param array $params The parameters for the endpoint.
	 * @return object
	 */
	private function request($endpoint, $params = array()) {

		$ch = curl_init();
		$options = $this->curl_options;
		$options[CURLOPT_URL] = self::API_URL . $endpoint . '?access_token=' . $this->access_token;
		if (!empty($params))
			$options[CURLOPT_URL].= '&' . http_build_query($params, null, '&');

		curl_setopt_array($ch, $options);

		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($error = curl_error($ch))
			throw new Exception($error, curl_errno($ch));

		curl_close($ch);

		$verify = json_decode($result);

		if (isset($verify->message))
			throw new Exception($verify->message, $status);

		return array(
			'status' => $status,
			'result' => $result
			);

	}


	/**
	 * Gets information about a bucket
	 * @param integer $id The id of the bucket
	 * @param array $params 
	 * @return object
	 */
	function bucket($id, $params = array()) {

		return $this->request(sprintf('/buckets/%d', $id), $params)['result'];

	}


	/**
	 * Gets the shots of a bucket
	 * @param integer $id The id of the bucket
	 * @param array $params 
	 * @return object
	 */
	function bucket_shots($id, $params = array()) {

		return $this->request(sprintf('/buckets/%d/shots', $id), $params)['result'];

	}


	/**
	 * Gets information about a project
	 * @param integer $id The id of the project
	 * @param array $params 
	 * @return object
	 */
	function project($id, $params = array()) {

		return $this->request(sprintf('/projects/%d', $id), $params)['result'];

	}


	/**
	 * Gets the shots of a project
	 * @param integer $id The id of a project
	 * @param array $params 
	 * @return object
	 */
	function project_shots($id, $params = array()) {

		return $this->request(sprintf('/projects/%d/shots', $id), $params)['result'];

	}


	/**
	 * Gets a list of shots
	 * @param string $list The type of shots to get.
	 * @param string $timeframe The period of time to limit the results
	 * @param date $date The timeframe to limit the results
	 * @param string $sort The sorting method
	 * @param array $params 
	 * @return object
	 */
	function shots($list = false, $timeframe = false, $date = false, $sort = false, $params = array()) {

		if ($list)      $params['list'] =      $list;
		if ($timeframe) $params['timeframe'] = $timeframe;
		if ($date)      $params['date'] =      date('Y-m-d', strtotime($date));
		if ($sort)      $params['sort'] =      $sort;

		return $this->request('/shots', $params)['result'];

	}


	/**
	 * Gets a single shot
	 * @param integer $id The id of the shot
	 * @return object
	 */
	function shot($id) {

		return $this->request(sprintf('/shots/%d', $id), $params)['result'];

	}


	/**
	 * Gets the attachemnts of a shot
	 * @param integer $id The id of the shot
	 * @param array $params 
	 * @return object
	 */
	function shot_attachments($id, $params = array()) {

		return $this->request(sprintf('/shots/%d/attachments', $id), $params)['result'];

	}


	/**
	 * Gets a single attachment
	 * @param integer $shot_id The id of the shot
	 * @param integer $attachment_id The id of the attachment
	 * @return object
	 */
	function attachment($shot_id, $attachment_id) {

		return $this->request(sprintf('/shots/%1$d/attachments/%2$d', $shot_id, $attachment_id), $params)['result'];

	}


	/**
	 * Gets the buckets for a shot
	 * @param integer $id The id of the shot
	 * @return object
	 */
	function shot_buckets($id) {

		return $this->request(sprintf('/shots/%d/buckets', $id), $params)['result'];

	}


	/**
	 * Gets the comments of a shot
	 * @param integer $id The id of the shot
	 * @param array $params 
	 * @return object
	 */
	function shot_comments($id, $params = array()) {

		return $this->request(sprintf('/shots/%d/comments', $id), $params)['result'];

	}


	/**
	 * Gets the likes of a comment
	 * @param integer $shot_id The id of the shot
	 * @param integer $comment_id The id of the comment
	 * @param array $params 
	 * @return object
	 */
	function comment_likes($shot_id, $comment_id, $params = array()) {

		return $this->request(sprintf('/shots/%1$d/comments/%2$d/likes', $shot_id, $comment_id), $params)['result'];

	}


	/**
	 * Gets a single comment
	 * @param integer $shot_id The id of the shot
	 * @param integer $comment_id The id of the comment
	 * @return object
	 */
	function comment($shot_id, $comment_id) {

		return $this->request(sprintf('/shots/%1$d/comments/%2$d', $shot_id, $comment_id), $params)['result'];

	}


	/**
	 * Checks if the authenticated user has liked a comment
	 * @param integer $shot_id The id of the shot
	 * @param integer $comment_id The id of the comment
	 * @return mixed
	 */
	function has_liked_comment($shot_id, $comment_id) {

		$response = $this->request(sprintf('/shots/%1$d/comments/%2$d/like', $shot_id, $comment_id), $params);

		if ($response['status'] == '200') {
			return $response['result'];
		} elseif ($response['status'] == '404') {
			return false;
		}

	}


	/**
	 * Gets the likes of a shot
	 * @param integer $id The id of the shot
	 * @param array $params 
	 * @return object
	 */
	function shot_likes($id, $params = array()) {

		return $this->request(sprintf('/shots/%d/likes', $id), $params)['result'];

	}


	/**
	 * Checks if the authenticated user has liked a shot
	 * @param integer $id The id of the shot
	 * @return mixed
	 */
	function has_liked_shot($id) {

		$response = $this->request(sprintf('/shots/%d/like', $id), $params);

		if ($response['status'] == '200') {
			return $response['result'];
		} elseif ($response['status'] == '404') {
			return false;
		}

	}


	/**
	 * Gets the projects for a shot
	 * @param integer $id The id of the shot
	 * @param array $params 
	 * @return object
	 */
	function shot_projects($id, $params = array()) {

		return $this->request(sprintf('/shots/%d/projects', $id), $params)['result'];

	}


	/**
	 * Gets the rebounds of a shot
	 * @param integer $id The id of the shot
	 * @param array $params 
	 * @return object
	 */
	function shot_rebounds($id, $params = array()) {

		return $this->request(sprintf('/shots/%d/rebounds', $id), $params)['result'];

	}


	/**
	 * Gets the members of a team
	 * @param mixed $team The username or id of the team
	 * @param array $params 
	 * @return object
	 */
	function team_members($team, $params = array()) {

		return $this->request(sprintf('/teams/%s/members', $team), $params)['result'];

	}


	/**
	 * Gets the shots of a team
	 * @param mixed $team The username or id of the team
	 * @param array $params 
	 * @return object
	 */
	function team_shots($team, $params = array()) {

		return $this->request(sprintf('/teams/%s/shots', $team), $params)['result'];

	}


	/**
	 * Gets information of a user.
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user($user, $params = array()) {

		return $this->request(sprintf('/users/%s', $user), $params)['result'];

	}


	/**
	 * Gets information of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user($params = array()) {

		return $this->request('/user', $params)['result'];

	}


	/**
	 * Gets the buckets of a user
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_buckets($user, $params = array()) {

		return $this->request(sprintf('/users/%s/buckets', $user), $params)['result'];

	}


	/**
	 * Gets the buckets of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_buckets($params = array()) {

		return $this->request('/user/buckets', $params)['result'];

	}


	/**
	 * Gets the followers of a user
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_followers($user, $params = array()) {

		return $this->request(sprintf('/users/%s/followers', $user), $params)['result'];

	}


	/**
	 * Gets the followers of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_followers($params = array()) {

		return $this->request('/user/followers', $params)['result'];

	}


	/**
	 * Gets the followees of a user
	 * @param mixed $user The username of id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_following($user, $params = array()) {

		return $this->request(sprintf('/users/%s/following', $user), $params)['result'];

	}


	/**
	 * Gets the followees of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_following($params = array()) {

		return $this->request('/user/following', $params)['result'];

	}


	/**
	 * Gets the shots of the users followed by the authenticated user
	 * @param type $params 
	 * @return type
	 */
	function current_user_following_shots($params = array()) {

		return $this->request('/user/following/shots', $params)['result'];

	}


	/**
	 * Checks if the authenticated user is following a user
	 * @param mixed $user The username or id of the user
	 * @return boolean
	 */
	function is_current_user_following($user) {

		$status = $this->request(sprintf('/user/following/%s', $user), $params)['status'];

		if ($status == '204') {
			return true;
		} elseif($status == '404') {
			return false;
		}

	}


	/**
	 * Checks if a user is following another user
	 * @param mixed $user The username or id of the user
	 * @param mixed $target_user The username or id of the target user
	 * @return boolean
	 */
	function is_user_following($user, $target_user) {

		$status = $this->request(sprintf('/users/%1$s/following/%2$s', $user, $target_user), $params)['status'];

		if ($status == '204') {
			return true;
		} elseif($status == '404') {
			return false;
		}

	}


	/**
	 * Gets the likes of a user
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_likes($user, $params = array()) {

		return $this->request(sprintf('/users/%s/likes', $user), $params)['result'];

	}


	/**
	 * Gets the likes of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_likes($params = array()) {

		return $this->request('/user/likes', $params)['result'];

	}


	/**
	 * Gets the projects of a user
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_projects($user, $params = array()) {

		return $this->request(sprintf('/users/%s/projects', $user), $params)['result'];

	}


	/**
	 * Gets the projects of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_projects($params = array()) {

		return $this->request('/user/projects', $params)['result'];

	}


	/**
	 * Gets the shots of a user
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_shots($user, $params = array()) {

		return $this->request(sprintf('/users/%s/shots', $user), $params)['result'];

	}


	/**
	 * Gets the shots of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_shots($params = array()) {

		return $this->request('/user/shots', $params)['result'];

	}


	/**
	 * Gets the teams of a user
	 * @param mixed $user The username or id of the user
	 * @param array $params 
	 * @return object
	 */
	function user_teams($user, $params = array()) {

		return $this->request(sprintf('/users/%s/teams', $user), $params)['result'];

	}


	/**
	 * Gets the teams of the authenticated user
	 * @param array $params 
	 * @return object
	 */
	function current_user_teams($params = array()) {

		return $this->request('/user/teams', $params)['result'];

	}

}