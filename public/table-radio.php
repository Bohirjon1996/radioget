<?php
	include 'functions.php';
	include 'fcm.php';
?>

	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if (empty($keyword)) {
			$sql_query = "SELECT id, radio_name, radio_image, radio_url, category_name FROM tbl_radio m, tbl_category c
					WHERE m.category_id = c.cid  
					ORDER BY m.id DESC";
		} else {
			$sql_query = "SELECT id, radio_name, radio_image, radio_url, category_name FROM tbl_radio m, tbl_category c
					WHERE m.category_id = c.cid AND radio_name LIKE ? 
					ORDER BY m.id DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if ($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if (!empty($keyword)) {
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result( 
					$data['id'],
					$data['radio_name'],
					$data['radio_image'],
					$data['radio_url'],
					$data['category_name']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page) {
			$from 	= ($page * $offset) - $offset;
		} else {
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if (empty($keyword)) {
			$sql_query = "SELECT id, radio_name, radio_image, radio_url, category_name FROM tbl_radio m, tbl_category c
					WHERE m.category_id = c.cid  
					ORDER BY m.id DESC LIMIT ?, ?";
		} else {
			$sql_query = "SELECT id, radio_name, radio_image, radio_url, category_name FROM tbl_radio m, tbl_category c
					WHERE m.category_id = c.cid AND radio_name LIKE ? 
					ORDER BY m.id DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if ($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if (empty($keyword)) {
				$stmt_paging ->bind_param('ss', $from, $offset);
			} else {
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result(
				$data['id'],
				$data['radio_name'],
				$data['radio_image'],
				$data['radio_url'],
				$data['category_name']
			);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if ($total_records_paging == 0) {
	
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Главная</a></li>
            <li class="active">РАДИОСТАНЦИИ</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Радиостанции</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-radio.php"><button type="button" class="btn bg-blue waves-effect">ДОБАВИТЬ РАДИО</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
	                        
	                        <form method="get">
	                        	<div class="col-sm-10">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="keyword" placeholder="Поиск по названию...">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
					                <button type="submit" name="btnSearch" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">поиск</i></button>
								</div>
							</form>
										
							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th>Название радио</th>
										<th>Картинка радио</th>
										<th>Ссылка (Stream Url)</th>
										<th>Категория</th>
										<th>Действие</th>
									</tr>
								</thead>

								
							</table>

							<div class="col-sm-10">Извините, ничего не найдено.</div>

						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

	<?php 
		// otherwise, show data
		} else {
			$row_number = $from + 1;
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Главная</a></li>
            <li class="active">Радиостанции</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Радиостанции</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-radio.php"><button type="button" class="btn bg-blue waves-effect">ДОБАВИТЬ РАДИО</button></a>
                            </div>
                            <br>
                        </div>

                        <div class="body table-responsive">
	                        
	                        <form method="get">
	                        	<div class="col-sm-10">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="keyword" placeholder="Поиск по названию...">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
					                <button type="submit" name="btnSearch" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">поиск</i></button>
								</div>
							</form>
										
							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th>Название радио</th>
										<th>Картинка радио</th>
										<th>Ссылка (Stream Url)</th>
										<th>Категория</th>
										<th><center>Действие</center></th>
									</tr>
								</thead>

								<?php 
									while ($stmt_paging->fetch()) { ?>
										<tr>
											<td><?php echo $data['radio_name'];?></td>
							            	<td><img src="upload/<?php echo $data['radio_image'];?>" height="48px" width="48px"/></td>
											<td>
												<?php
                                                    $value = $data['radio_url'];
                                                    if (strlen($value) > 50)
                                                    $value = substr($value, 0, 47) . '...';
                                                    echo $value;													
												?>
											</td>
											<td><?php echo $data['category_name'];?></td>
											<td><center>

									            <a href="edit-radio.php?id=<?php echo $data['id'];?>">
									                <i class="material-icons">mode_edit</i>
									            </a>
									                        
									            <a href="delete-radio.php?id=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to delete this radio?')" >
									                <i class="material-icons">delete</i>
									            </a></center>
									        </td>
										</tr>
								<?php 
									}
								?>
							</table>

							<h4><?php $function->doPages($offset, 'manage-radio.php', '', $total_records, $keyword); ?></h4>
							<?php 
								}
							?>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>