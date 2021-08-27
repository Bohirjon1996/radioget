<?php include_once('functions.php'); ?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Главная</a></li>
            <li class="active">Пуш Уведомления</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form role="form" action="send-notification.php" method="post" id="form_validation">
                    <div class="card">
                        <div class="header">
                            <h2>ПУШ УВЕДОМЛЕНИЯ</h2>
                        </div>
                        <div class="body">

                            <div class="row clearfix">

                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" required>
                                            <label class="form-label" for="title">Заголовок</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="message" id="message" required>
                                            <label class="form-label">Сообщение</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="link" id="link">
                                            <label class="form-label">Ссылка (URL)</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-12">
                                    <button class="btn bg-blue waves-effect pull-right" type="submit">ОТПРАВИТЬ УВЕДОМЛЕНИЕ</button>
                                </div>


                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>