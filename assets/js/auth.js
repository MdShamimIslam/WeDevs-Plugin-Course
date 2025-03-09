jQuery(function ($){

    $("form#profile-form").on("submit", function(e){
        e.preventDefault();

        var button = $(this).find('button[type="submit"]');

        button.attr('disabled', 'disabled');

        $.post(
            simpleAuthAjax.ajax_url,
            $(this).serialize() + '&_wpnonce=' + simpleAuthAjax.nonce ,
            function (response){
                if (response?.success) {
                    $('#profile-update-message')
                        .html(response.data.message)
                        .removeClass('hidden')

                    setTimeout(() => {
                        $('#profile-update-message').addClass('hidden');
                    }, 3000);
                }

                button.removeAttr('disabled');
            }
        )
    });

    $("form#simple-auth-login-form").on("submit", function(e){
        e.preventDefault();

        var button = $(this).find('button[type="submit"]');

        button.attr('disabled', 'disabled');

        wp.ajax.post('simple-auth-login-form', $(this).serialize())
            .done(function(res){
                $('#login-message')
                .html(res?.message)
                .removeClass('hidden')
                .removeClass('error-message')
                .addClass('success-message')

                setTimeout(() => {
                  window.location.reload();  
                }, 2000);

            })
            .fail(function(error){
                $('#login-message')
                .html(error.message)
                .removeClass('hidden')
                .addClass('error-message')

                button.removeAttr('disabled');
            })
        
    });

})



// 1. jQuery(function($){})
// 2. $(function(){})
// 3. $(document).ready(function(){})