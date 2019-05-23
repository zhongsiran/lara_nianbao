
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
                <a href="/" id="logo">合益市场监管所年报工作系统</a>
            </div>
        </div>
    </header>
    <div id="app"></div>
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="jumbotron col-md-12 text-center" style="background-color: white;">
                    <h1>合益市场监管所年报工作系统</h1>
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
                                    <option value="17年新办">1.2017年新增企业</option>
                                    <option value="一般企业">2.2016年度正常企业</option>
                                    <option value="16年未报">3.2016年度未报企业</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="div">选择片区：</label>
                                <select name="div" class="form-control">
                                    <option value="中心一区">1.中心1区</option>
                                    <option value="中心二区">2.中心2区</option>
                                    <option value="中心三区">3.中心3区</option>
                                    <option value="中心四区">4.中心4区</option>
                                    <option value="中心五区">5.中心5区</option>
                                    <option value="中心六区">6.中心6区</option>
                                    <option value="西一片区">7.西1片区</option>
                                    <option value="西二片区">8.西2片区</option>
                                    <option value="西三片区">9.西3片区</option>
                                    <option value="西四片区">10.西4片区</option>
                                    <option value="北一片区">11.北1片区</option>
                                    <option value="北二片区">12.北2片区</option>
                                    <option value="北三片区">13.北3片区</option>
                                    <option value="待处理企业片区">14.待处理片区</option>
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