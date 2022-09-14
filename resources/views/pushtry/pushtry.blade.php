<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Push Try Utility</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style type="text/css">
        .error {
            color: red;
        }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141896396-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-141896396-1');
    </script>

</head>

<body class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- <h4 class="mb-3">iOS Push Try</h4> -->
                <div class="py-5 text-center">
                    <img class="d-block mx-auto mb-4" src="{{asset('pushtryassets/apnstry.png')}}"
                        alt="" width="72" height="72">
                    <h2>iOS Push Try</h2>
                </div>

                <div class="custom-file mb-3" id="divFileInputHolder">
                    <input type="file" class="custom-file-input" id="fileapns"
                        name="fileapns">
                    <label class="custom-file-label" for="fileapns">Choose PEM
                        or P12 or P8
                        file</label>
                    <small class="text-muted">considering passphrase is blank</small>

                </div>
                <div class="mb-3" id="divLinkHolder">
                    <label for="pemfilelink">Selected Pem file</label>
                    <br />
                    <a href="#" id="pemfilelink" download>Pem File Download</a>
                    <button type="button" class="close" id="btn-close-filelink">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br />
                    <small class="text-muted">This file will be used to send
                        notificaiton</small>
                </div>

                <hr class="mb-4">

                <form class="needs-validation" id="form-apns" onsubmit="return sendApnsPush()" novalidate>

                    <input type="hidden" id="hiddenpemfilelink" name="hiddenpemfilelink">
                    <input type="hidden" id="hiddenisp8" name="hiddenisp8">
                    <div class="row divp8holder">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">KeyID</label>
                            <input type="text" class="form-control" id="keyid"
                                name="keyid" placeholder="Q8G44BM3Z5" value=""
                                required>
                            <div class="invalid-feedback">
                                required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">TeamID</label>
                            <input type="text" class="form-control" id="teamid"
                                name="teamid" placeholder="JUGDE9K6Z2" value=""
                                required>
                            <div class="invalid-feedback">
                                required.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 divp8holder">
                        <label for="devicetokens">App Bundle Id</label>
                        <input type="text" class="form-control" id="appid" name="appid"
                            placeholder="com.company.app" required>
                    </div>

                    <div class="mb-3">
                        <label for="devicetokens">Multiple Device Tokens
                            seprated by comma</label>
                        <input type="text" class="form-control" id="devicetokens"
                            name="devicetokens" placeholder="token1, token2"
                            required>
                    </div>

                    <hr class="mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="isproduction"
                            name="isproduction">
                        <label class="custom-control-label" for="isproduction">Is
                            Production</label>
                    </div>

                    <hr class="mb-4">

                    <!-- <h4 class="mb-3">Message Type</h4> -->

                    <!-- <div class="d-block my-3">
                        <div class="custom-control custom-radio float-left">
                            <input id="credit" name="isProduction" type="radio"
                                class="custom-control-input" checked required>
                            <label class="custom-control-label" for="credit">Text</label>
                        </div>
                        <div class="custom-control custom-radio float-left ml-4">
                            <input id="debit" name="isProduction" type="radio"
                                class="custom-control-input" required>
                            <label class="custom-control-label" for="debit">Json</label>
                        </div>
                    </div> -->
                    <div class="mb-3">
                        <label for="message">Message:</label>
                        <textarea class="form-control" rows="2" id="message"
                            name="message" required></textarea>
                    </div>

                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit" id="btnsendpush">Send
                        Push</button>
                </form>
            </div>
        </div>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>

<script type="text/javascript">
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
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    var url = "https://www.logisticinfotech.co.in/pushtry";
    $(document).ready(function() {
        console.log("ready!");
        getPemFileFromSession();

        $("#divLinkHolder").hide();
        $(".divp8holder").hide();

        $('.loader').hide();

        $("#form-apns").validate();
        $("#btnsendpush").prop('disabled', true);

        $("#fileapns").change(function(e) {
            var file = this.files[0];
            console.log(file);
            var formData = new FormData();
            if(!file){
              return;
            }
            formData.append('fileapns', file);
            $.ajax({
                url: url + '/apnsFileUpload',
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
                    updateUiIfPemFileInSession(data);
                    // if (data == 'invalid') {
                    //     // invalid file format.
                    //     $("#err").html(
                    //         "Invalid File !").fadeIn();
                    // } else {
                    //     // view uploaded file.
                    //     $("#preview").html(data).fadeIn();
                    //     $("#form")[0].reset();
                    // }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }
            });
        });



        $("#btn-close-filelink").click(function(e) {
            $("#divLinkHolder").hide();
            $("#divFileInputHolder").show();
        });
    });

    function getPemFileFromSession() {
        $.ajax({
            url: url + '/getPemFileFromSession',
            type: "GET",
            xhrFields: {
                withCredentials: true
            },
            success: function(data) {
                console.log("getPemFileFromSession success ", data);
                updateUiIfPemFileInSession(data);

                if (data.devicetokens) {
                    $("#devicetokens").val(data.devicetokens);
                }
                if (data.message) {
                    $("#message").val(data.message);
                }
                if (data.isproduction == "on") {
                    $("#isproduction").prop('checked', true);
                }
                if (data.keyid) {
                    $("#keyid").val(data.keyid);
                }
                if (data.teamid) {
                    $("#teamid").val(data.teamid);
                }
                if (data.appid) {
                    $("#appid").val(data.appid);
                }
            },
            error: function(e) {
                console.log("getPemFileFromSession error", e);
            }
        });
    }

    function updateUiIfPemFileInSession(data) {
        var pemfilelink = data.pemfilelinkpathonly;
        var pemfilename = data.pemfilename;
        var hiddenisp8  = data.isp8;

        $("#hiddenpemfilelink").val(pemfilelink);
        $("#hiddenisp8").val(hiddenisp8);
        $("#pemfilelink").attr("href",
            pemfilelink);
        $("#pemfilelink").html(
            pemfilename +
            " Download");
        $("#divLinkHolder").show();
        $("#divFileInputHolder").hide();

        if (data.isp8) {
            $(".divp8holder").show();
        } else {
            $(".divp8holder").hide();
        }

        $("#btnsendpush").prop('disabled', false);
    }

    function sendApnsPush() {
        if (!$("#form-apns").valid()) {
            return;
        }
        $('.loader').show();
        $.ajax({
            url: url + '/sendApnsPush',
            type: "POST",
            xhrFields: {
                withCredentials: true
            },
            data: $("#form-apns").serialize(),
            success: function(data) {

                console.log("sendApnsPush success ", data);
                var alertClass = "alert-success";
                var msg = "";
                for (let index = 0; index < data.length; index++) {
                    const tokenResp = data[index];
                    if (tokenResp.status != 200) {
                        alertClass = "alert-danger";
                        toastr.error("<strong>" + tokenResp.reason +
                            "</strong>" + " " + tokenResp.token
                        );
                    } else {
                        toastr.success("<strong>Good</strong>" +
                            " " + tokenResp.token);
                    }

                }
                $('.loader').hide();
                // showSnackbar(msg, alertClass);
                // alert-warning
            },
            error: function(e) {
                reason = e.responseJSON.reason;
                token = e.responseJSON.token;
                toastr.error( "<strong>"+ reason +"</strong><br/> Token: "+ token);
                $('.loader').hide();

                $('.loader').hide();
            }
        });
        return false;
    }
</script>

</html>