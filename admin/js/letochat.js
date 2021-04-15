(function ($) {
    "use strict";

    if (typeof navigator !== 'undefined' && typeof navigator.clipboard !== 'undefined' && typeof navigator.clipboard.readText !== 'undefined') {
        let pasteDataButton = $("#paste-data");

        pasteDataButton.show();

        pasteDataButton.click(function(e) {
            e.preventDefault();

            navigator.clipboard.readText().then(function(message) {
                populateLetoChatKeys(message);
            });
        });
    }

    $('form#leto-chat-data-form input[type=text]').on('paste', function (e) {
        e.preventDefault();

        let pasteData = e.originalEvent.clipboardData.getData('text');

        populateLetoChatKeys(pasteData);
    });

    let channelId = $('#channel-id').val();
    let channelSecret = $('#channel-secret').val();
    let authSecret = $('#auth-secret').val();

    if (channelId !== '' && channelSecret !== '' && authSecret !== '') {
        connectToLetoChatAjax();
    }

    $("form#leto-chat-data-form").on('submit', function (e) {
        e.preventDefault();

        $(this).validate({
            rules: {
                field: {
                    required: true,
                },
            },
        });

        if (!$(this).valid()) {
            return false;
        }

        connectToLetoChatAjax();
    });

    $('#enable-widget').change( function() {
       let status = $('#enable-widget:checked').val();

       if (status === undefined) {
           status = 'off';
       } else if (status === 'true') {
           status = 'on';
       }

        switcherAction('enable_widget', status);
    });

    $('#visible-for-admins').change( function() {
        let status = $('#visible-for-admins:checked').val();

        if (status === undefined) {
            status = 'off';
        } else if (status === 'true') {
            status = 'on';
        }

        switcherAction('visible_for_admins', status);
    });

    function populateLetoChatKeys(pasteData) {
        if (pasteData.indexOf('letochat:') >= 0) {
            pasteData = pasteData.replace('letochat:', '');

            let keys = pasteData.split(',');

            $('#channel-id').val(keys[0]);
            $('#channel-secret').val(keys[1]);
            $('#auth-secret').val(keys[2]);
        } else {
            $(this).val(pasteData);
        }
    }

    function connectToLetoChatAjax() {
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
                let statusElement = $('.toplevel_page_letochat').find('#status');
                statusElement.removeClass().addClass('alert');

                if (data.isSuccess === true) {
                    statusElement.addClass('alert-success');
                    statusElement.html(data.message);
                } else {
                    statusElement.addClass('alert-danger');
                    statusElement.html(data.message);
                }

                statusElement.css('display', 'block');
            },
            complete: function (data) {
                Notiflix.Loading.Remove();
            },
        });
    }

    function switcherAction(type, status) {
        $.ajax({
            type: 'POST',
            url: ajax_letochat_admin_object.ajax_url,
            data: {
                action: 'letochat_switcher',
                security: ajax_letochat_admin_object.ajax_nonce,
                type: type,
                status: status,
            },
            dataType: 'json',
            beforeSend: function () {
                Notiflix.Loading.Standard(ajax_letochat_admin_object.messages.please_wait);
            },
            success: function (data) {
                let statusElement = $('.toplevel_page_letochat').find('#status');
                statusElement.removeClass().addClass('alert');

                if (data.isSuccess === true) {
                    statusElement.addClass('alert-success');
                    statusElement.html(data.message);
                } else {
                    statusElement.addClass('alert-danger');
                    statusElement.html(data.message);
                }

                statusElement.css('display', 'block');
            },
            complete: function (data) {
                Notiflix.Loading.Remove();
            },
        });
    }

    $.extend($.validator.messages, {
        required: ajax_letochat_admin_object.messages.required,
    });
})(jQuery);
