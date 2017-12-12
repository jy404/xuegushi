<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="{{ config('seo.default_keywords') }}">
    <meta name="description" content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}">
    <meta name="author" content="小小梦工场">
    {{--og meta--}}
    <meta property="og:title" content="{{ isset($site_title)?$site_title:config('seo.default_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.default_sub_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}" />
    <meta property="og:url" content="https://xuegushi.cn" />
    <meta property="og:sitename" content="学古诗" />
    <meta name="sogou_site_verification" content="qZUdNCxRqM"/>
    <meta name="360-site-verification" content="692a791faf2667be55acfff23615caaf" />
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    {{--<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />--}}
    <title>{{ isset($site_title)?$site_title:config('seo.default_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.default_sub_title') }}</title>
    @yield('base-css')
    @yield('content-css')
    {{--百度统计--}}
    @yield('baidutongji')
    {{--谷歌统计--}}
    @yield('googletongji')
</head>

<body>
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
@yield('header')
@yield('content')
@yield('goTop')
@yield('base-js')
@yield('content-js')
<script>
    (function(){
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https') {
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        }
        else {
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
</script>
</body>
</html>
