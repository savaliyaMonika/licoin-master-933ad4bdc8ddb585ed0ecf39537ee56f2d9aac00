<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SSL-CHECKER</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/sslchecker/custom.css') }}">
</head>

<body>
    <div class="h-100 w-100 d-flex flex-column">
        <div class="container d-flex flex-column flex-fill mb-5">
            <div id="checkSSLBox" class="border1 border-success d-flex flex-column align-items-center justify-content-center mb-4 h-100">
                <div>
                    <h1 class="pt-3 pb-3 text-center"><b>SSL-CHECKER</b></h1>
                </div>

                <form id="domainForm" class="w-100">
                    <div class="input-group">
                        <input type="text" id="serverhost" name="serverhost" class="form-control" placeholder="Server Host Name. ( www.logisticinfotech.com )" required pattern=".*\S+.*">
                        <div class="input-group-append">
                            <button class="btn btn-primary ml-3" id="check-ssl" type="submit">Check SSL</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="sslDetailsBox" class="border1 border-success flex-fill d-flex align-items-start justify-content-center">
                <div id="sslDetailsCard" class="card w-100 shadow-lg">

                </div>
            </div>
        </div>

        <footer class="text-center dnsheader p-2 text-white fixed-bottom">
            <b>Copyright © 2019 Logistic Infotech Pvt. Ltd. - All rights reserved.</b>
        </footer>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $(document).on('hidden.bs.modal', '#remindMeModal', function() {
                $('#reminderForm').trigger("reset");
            });

            function showToast(type, text) {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "progressBar": true,
                    "preventDuplicates": false,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "400",
                    "hideDuration": "1000",
                    "timeOut": "7000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr[type](text)
            }

            $(document).on("submit", "#domainForm", (e) => {
                e.preventDefault();

                var submitBtn = $(e.target).find('[type=submit]');
                submitBtn.attr("disabled", true);
                submitBtn.html("Checking...");

                $.ajax({
                    url: "{{ route('sslchecker.getDetails') }}",
                    type: "post",
                    data: $('#domainForm').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    success: function(result) {
                        if (result) {
                            let detailCard = $('#sslDetailsCard');
                            $("#checkSSLBox").removeClass("h-100")
                            // console.log(result);
                            detailCard.html(result.html);
                            detailCard.show(3000);
                        }
                    },
                    error: function(error) {
                        showToast('error', error.responseJSON.message);
                    },
                    complete: function(result) {
                        submitBtn.attr("disabled", false);
                        submitBtn.html("Check SSL");
                    }
                });
            });

            $(document).on("submit", "#reminderForm", (e) => {
                e.preventDefault();
                var form = $("#reminderForm");

                $.ajax({
                    type: "POST",
                    url: "{{route('sslchecker.subscribe')}}",
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    data: form.serialize(), // serializes the form's elements.
                    success: function(result) {
                        if (result.code == 200) {
                            showToast('success', result.message);
                            $("#remindMeModal").modal('hide');
                        }
                    },
                    error: function(error) {
                        showToast('error', error.responseJSON.message);
                    },
                });

            });

        });
    </script>
</body>

</html>