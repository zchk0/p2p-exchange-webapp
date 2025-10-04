<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Загрузка файла</title>
    <script src="/jquery-3.6.4.min.js"></script>
    <script src="/jquery.form.min.js"></script>
</head>

<body>
    <style>
        .form-control {
            display: block;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 13px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
        }

        .btn-primary {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            padding: 10px 22px;
            font-size: 15px;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 4px;
            line-height: 1.43;
            box-shadow: 0px 4px 20px -5px rgb(48 48 48 / 60%);
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #286090;
            border-color: #204d74;
        }

        .progress {
            height: 20px;
            margin-bottom: 20px;
            overflow: hidden;
            background-color: #f5f5f5;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
            max-width: 291px;
        }

        .progress-bar {
            float: left;
            width: 0;
            height: 100%;
            font-size: 12px;
            line-height: 20px;
            color: #fff;
            text-align: center;
            background-color: #347c62;
            -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
            -webkit-transition: width .6s ease;
            -o-transition: width .6s ease;
            transition: width .6s ease;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style=" padding: 15px; ">
                    <div class="card-header" style=" font-size: 21px; line-height: 1.3; ">
                        Загрузка файла на облако <br>(макс размер 100 МБ)</div><br>
                    <div class="card-body">
                        <form id="fileUploadForm" action="{{ route('upload') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input required type="file" name="file" class="form-control">
                            <div class="form-group">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                        role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 0%"></div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary">
                        </form>
                        <br>
                        <div class="alert alert-success" style="display:none;"><textarea class="form-control" id="logs"
                                rows="4" style="width: 261px;"></textarea></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        $(function() {
            $(document).ready(function() {
                $('#fileUploadForm').ajaxForm({
                    beforeSend: function() {
                        $('.progress .progress-bar').css("width", "0%").text("0%");
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage + '%',
                            function() {
                                return $(this).attr("aria-valuenow", percentage) + "%";
                            }).text(percentage + "%");
                    },
                    complete: function(xhr) {
                        var file_data = JSON.parse(event.target.response);
                        $('.alert-success').css("display", "block");
                        $(".alert-success .form-control").append(file_data.durl + "\n");
                        $('.progress .progress-bar').css("width", "0%").text("0%");
                        //window.location.href = "http://p2p.zchk.ru/u";
                    }
                });
            });
        });
    </script>
</body>
</html>