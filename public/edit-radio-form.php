 <?php include ('functions.php'); ?>

	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store category data
		$category_data = array();
			
		$sql_query = "SELECT cid, category_name FROM tbl_category ORDER BY cid ASC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($category_data['cid'], 
				$category_data['category_name']
				);
				
		}
			
		$sql_query = "SELECT radio_image FROM tbl_radio WHERE id = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_radio_image);
			$stmt->fetch();
			$stmt->close();
		}
		
		
		if(isset($_POST['btnEdit'])){
			
			$radio_name = $_POST['radio_name'];
			$cid = $_POST['cid'];
			$radio_url = $_POST['radio_url'];
			
			// get image info
			$radio_image = $_FILES['radio_image']['name'];
			$image_error = $_FILES['radio_image']['error'];
			$image_type = $_FILES['radio_image']['type'];
				
			// create array variable to handle error
			$error = array();
			
			if(empty($radio_name)){
				$error['radio_name'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
				
			if(empty($cid)){
				$error['cid'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}				
				
			if(empty($radio_url)){
				$error['radio_url'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}			
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["radio_image"]["name"]));
			
			if(!empty($radio_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['radio_image'] = "*<span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
			
					
			if( !empty($radio_name) && 
				!empty($cid) && 
				!empty($radio_url) && 
				empty($error['radio_image'])){
				
				if(!empty($radio_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['radio_image']['name']);
					$function = new functions;
					$radio_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink('upload/'."$previous_radio_image");
					$delete = unlink('upload/thumbs/'."$previous_radio_image");
					
					// upload new image
					$unggah = 'upload/'.$radio_image;
					$upload = move_uploaded_file($_FILES['radio_image']['tmp_name'], $unggah);	 
	  
					// updating all data
					$sql_query = "UPDATE tbl_radio 
							SET radio_name = ? , category_id = ?, radio_url = ?, radio_image = ?
							WHERE id = ?";
					
					$upload_image = $radio_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssss', 
									$radio_name, 
									$cid, 
									$radio_url, 
									$upload_image,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					// updating all data except image file
					$sql_query = "UPDATE tbl_radio 
							SET radio_name = ? , category_id = ?, radio_url = ?
							WHERE id = ?";
							
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssss', 
									$radio_name, 
									$cid,
									$radio_url,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}
					
				// check update result
				if($update_result){
					$error['update_data'] = "<br><div class='alert alert-info'>New Radio updated Successfully...</div>";
				}else{
					$error['update_data'] = "<br><div class='alert alert-danger'>Update Failed</div>";
				}
			}
			
		}		
		
		// create array variable to store previous data
		$data = array();
			
		$sql_query = "SELECT * FROM tbl_radio WHERE id = ?";
			
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['id'], 
					$data['cid'], 
					$data['radio_name'],  
					$data['radio_image'], 
					$data['radio_url']
					);
			$stmt->fetch();
			$stmt->close();
		}	
			
	?>

   <section class="content">
   
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-radio.php">Manage Radio</a></li>
            <li class="active">Edit Radio</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT RADIO</h2>
                                <?php echo isset($error['update_data']) ? $error['update_data'] : '';?>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Radio Name</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="radio_name" id="radio_name" value="<?php echo $data['radio_name'];?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Category</div>
                                        <select class="form-control show-tick" name="cid" id="cid">
                                           <?php while($stmt_category->fetch()){ 
												if ($category_data['cid'] == $data['cid']) { ?>
													<option value="<?php echo $category_data['cid']; ?>" selected="<?php echo $data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
														<?php }} 
											?>
	                                    </select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Radio Streaming Url</div>
                                        <div class="form-line">
                                            <input type="text" name="radio_url" id="radio_url" class="form-control" value="<?php echo $data['radio_url'];?>" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <div class="font-12 ex1">Image Primary ( jpg / png ) *</div>
                                    <div class="form-group">
                                        <input type="file" name="radio_image" id="radio_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/<?php echo $data['radio_image'];?>" data-show-remove="false"/>
                                    </div>
                                	</div>

                                	<div class="col-sm-12">
                                    <button type="submit" name="btnEdit" class="btn bg-blue waves-effect pull-right">UPDATE</button>
                                  	</div>
                                  	
                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>