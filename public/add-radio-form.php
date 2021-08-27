 <?php include ('functions.php'); ?>

    <?php

        $sql_query = "SELECT cid, category_name FROM tbl_category ORDER BY category_name ASC";
                
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
        
            
        //$max_serve = 10;
            
        if(isset($_POST['btnAdd'])){
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
                    
            if ($image_error > 0) {
                $error['radio_image'] = " <span class='font-12 col-red'>You're not insert images!!</span>";
            } else if(!(($image_type == "image/gif") || 
                ($image_type == "image/jpeg") || 
                ($image_type == "image/jpg") || 
                ($image_type == "image/x-png") ||
                ($image_type == "image/png") || 
                ($image_type == "image/pjpeg")) &&
                !(in_array($extension, $allowedExts))){
            
                $error['radio_image'] = " <span class='font-12'>Image type must jpg, jpeg, gif, or png!</span>";
            }
                
            if (!empty($radio_name) && 
                !empty($cid) && 
                !empty($radio_url) && 
                 empty($error['radio_image'])) {        

                // create random image file name
                $string = '0123456789';
                $file = preg_replace("/\s+/", "_", $_FILES['radio_image']['name']);
                $function = new functions;
                $radio_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
                    
                // upload new image
                $unggah = 'upload/'.$radio_image;
                $upload = move_uploaded_file($_FILES['radio_image']['tmp_name'], $unggah);
        
                // insert new data to menu table
                $sql_query = "INSERT INTO tbl_radio (radio_name, category_id, radio_url, radio_image)
                        VALUES(?, ?, ?, ?)";
                        
                $upload_image = $radio_image;
                $stmt = $connect->stmt_init();
                if($stmt->prepare($sql_query)) {    
                    // Bind your variables to replace the ?s
                    $stmt->bind_param('ssss', 
                                $radio_name, 
                                $cid, 
                                $radio_url, 
                                $upload_image
                                );
                    // Execute query
                    $stmt->execute();
                    // store result 
                    $result = $stmt->store_result();
                    $stmt->close();
                }           
                
                if ($result) {
                    $error['add_radio'] = "<br><div class='alert alert-info'>New Radio Added Successfully...</div>";
                } else {
                    $error['add_radio'] = "<br><div class='alert alert-danger'>Added Failed</div>";
                }
            }
                
            }
    ?>

   <section class="content">
   
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Главная</a></li>
            <li><a href="manage-radio.php">Радиостанции</a></li>
            <li class="active">Добавить радио</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>ДОБАВИТЬ РАДИО</h2>
                                <?php echo isset($error['add_radio']) ? $error['add_radio'] : '';?>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">
                            <div>
                                    <div class="form-group form-float col-sm-12">
                                        <div class="font-12">Название радио</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="radio_name" id="radio_name" placeholder="Radio Name" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Категория</div>
                                        <select class="form-control show-tick" name="cid" id="cid">
                                            <?php while($stmt_category->fetch()){ ?>
                                                <option value="<?php echo $category_data['cid']; ?>"><?php echo $category_data['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Ссылка стрим (Stream Url)</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="radio_url" id="radio_url" placeholder="например : http://193.232.148.42:8000/v3_1" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <div class="font-12 ex1">Картинка ( jpg / png )</div>
                                    <div class="form-group">
                                        <input type="file" name="radio_image" id="radio_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" />
                                        <div class="div-error"><?php echo isset($error['radio_image']) ? $error['radio_image'] : '';?></div>
                                    </div>
                                    </div>                                

                                    <div class="col-sm-12">
                                    <button type="submit" name="btnAdd" class="btn bg-blue waves-effect pull-right ">ПРИМЕНИТЬ</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>