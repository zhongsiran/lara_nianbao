<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('nianbao.entrance')}}">年报系统</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    返回上级
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{route('nianbao.entrance')}}">返回首页 <span class="sr-only">(current)</span></a>
            
                    @if (isset($page_type) and $page_type == 'corp_detail')
                    {{--  根据页面类型决定是否显示“返回名单按钮”  --}}
                        @if (isset($page))
                            <a class="dropdown-item" href="{{route('corp.index',['type'=>$type, 'div'=>$div, 'page'=>$page])}}">返回名单列表</a>
                        @else
                            <a class="dropdown-item" href="{{route('corp.index',['type'=>$type, 'div'=>$div])}}">返回名单列表</a>
                        @endif
                    @endif
                </div>
            </li>
            @if (isset($corp))
            <li class="nav-item">
                <a class="nav-link" href="{{route('corp.prev', $corp->RegNum)}}">前一户</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('corp.next', $corp->RegNum)}}">下一户</a>
            </li>
            @endif

            <li>            
                <form class="form-inline" method="GET" action="/corps/search">
                    <input name="search_content" class="form-control mr-sm-2" type="search" placeholder="搜索注册号或企业名称" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </li>

            {{-- <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li> --}}
        </ul>
{{--         <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form> --}}
    </div>
</nav>