<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Language Translate Utility</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141896396-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-141896396-1');
    </script>

    <style type="text/css">
        .error {
            color: red;
            position: absolute;
            bottom: -85%;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container">
        @if ($count > 0)
        {{-- action="langtranslate/submitFile" method="post"     --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="row mt-5">
                        <form class='text-center w-100 mb-5'>
                            <h6 class='text-info d-block mb-2'><b>Hey we need your help, If you like this service then please support the project.</b></h6>
                            <script src="https://checkout.razorpay.com/v1/payment-button.js" data-payment_button_id="pl_IRSOly7scBVNSE" async> </script>
                        </form>
                        <div class="col-md-8 offset-md-2 text-center mb-3">
                            Expecting .json file with key-value pair like <code>{'home':'Casa','info':'información'} </code><br />(only
                            .json, less than 500kb)
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div id='validationFlowForms'>
                        <!-- send otp -->
                        <form class='' id="emailVerifyForm" method="post" onsubmit="return sendOtp()" novalidate>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="abc@gmail.com" required="true"/>
                                <div class="input-group-append">
                                    <button class="btn btn-md btn-primary" type="submit" id="btnSendOtp">
                                        Send OTP
                                        <i class="fa fa-gear fa-spin loader" style="font-size:18px"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- send otp -->

                        <!-- Verify Email -->
                        <form id="verifyOtpForm" class='' method="post" onsubmit="return verifyOtp()" novalidate style="display:none;">
                            <div class="input-group" >
                                <input type="otp" class="form-control" id="otp" name="otp" placeholder="Enter OTP" require>
                                <div class="input-group-append">
                                    <button type="submit" class="btn  btn-primary" id="btnSendOtp">
                                        Verify <i class="fa fa-gear fa-spin loader" style="font-size:18px"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- Verify Email -->
                        <hr class="mb-5 mt-5">
                    </div>
                    <!-- Translation flow forms and donate button -->
                    <div id='translationFlowWrapper'>
                        <div class="custom-file mb-3" id="divFileInputHolder">
                            <input type="file" class="custom-file-input" id="filelangjson" name="filelangjson">
                            <label class="custom-file-label" for="filelangjson">Choose Json file</label>
                            <!-- <small class="text-muted">considering passphrase is blank</small> -->
                        </div>
                        <div class="mb-3" id="divLinkHolder">
                            <label for="jsonfilelink">Selected Json file</label>
                            <br />
                            <a href="javascript:void(0)" id="jsonfilelink" download>File Download</a>
                            <button type="button" class="close" id="btn-close-filelink">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="langForm" class='row d-flex justify-content-center' method="post" onsubmit="return onSubmitFormData()" novalidate>
                            <div class="mb-3 col-md-6">
                                <label for="selectFrom">From:</label>
                                <select id="selectFrom" name="from" class="custom-select d-block w-100"></select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="selectTo">To:</label>
                                <select id="selectTo" name="to" class="custom-select d-block w-100">
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-formsubmit" id="btnsendpush">
                                        Submit
                                        <i class="fa fa-gear fa-spin loader" style="font-size:18px"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row mt-3 justify-content-center d-flex">
                            <p style="text-align:center;">
                                If you find an issue or need any help feel free to contact us on below email : <a href='mailto:info@logisticinfotech.com'>info@logisticinfotech.com</a>
                            </p>
                        </div>
                    </div>
                    <!-- Translation flow forms and donate button -->
                </div>
            </div>
        @else
            <h1>Sorry we are facing some issues</h1>
            <p>please try again after few days</p>
        @endif

    </div>

</body>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": -1,
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    var url = "langtranslate";

    var selectFrom = document.getElementById('selectFrom');
    var selectTo = document.getElementById('selectTo');
    var email = document.getElementById("email");
    var otp = document.getElementById("otp");
    var fileName = '';

    $(document).ready(function() {
        $("#divLinkHolder").hide();
        $('#translationFlowWrapper input,select').prop('disabled', true)
        $("#btnsendpush").prop('disabled', true);

        $("#langForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				}
        }});

        getLanguages();

        $("#filelangjson").change(function(e) {
            var file = this.files[0];
            var formData = new FormData();
            if(!file){
              return;
            }
            var filePath = $(this).val();
            var extension = filePath.substring(filePath.indexOf('.') + 1);
            if(extension != "json") {
                toastr.error("Please select json file");
                return;
            }
            formData.append('filelangjson', file);
            $.ajax({
                url: url + '/langFileUpload',
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                xhrFields: {
                    withCredentials: true
                },
                beforeSend: function() {
                    //$("#preview").fadeOut();
                    // $("#err").fadeOut();
                },
                success: function(data) {
                    console.log("success ", data)
                    fileName = data.langfilename;
                    updateUiIfLangFileInSession(data);
                },
                error: function(e) {
                    console.log(e.responseJSON.error.join());
                    toastr.error(e.responseJSON.error
                        .join());
                }
            });
        });

        $("#btn-close-filelink").click(function(e) {
            $("#divLinkHolder").hide();
            $("#divFileInputHolder").show();
        });

    });

    function updateUiIfLangFileInSession(data) {
        var jsonfilelink = data.langfilelink;
        var langfilename = data.langfilename;
        $("#jsonfilelink").attr("href", jsonfilelink);
        $("#jsonfilelink").html(langfilename);
        $("#divLinkHolder").show();
        $("#divFileInputHolder").hide();

        $("#btnsendpush").prop('disabled', false);
    }


    function getLanguages(ui = 'en') {
        $('.loader').show();
        $.ajax({
            url: url +  '/getlanguages',
            type: 'GET',
            headers: {},
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                // console.log('success data', data);
                fileName = data.langfilename;
                // data = JSON.parse(data);
                if (data) {
                    appendLanguageOptions(data.languages);
                }
                if (data.langfilename) {
                    updateUiIfLangFileInSession(data);
                }
                $('.loader').hide();
            },
            error: function(e) {
                // console.log(e);
                toastr.error(e.responseJSON.error.join());
                $('.loader').hide();
            }
        });
    }

    function appendLanguageOptions(objLanguage) {
        // console.log(objLanguage);
        for (const key in objLanguage) {
            if (objLanguage.hasOwnProperty(key)) {
                selectFrom.add(new Option(objLanguage[key], key, false, (key ==
                    'en' ? true : false)));
                selectTo.add(new Option(objLanguage[key], key));
            }
        }
    }

    function onSubmitFormData() {
        if (!$("#langForm").valid()) {
            return;
        }

        $('.loader').show();
        $("#btnsendpush").prop('disabled', true);

        var formData = new FormData();

        formData.append('from', selectFrom.value);
        formData.append('to', selectTo.value);
        formData.append('email', email.value);
        formData.append('fileName', fileName);

        // console.log(fileToUpload, fileName, selectFrom.value, selectTo.value);

        $.ajax({
            type: 'POST',
            url: url + '/submitTranslateRequest',
            cache       : false,
            contentType : false,
            processData : false,
            data: formData,
            xhrFields: {
                withCredentials: true
            },
            success: function(res) {

                $("#btnsendpush").attr('disabled', false);
                $('.loader').hide();
                if (res.info) {
                    toastr.info(res?.info);
                } else {
                    toastr.success(res?.message || "Thank you for using our service. we will send you email when its ready.");
                }

            },
            error: function(xhr, status, error) {
                $("#btnsendpush").attr('disabled', false);
                $('.loader').hide();
                toastr.error(xhr.responseJSON.error);
            }
        });
        return false;
    }

    function sendOtp() {
        if (!$("#emailVerifyForm").valid()) {
            return false;
        }

        $('.loader').show();
        $("#btnSendOtp").prop('disabled', true);

        var formData = new FormData();
        formData.append('email', email.value);

        $.ajax({
            type: 'POST',
            url: url + '/send-otp',
            cache       : false,
            contentType : false,
            processData : false,
            data: formData,
            xhrFields: {
                withCredentials: true
            },
            success: function(res) {
                $("#btnSendOtp").attr('disabled', false);
                $('.loader').hide();
                if (res.type === 'alreadyVerified') {
                    $('#translationFlowWrapper input,select').prop('disabled', false)
                    $("#validationFlowForms").hide();
                } else if(res.type === 'notVerified') {
                    $("#verifyOtpForm").show();
                }
                // console.log(res)
                toastr.success(res.message);
            },
            error: function(xhr, status, error) {
                $("#btnSendOtp").attr('disabled', false);
                $('.loader').hide();
                toastr.error(xhr.responseJSON.error);
            }
        });
        return false;
    }

    function verifyOtp() {
        if (!$("#verifyOtpForm").valid()) {
            return false;
        }

        $('.loader').show();
        $("#btnSendOtp").prop('disabled', true);

        var formData = new FormData();

        formData.append('email', email.value);
        formData.append('otp', otp.value);
        $.ajax({
            type: 'POST',
            url: url + '/verify-otp',
            cache       : false,
            contentType : false,
            processData : false,
            data: formData,
            xhrFields: {
                withCredentials: true
            },
            success: function(res) {
                $("#btnSendOtp").attr('disabled', false);
                $('.loader').hide();
                $('#translationFlowWrapper input,select').prop('disabled', false)
                $("#validationFlowForms").hide();
                // console.log(res)
                toastr.success(res.message);
            },
            error: function(xhr, status, error) {
                $("#btnSendOtp").attr('disabled', false);
                $('.loader').hide();
                toastr.error(xhr.responseJSON.error);
            }
        });
        return false;
    }

</script>

</html>