// Init global variable
var $document  = $(document);

// Initialization
$document.ready(init);

function init() {
    //Init local variables
    var formValidation = false;
    var $validation_form  =  $(".validation-form");
    var $login_form  =  $("#loginform");
    var $register_form  =  $("#registerform");
    var $useremail_form = $("#useremail");
    var $contact_form = $("#contact-form");
    var $validation_form_input  =  $validation_form.find('input, textarea, select');
    var $login_btn  =  $("#loginbtn");
    var $register_btn  =  $("#registerbtn");
    var $terms_privacy  =  $("#terms-privacy");
    var $selectServer  =  $("#selectServer");
    var $send_success  =  $("#sendsuccess");
    var $valid_svg = $('<svg class="valid" viewBox="-1 -1 20 20"><path d="M0.3,8.5c0,0,5,4.4,6.3,8.1c1.9-8.8,7.7-16.3,7.7-16.3"></path></svg>');
    var $invalid_svg = $('<svg class="invalid" viewBox="-1 -1 20 20"><path d="M2,18.4c6-12.3,14.4-18,14.4-18"></path><path d="M0.2,2.2C8.8,7,16.1,16.7,16.1,16.7"></path></svg>');
    var $arrow_svg = $('<svg class="arrow" viewBox="-0.5 0 13 24"><polyline class="arrowBorder" points="12,0 12,2 2,12 12,22 12,24"></polyline><polyline class="arrowCover" points="13,2 3,12 13,22"></polyline></svg>');
    var $speech_bubble = $('<div class="speechBubble text-left"></div>');
    var $span  = $('<span></span>');

    $speech_bubble.prepend($arrow_svg);

    // Init EVENTS
    $validation_form_input.blur(blurInValidationInput);
    $validation_form_input.focus(focusInValidationInput);
    $login_form.submit(loginFormSubmit);
    $register_form.submit(registerFormSubmit);
    $terms_privacy.change(termsPrivacyCheck);
    $useremail_form.submit(userEmailFormSubmit);
    $contact_form.submit(contactFormSubmit);
    $selectServer.change(selectServer);



    function blurInValidationInput() {
        var validation = true;
        var error_text = '';
        var val = $(this).val().trim();
        var type = $(this).attr('name');
        var $textField  =  $(this).closest('.textField');
        var $validation  =  $textField.find('.validation');
        if(val.length === 0){
            $textField.removeClass('valid');
            validation = false;
            error_text = LANGUI_ERROR_T1;
        }else if(type == 'name' && val.length < 5){
            validation = false;
            error_text  = LANGUI_ERROR_T2;
        }else if(type == 'email' && !validateEmail(val)){
            validation = false;
            error_text  = LANGUI_ERROR_T3;
        }else if( (type == 'pwd' || type == 'password') && val.length < 6){
            validation = false;
            error_text  = LANGUI_ERROR_T4;
        }
        if(!validation){
            $textField.removeClass('valid');
            showValidationError($validation, error_text);
        }else{
            $validation.html($valid_svg.clone());
            $validation.fadeIn(0);
        }
        if(val.length > 0){
            $textField.addClass('valid');
        }
        formValidation = validation;
    }

    function focusInValidationInput() {
        var $validation  =  $(this).closest('.textField').find('.validation');
        $validation.empty();
        $validation.fadeOut(0)
    }

   function loginFormSubmit(e) {
       e.preventDefault();
        var $this  = $(this);
        $this.find('input, select').blur();
       $selectServer.change();
        if(!formValidation) return false;
        $login_btn.button('loading');
        var url = "login";

        $.ajax({
            type: "POST",
            url: url,
            data: $this.serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(data) {
            var errorState = data["errorState"];
            $login_btn.button('reset');
            if ( errorState > 0) {
                var error_text = data["error"];
                var $error_field;
                if(errorState == 1){
                    $error_field = $this.find('.username-field');
                }else if(errorState == 2){
                    $error_field = $this.find('.password-field');
                }else if(errorState == 3){
                    $error_field = $this.find('.server-field');
                }
                var $validation = $error_field.find('.validation');
                showValidationError($validation, error_text);
            } else {
                if (data["regdata"] == "") {
                    window.location.replace(data["url"]);
                } else {
                    $('#dr').val(data["regdata"]);
                    $('#server').val($('#selectServer').val());
                    $("#loginmodel").modal("show");
                }

            }
        });
    }
    
    function registerFormSubmit(e) {
        e.preventDefault();
        var $this  = $(this);
        $this.find('input').blur();
        $terms_privacy.change();
       
        if(!formValidation || !termsPrivacyValid) return false;
        $register_btn.button('loading');
        $.ajax({
            type: "POST",
            url: register_form_url,
            data: $this.serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(data) {
            $register_btn.button('reset');
            if (data["success"] == false) {
                console.log(data);
                data["err"].forEach(function (val,key) {
                    if(val !== "") {
                        var $error_field;
                        switch (key){
                            case 0:
                            case 3:
                                $error_field = $this.find('.username-field');
                                break;
                            case 1:
                                $error_field = $this.find('.email-field');
                                break;
                            case 2:
                                $error_field = $this.find('.password-field');
                                break;
                        }
                        var $validation = $error_field.find('.validation');
                        showValidationError($validation, val);
                    }

                })

            } else {
                $("#ruser").html(data["username"]);
                $("#remail").html(data["email"]);
                $("#registerSuccess").css("display", "block");
                $("#registerform").css("display", "none");

            }
        });
    }
    var termsPrivacyValid = false;

    function termsPrivacyCheck() {
        if(!$(this).prop('checked')) {
            $(this).closest('.textField').find('.speechBubble').removeClass('hidden')
            termsPrivacyValid = false;
        }else{
            $(this).closest('.textField').find('.speechBubble').addClass('hidden')
            termsPrivacyValid = true;
        }
    }
    function selectServer() {
        var $this = $(this);
        var value = $this.val()
        if (value) {
            var self_server =  server[value];
            var server_data =
                "<ul><li><span style='color:#964646;'>"+LANGUI_INDX_T20+": </span>" + self_server["start_date"] +
                "</li><li><span style='color:#964646'>"+LANGUI_INDX_T21+": </span>" + self_server["players_count"] +
                "</li><li><span style='color:#964646'>"+LANGUI_INDX_T22+": </span>" + self_server["speed"] +
                "</li><li><span style='color:#964646'>"+LANGUI_INDX_T23+": </span>" + self_server["end"] +
                "</li></ul>";

            $('#server-data').html(server_data);
            $("#server-data-content").fadeIn(200);
        }else{
            $("#server-data-content").fadeOut(0);
        }
    };

   function userEmailFormSubmit(e) {
        e.preventDefault();
       var $this  = $(this);
       $this.find('input').blur();
       if(!formValidation) return false;
        var url = "password";
        $.ajax({
            type: "POST",
            url: url,
            data: $this.serialize(),
            dataType: 'json',
            encode: true
        }).done(function(data) {
            if (data["success"] == false) {
                var $validation  = $this.find('.validation');
                var error_text = data['error'];
                showValidationError($validation, error_text);
            } else {
                if (data["success"] == true) {
                    $send_success.css("display", "block");
                }

            }
        });

    };
    
    function contactFormSubmit(e) {
        e.preventDefault();
        var $this  = $(this);
        $this.find('input, textarea').blur();
        if(!formValidation) return false;
        var url = "contact";
        $.ajax({
            type: "POST",
            url: url,
            data: $this.serialize(),
            dataType: 'json',
            encode: true
        }).done(function(data) {
            if (data["success"] == false) {
                var $validation  = $this.find('.validation');
                var error_text = data['error'];
                showValidationError($validation, error_text);
            } else {
                if (data["success"] == true) {

                }

            }
        });
    }

    function showValidationError($validation, error_text) {
        $validation.empty();
        var $span_clone =  $span.clone().text(error_text);
        var $speech_bubble_clone = $speech_bubble.clone().append($span_clone);
        $validation.append($invalid_svg.clone(),$speech_bubble_clone);
        $validation.fadeIn(0)
    }

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }





}

function setLang(a) {
    document.cookie = "lng=" + a + "; expires=Wed, 1 Jan 2250 00:00:00 GMT"
}


$(".panel-body").hover(function(){
    $(this).toggleClass('hover');

});