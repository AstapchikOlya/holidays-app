<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Holidays Service</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker3.min.css">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h1 class="card-title text-center">Holiday Service</h1>
                            <form id="check-holiday" class="mt-4">
                                @csrf
                                <div class="form-group">
                                    <label for="date" class="font-weight-semibold">Date</label>
                                    <input name="date" id="date" class="datepicker form-control">
                                    <div id="date-error" class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block mt-3">
                                        Check
                                    </button>
                                </div>
                            </form>
                            <div class="mt-4 text-center">
                                <div id="holiday-result-msg" class="alert font-weight-bold"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#date').datepicker({
                    format: 'dd.mm.yyyy',
                    todayHighlight: true,
                    autoclose: true
                });

                $('#check-holiday').submit(function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();

                    $.ajax({
                        type: 'POST',
                        url: '{{ url("/holidays/check") }}',
                        data: formData,
                        success: function(response) {
                            const alertClass = response.isHoliday
                                ? 'alert-success'
                                : 'alert-secondary';

                            $('#holiday-result-msg').addClass(alertClass).text(response.message);
                            $('#date').removeClass('is-invalid');
                            $('#date-error').text('');
                        },
                        error: function(xhr) {
                            $('#holiday-result-msg').removeClass('alert-success alert-secondary').text('');
                            $('#date').addClass('is-invalid');
                            $('#date-error').text(JSON.parse(xhr.responseText).message);
                        }
                    });
                });
            });
        </script>
    </body>
</html>
