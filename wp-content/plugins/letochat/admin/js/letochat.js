(function ($) {
    "use strict";

    $(document).ready(function() {
        $('#save-leto-chat-data').on('click', function() {
            let data = {
                action: 'letochat_check_data',
                security: ajax_letochat_admin_object.ajax_nonce,
            };

            $.ajax({
                type: 'POST',
                url: ajax_letochat_admin_object.ajax_url,
                data: $('#leto-chat-data-form').serialize() + '&' + $.param(data),
                dataType: 'json',
                beforeSend: function () {
                    Notiflix.Loading.Standard(ajax_letochat_admin_object.messages.please_wait);
                },
                success: function (data) {
                    // Notiflix
                },
                complete: function (data) {
                    Notiflix.Loading.Remove();
                },
            });
        });
    });
})(jQuery);
