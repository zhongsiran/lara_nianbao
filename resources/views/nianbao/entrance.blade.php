
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
                <a href="/" id="logo">狮岭所年报工作系统</a>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="jumbotron col-md-12 text-center" style="background-color: white;">
                    <div id="badges" class="weui-flex justify">
                        <img src="/Icons/AICBADGE.jpg" class="weui-flex-item">
                        <img src="/Icons/QUABADGE%20%281%29.jpg" class="weui-flex-item">
                    </div>
                    <h1>狮岭所年报工作系统</h1>
                </div>
            </div>
            <div class="row">
                <div class="container-fluid">
                    <div class="jumbotron col-md-6 text-center">
                        <h1>情况录入</h1>
                        <p>进入模块录入企业联系情况</p>
                        <form method="GET" action="index.php">
                            <div class="form-group">
                                <label for="identifier">选择名单：</label>
                                <select name="identifier" class="form-control">
                                    <option value="new_corp">1.2017年新增企业</option>
                                    <option value="normal_corp">2.2016年度正常企业</option>
                                    <option value="dead_corp">3.2016年度未报企业</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="div">选择片区：</label>
                                <select name="div" class="form-control">
                                    <option value="c1">1.中心1区</option>
                                    <option value="c2">2.中心2区</option>
                                    <option value="c3">3.中心3区</option>
                                    <option value="c4">4.中心4区</option>
                                    <option value="c5">5.中心5区</option>
                                    <option value="c6">6.中心6区</option>
                                    <option value="w1">7.西片1区</option>
                                    <option value="w2">8.西片2区</option>
                                    <option value="w3">9.西片3区</option>
                                    <option value="w4">10.西片4区</option>
                                    <option value="n1">11.北片1区</option>
                                    <option value="n2">12.北片2区</option>
                                    <option value="n3">13.北片3区</option>
                                    <option value="dcl">14.待处理片区</option>
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary btn-lg" role="button" value="点击进入">
                        </form>
                    </div>
                    <div class="jumbotron col-md-6 text-center">
                        <h1>进度统计</h1>
                        <p>查看年报进度统计情况</p>
                        <p><a class="btn btn-primary btn-lg" href="status.php" role="button">点击进入</a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <footer class="footer">
                <small class="slogon">
                    <a href="#">
                        狮岭监管所制作 
                    </a>
                </small>
            </footer>
        </div>
        <script src="/js/app.js"></script>
    </body>
    </html>