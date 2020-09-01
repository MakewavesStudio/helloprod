var saidHelloMakewaves = false;
var typing;
var typingDelay = 250;
var loadingDelay = 1500;

(function ($) {

    // On Document Ready
    $(document).ready(function () {
        //alert('DOM Ready');

        // Burger menu
        $('.c-header__burger').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).find('div.burger').toggleClass('open');
            $('.c-header__menulayout').toggleClass('open');
        });


        /* ScrollTo */
        $('.js-scrollTo').on('click', function(e) { // Au clic sur un élément
            e.preventDefault();
            var page = $(this).children('a').attr('href'); // Page cible
            console.log(page);
            var speed = 750; // Durée de l'animation (en ms)
            $('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
            return false;
        });


	    // Button Menu : Open/Close Modale
        $(document).on('click', '#button-menu', function(e){
            e.preventDefault();
            toggle_modale('#modale-menu');
        });

        // Button Search : Open/Close Modale
        // $(document).on('click', '#button-search', function(e){
        //     e.preventDefault();
        //     toggle_modale('#button-search','#modale-search');
        // });

        // Fermeture Modal Box Search
        $(document).on('click', '.close', function(e){
            e.preventDefault();
            $(this).parent('.modale').removeClass('open');
        });

        // Toggle Modale : Global Fonction
        function toggle_modale(button_id,modal_id){
            if ($(button_id).hasClass('open')){ // Close
                $('.modale').each(function(){$(this).removeClass('open');});
                $('.button-modale').each(function(){$(this).removeClass('open');});
                $(button_id).removeClass('open');
            }else{ // Open
                $('.modale').each(function(){$(this).removeClass('open');});
                $('.button-modale').each(function(){$(this).removeClass('open');});
                $(modal_id).addClass('open');
                $(button_id).addClass('open');
            }
        }

        // Toggle SubMenu : Global Fonction
        function toggle_submenu(button_id,modal_id){
            if (!window.matchMedia("(min-width: 1260px)").matches) {
                if ($(button_id).hasClass('open')){ // Close
                    $(button_id).removeClass('open');
                    $(modal_id).removeClass('open');
                }else{ // Open
                    // Other Menu
                    if (!window.matchMedia("(min-width: 1260px)").matches) {
                        $('.sub-menu').each(function(){$(this).removeClass('open');});
                        $('.sub-menu').parent('li').each(function(){$(this).removeClass('open');});
                    }
                    $(modal_id).addClass('open');
                    $(button_id).addClass('open');
                }
            }
        }

        // Toggle Sub Menu : Only On Mobile Screens
        if (!window.matchMedia("(min-width: 1260px)").matches) {
            var current_id = '';
            $('.menu > .menu-item-has-children > a').on('click',function(e){
                e.preventDefault();
                current_id = $(this).parent().attr('id');

                if ($('#'+current_id).hasClass('open')){
                    $('#'+current_id).removeClass('open');
                    $('#'+current_id).parent().removeClass('open');
                    $('#'+current_id).parent().parent().removeClass('open');
                    $('#'+current_id).children('.sub-menu').removeClass('open');
                }else{
                    // Hide or Show SubMenu
                    $('.menu > .menu-item').each(function(index){
                        $(this).removeClass('open');
                       if ($(this).attr('id') !== current_id){
                           $(this).children('.sub-menu').removeClass('open');
                       }else{
                           if ($(this).hasClass('open')){

                               $(this).parent().removeClass('open');
                               $(this).parent().parent().removeClass('open');
                           }else {
                                $(this).addClass('open');
                                $(this).children('.sub-menu').addClass('open');
                            }

                       }
                    });
                }
            });

            $('.menu > .menu-item-has-children > .sub-menu > .menu-item-has-children > a').on('click',function(e){
                e.preventDefault();
                current_id = $(this).parent().attr('id');

                if ($('#'+current_id).hasClass('open')){
                    $('#'+current_id).removeClass('open');

                    $('#'+current_id).children('.sub-menu').removeClass('open');
                }else{
                    // Hide or Show SubMenu
                    $('.menu > .menu-item-has-children > .sub-menu > .menu-item').each(function(index){
                       if ($(this).attr('id') !== current_id){
                           $(this).children('.sub-menu').removeClass('open');
                       }else{
                           $(this).addClass('open');
                           $(this).children('.sub-menu').addClass('open');
                       }
                    });
                }
            });
        }

        // Contact Form Submit Process
        //////////////////////////////
        if ($('#c-validator').length > 0) {

            var busy = null;

            // Prevent the form from doing a submit
            $(document).on('submit','#contact-form',function (e) {
                e.preventDefault();
                return false;
            });

            // Input Submit On Click
            $(document).on('click', '#c-validator', function (e) {

                // Flush Existing Notifications
                $('#contact-form-notification').html('');

                // Initialize Properties
                var error = false;
                var form = $(this).closest('form');

                // Force And Check Native HTML5 Validaton
                if (( typeof(form[0].checkValidity) === "function" ) && !form[0].checkValidity()) {
                    return;
                }

                // Avoid Nervous Clicks !
                if ( busy )
                    busy.abort();

                // Ajax Submit Form
                busy = $.ajax({
                    type: "post", url: ajaxurl, data: form.serialize(),
                    beforeSend: function () { },
                    success: function (response) {
                        if (response.success === true) {
                            form[0].reset();
                            var msg_success = '<p class="success">Votre message a bien été envoyé.</p>';
                            $('#contact-form-notification').html(msg_success);
                        } else {
                            var msg_success = '<p class="error">Votre message n\'a pas pu être envoyé.</p>';
                            $('#contact-form-notification').html(msg_success);
                        }
                    },
                    error: function (response) { alert('error'); },
                    complete: function () { }
                });

                return false;

            });
        }

    });



    // ON DOCUMENT LOADED
    $(window).load(function () {
        //alert('Window loaded');
    });


    var mouseDown, innerX, clickedTimer;


})(jQuery);
