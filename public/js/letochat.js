(function ($) {
    "use strict";

    let letochatScriptElement = $('#letochat-script');

    $('body').on('added_to_cart', function(){
        $.ajax({
            type: 'POST',
            url: ajax_letochat_public_object.ajax_url,
            data: {
                action: 'update_chat_token',
                security: ajax_letochat_public_object.ajax_nonce,
            },
            dataType: 'json',
            beforeSend: function () {
                letochatScriptElement.empty();
            },
            success: function (data) {
                if (data.isSuccess === true) {
                    letochatScriptElement.html(data.chat);
                }
            },
            complete: function (data) {},
        });
    });
})(jQuery);
