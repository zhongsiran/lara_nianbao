
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>年报系统</title>
    <link rel="stylesheet" type="text/css" href="/css/app_entrance.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-inverse">
        <div class="container">
            <div class="col-md-offset-1 col-md-10">
                <a href="/" id="logo">{{env('JIAN_GUAN_SUO')}} 市场监管所年报工作系统</a>
            </div>
        </div>
    </header>
    <div id="app"></div>
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="jumbotron col-md-12 text-center" style="background-color: white;">
                    <h1>{{env('JIAN_GUAN_SUO')}}市场监管所年报工作系统</h1>
                </div>
            </div>
            <div class="row">
                <div class="container-fluid">
                    <div class="jumbotron col-md-6 text-center">
                        <h1>情况录入</h1>
                        <p>进入模块录入企业联系情况</p>
                        <form method="GET" action="{{route('corp.index')}}">
                            <div class="form-group">
                                <label for="type">选择名单：</label>
                                <select name="type" class="form-control">
                                    <option value="新办">1.新增企业</option>
                                    <option value="一般企业">2.去年正常企业</option>
                                    <option value="去年未报">3.去年未报企业</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="div">选择片区：</label>
                                <select name="div" class="form-control">
                                    <option value="A1">1.A1</option>
                                    <option value="A2">2.A2</option>
                                    <option value="B1">3.B1</option>
                                    <option value="B2">4.B2</option>
                                    <option value="B3">5.B3</option>
                                    <option value="C1">6.C1</option>
                                    <option value="C2">7.C2</option>
                                    <option value="C3">8.C3</option>
                                    <option value="C4">9.C4</option>
                                    <option value="进出口">10.进出口</option>
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary btn-lg" role="button" value="点击进入">
                        </form>
                    </div>
                    <div class="jumbotron col-md-6 text-center">
                        <h1>进度统计</h1>
                        <p>查看年报进度统计情况</p>
                        <p><a class="btn btn-primary btn-lg" href="status" role="button">点击进入</a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <footer class="footer">
                <small class="slogon">
                    <a href="#">
                        反馈建议请联系钟思燃（661668、13922471668）
                    </a>
                </small>
            </footer>
        </div>
        <script src="/js/app.js"></script>
    </body>
    </html>