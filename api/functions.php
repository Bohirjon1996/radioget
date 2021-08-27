<?php

require_once("Rest.inc.php");
require_once("db.php");

class functions extends REST {
    
    private $mysqli = NULL;
    private $db = NULL;
    
    public function __construct($db) {
        parent::__construct();
        $this->db = $db;
        $this->mysqli = $db->mysqli;
    }

	public function checkConnection() {
		if (mysqli_ping($this->mysqli)) {
			$respon = array('status' => 'ok', 'database' => 'connected');
            $this->response($this->json($respon), 200);
		} else {
            $respon = array('status' => 'failed', 'database' => 'not connected');
            $this->response($this->json($respon), 404);
		}
	}

    public function getRecentRadio() {

    	include "../includes/config.php";
		$setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];

		if (isset($_GET['api_key'])) {

			$access_key_received = $_GET['api_key'];

			if ($access_key_received == $api_key) {

				if ($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
								
					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT id) FROM tbl_radio");
					$query = "SELECT n.id AS 'radio_id', n.radio_name, n.radio_image, n.radio_url, c.category_name FROM tbl_category c, tbl_radio n WHERE c.cid = n.category_id ORDER BY n.id DESC LIMIT $limit OFFSET $offset";

					$categories = $this->get_list_result($query);
					$count = count($categories);
					$respon = array(
									'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $categories
					);
					$this->response($this->json($respon), 200);

			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
				$this->response($this->json($respon), 404);
			}
		} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
		}			

    } 
    
    public function getCategoryIndex() {

    	include "../includes/config.php";
		$setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];

		if (isset($_GET['api_key'])) {

			$access_key_received = $_GET['api_key'];

			if ($access_key_received == $api_key) {    	

				if($this->get_request_method() != "GET") $this->response('',406);
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT cid) FROM tbl_category");

					$query = "SELECT DISTINCT c.cid, c.category_name, c.category_image, COUNT(DISTINCT r.id) as radio_count
								FROM tbl_category c LEFT JOIN tbl_radio r ON c.cid = r.category_id GROUP BY c.cid ORDER BY c.cid DESC";

					$news = $this->get_list_result($query);
					$count = count($news);
					$respon = array(
						'status' => 'ok', 'count' => $count, 'categories' => $news
					);
					$this->response($this->json($respon), 200);

			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
				$this->response($this->json($respon), 404);
			}
		} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
		}				

    }

    public function getCategoryDetail() {

    	$id = $_GET['id'];

		if($this->get_request_method() != "GET") $this->response('',406);
		$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
		$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

		$offset = ($page * $limit) - $limit;
		$count_total = $this->get_count_result("SELECT COUNT(DISTINCT id) FROM tbl_radio WHERE category_id = '$id'");

		$query_category = "SELECT distinct cid, category_name, category_image FROM tbl_category WHERE cid = '$id' ORDER BY cid DESC";

		$query_post = "SELECT DISTINCT n.id AS 'radio_id', 
						n.radio_name, 
						n.radio_image,
						n.radio_url, 
						n.category_id,	
						c.category_name

						FROM tbl_radio n 

						LEFT JOIN tbl_category c ON n.category_id = c.cid 

						WHERE c.cid = '$id' 

						GROUP BY n.id 
						ORDER BY n.id DESC 
								 
						LIMIT $limit OFFSET $offset";

		$category = $this->get_category_result($query_category);
		$post = $this->get_list_result($query_post);
		$count = count($post);
		$respon = array(
			'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'category' => $category, 'posts' => $post
		);
		$this->response($this->json($respon), 200);

    }

    public function getSearchResults() {

    	include "../includes/config.php";
		$setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];

		if (isset($_GET['api_key'])) {

			$access_key_received = $_GET['api_key'];

			if ($access_key_received == $api_key) {    	

				$search = $_GET['search'];

				if($this->get_request_method() != "GET") $this->response('',406);
				$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
				$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

				$offset = ($page * $limit) - $limit;
				$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.id) FROM tbl_radio n, tbl_category c WHERE n.category_id = c.cid AND (n.radio_name LIKE '%$search%' OR c.category_name LIKE '%$search%')");

				$query = "SELECT DISTINCT n.id AS 'radio_id', 
											n.radio_name, 
											n.radio_image,
											n.radio_url,
											c.category_name

										  FROM tbl_radio n 

										  LEFT JOIN tbl_category c ON n.category_id = c.cid 

										  WHERE n.category_id = c.cid AND (n.radio_name LIKE '%$search%' OR c.category_name LIKE '%$search%') 

										  GROUP BY n.id 
										  ORDER BY n.id DESC

									LIMIT $limit OFFSET $offset";

				$post = $this->get_list_result($query);
				$count = count($post);
				$respon = array(
					'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
				);
				$this->response($this->json($respon), 200);

			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
				$this->response($this->json($respon), 404);
			}
		} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
		}		

    }

	public function getPrivacyPolicy() {

		include "../includes/config.php";
		
		$sql = "SELECT * FROM tbl_settings WHERE id = 1";
		$result = mysqli_query($connect, $sql);

		header( 'Content-Type: application/json; charset=utf-8' );
		print json_encode(mysqli_fetch_assoc($result));


	}




    public function get_list_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			while($row = $r->fetch_assoc()) {
				$result[] = $row;
			}
		}
		return $result;
	}

    public function get_count_result($query) {
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			$result = $r->fetch_row();
			return $result[0];
		}
		return 0;
	}

	private function get_category_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			while($row = $r->fetch_assoc()) {
				$result = $row;
			}
		}
		return $result;
	}

	private function get_one($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) $result = $r->fetch_assoc();
		return $result;
	}
    
}

?>