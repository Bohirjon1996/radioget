<?php

  $sql_category = "SELECT COUNT(*) as num FROM tbl_category";
  $total_category = mysqli_query($connect, $sql_category);
  $total_category = mysqli_fetch_array($total_category);
  $total_category = $total_category['num'];

  $sql_news = "SELECT COUNT(*) as num FROM tbl_radio";
  $total_radio = mysqli_query($connect, $sql_news);
  $total_radio = mysqli_fetch_array($total_radio);
  $total_radio = $total_radio['num'];

?>

    <section class="content">

    <ol class="breadcrumb">
        <li><a href="dashboard.php">Главная</a></li>
        <li class="active">Основная</a></li>
    </ol>

        <div class="container-fluid">
             
             <div class="row">

                <a href="manage-category.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">КАТЕГОРИИ</div>
                            <div class="color-name"><i class="material-icons">people</i></div>
                            <div class="color-class-name">Total ( <?php echo $total_category; ?> ) Категорий</div>
                            <br>
                        </div>
                    </div>
                </a>

               <a href="manage-radio.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">РАДИОСТАНЦИИ</div>
                            <div class="color-name"><i class="material-icons">radio</i></div>
                            <div class="color-class-name">Total ( <?php echo $total_radio; ?> ) Радио</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="push-notification.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">ПУШ УВЕДОМЛЕНИЯ</div>
                            <div class="color-name"><i class="material-icons">notifications</i></div>
                            <div class="color-class-name">Уведомить пользователей</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="members.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">АДМИНИСТРАТОР</div>
                            <div class="color-name"><i class="material-icons">people</i></div>
                            <div class="color-class-name">Администраторы</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="settings.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">НАСТРОЙКИ</div>
                            <div class="color-name"><i class="material-icons">settings</i></div>
                            <div class="color-class-name">Настройки уведомлений и политика</div>
                            <br>
                        </div>
                    </div>
                </a>

            </div>
            
        </div>

    </section>