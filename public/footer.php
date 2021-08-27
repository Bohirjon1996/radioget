            <!-- Large Size -->
            <div class="modal fade" id="modal-server-key" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="largeModalLabel">Где взять ключ сервера Firebase?</h4>
                        </div>
                        <div class="modal-body">
                           <p>API Key сервера Firebase предназначен для идентификации вашего приложения Firebase. Чтобы получить ваш ключ API Key сервера, перейдите в консоль Firebase, выберите проект и перейдите в Настройки, выберите вкладку Cloud Messaging и скопируйте ключ сервера.</p>
                            <img src="assets/images/fcm-server-key.jpg" class="img-responsive">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">ОК</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Large Size -->
            <div class="modal fade" id="modal-api-key" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="largeModalLabel">Куда нужно вставить API Key?</h4>
                        </div>
                        <div class="modal-body">
                            <ol>
                                <li>Для безопасности приложения обновите строку API KEY</li>
                                <li>Откройте свой проект в Android Studio.</li>
                                <li>В админке нажмите на Поменять API Key, сгенерируйте и примените новый API Key.</li>
                                <li>В Android Studio перейдите в app - java - ваш проект - Config.java и вставьте сгенерированный API Key. <img src="assets/images/api_key.jpg" class="img-responsive"></li>
                            </ol>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

     <!-- Wait Me Plugin Js -->
    <script src="assets/plugins/waitme/waitMe.js"></script>

    <!-- Jquery Core Js -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="assets/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    
    <!-- Latest compiled and minified JavaScript -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/i18n/defaults-*.min.js"></script> -->


    <!-- Slimscroll Plugin Js -->
    <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="assets/plugins/node-waves/waves.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="assets/plugins/autosize/autosize.js"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="assets/plugins/jquery-validation/jquery.validate.js"></script>

    <script src="assets/js/dropify.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <!-- Custom Js -->
    <script src="assets/js/admin.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/js/pages/forms/form-validation.js"></script>

    <!-- Demo Js -->
    <script src="assets/js/demo.js"></script>

    <script>
        $(document).ready(function(){
            // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove:  'Supprimer',
                    error:   'Désolé, le fichier trop volumineux'
                }
            });

            $('.dropify-image').dropify({
                messages: {
                    default : '<center>Drag and drop a image here or click</center>',
                    error   : 'Ooops, something wrong appended.'
                },
                error: {
                    'fileSize': '<center>The file size is too big broo ({{ value }} max).</center>',
                    'minWidth': '<center>The image width is too small ({{ value }}}px min).</center>',
                    'maxWidth': '<center>The image width is too big ({{ value }}}px max).</center>',
                    'minHeight': '<center>The image height is too small ({{ value }}}px min).</center>',
                    'maxHeight': '<center>The image height is too big ({{ value }}px max).</center>',
                    'imageFormat': '<center>The image format is not allowed ({{ value }} only).</center>',
                    'fileExtension': '<center>The file is not allowed ({{ value }} only).</center>'
                },
            });

            $('.dropify-video').dropify({
                messages: {
                    default: '<center>Drag and drop a video here or click</center>'
                }
            });

            $('.dropify-notification').dropify({
                messages: {
                    default : '<center>Drag and drop a image here or click<br>(Optional)</center>',
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element){
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element){
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element){
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e){
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>

    <!-- <footer class="footer">
        <div class="container">
            <span class="right span-padding">Copyright © 2017 
                <a href="https://codecanyon.net/user/solodroid/portfolio" target="_blank">Solodroid Developer</a> All rights reserved.</span>
        </div>
    </footer> -->

</body>

</html>