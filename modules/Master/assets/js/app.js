#require js/jquery.js
#require js/jquery-ui.min.js
#require js/jquery.ui.touch-punch.min.js
#require js/blueimp/tmpl.js
#require js/blueimp/gallery.js
#require js/bootstrap/affix.js
#require js/bootstrap/alert.js
#require js/bootstrap/button.js
#require js/bootstrap/carousel.js
#require js/bootstrap/collapse.js
#require js/bootstrap/dropdown.js
#require js/bootstrap/modal.js
#require js/bootstrap/tooltip.js
#require js/bootstrap/popover.js
#require js/bootstrap/tab.js
#require js/bootstrap/transition.js
#require js/xing/wysihtml5-0.3.0.min.js
#require js/jhollingworth/bootstrap-wysihtml5.js
#require js/bootbox.js
#require js/jquery.autosize.min.js
#require js/jquery.dataTables.min.js
#require js/dataTables.bootstrap.min.js
#require js/dataTables.fixedHeader.min.js
#require js/bootstrap-multiselect.min.js

#require js/bootstrap-toggle.min.js

#require js/blueimp/js/vendor/jquery.ui.widget.js
#require js/blueimp/js/jquery.iframe-transport.js
#require js/blueimp/js/jquery.fileupload.js

#require js/tool.js


(function ($) {
    $(function () {

      $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
      });
      
      $(document.body).on('click', '.disabled :input', function (e) {
        e.stopPropagation();
        e.preventDefault();
      });

      if ($('#auto_submit').length) {
        console.log('check');
        $('#auto_submit').submit();
      }

    });
})(jQuery);