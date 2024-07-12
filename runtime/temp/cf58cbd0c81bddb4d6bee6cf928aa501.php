<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"/www/wwwroot/hcxcx.cqbcx.com/template/default/index/index_index.html";i:1596599653;s:70:"/www/wwwroot/hcxcx.cqbcx.com/template/default/index/public_header.html";i:1596599655;s:70:"/www/wwwroot/hcxcx.cqbcx.com/template/default/index/public_footer.html";i:1596599655;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta name="applicable-device" content="pc,wap">
    <meta name="MobileOptimized" content="width" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="keywords" content="<yunu:config name='seo_keywords'>">
    <meta name="description" content="<yunu:config name='seo_description'>">
    <title><yunu:config name="seo_title"></title>
    <script type="text/javascript" src="/template/default/index/js/jquery-1.12.4.min.js"></script>
    <link rel="stylesheet" href="/template/default/index/css/yunu.css">
    <link rel="stylesheet" href="/template/default/index/css/swiper.min.css">
    <script type="text/javascript" src="/template/default/index/js/yunu.js"></script>
    <script type="text/javascript" src="/template/default/index/js/Superslide.js"></script>
    <script type="text/javascript" src="/template/default/index/js/swiper.min.js"></script>
</head>
<body>
        <div class="iet">
        <p>您的浏览器版本过低，为保证更佳的浏览体验，<a href="https://www.imooc.com/static/html/browser.html">请点击更新高版本浏览器</a></p>
        <span class="closed">以后再说<i>X</i></span>
    </div>
    <script type="text/javascript">
        $('.closed').click(function(){
            $('.iet').hide();
        })
    </script>
    <div class="header">
        <div class="center">
            <div class="head_top_content">
                <div class="head_logo fl">
                    <a href="<yunu:url name='home'/>"><img src="<yunu:config name="site_logo">"></a>
                    <div class="head_tit fl">
                        <h4><yunu:config name="site_title"></h4>
                        <h6><yunu:block name='head_text1' /></h6>
                    </div>
                    <div class="head_text fl">
                        <yunu:block name='head_text2' />    
                    </div>
                </div>
                <div class="fr">
                    <img src="/template/default/index/img/ren.png" alt="图片名">
                    <p><i>全国服务热线：</i><span><yunu:block name='head_text3' /></span></p>
                </div>
            </div>
            <div class="head_nav">
                <ul>
                    <li><a href="<yunu:url name='home'/>">首页</a></li>
                    <?php
                    $chkid = isset($cid) ? $cid : 0;
                    $catid = isset($category) ? $category['pid'] : 0;
                    ?>
                    <yunu:nav typeid='1'>
                        <li <?php if($nav['id'] == $chkid || $nav['id'] == $catid): ?>class="on"<?php endif; ?>>
                            <a href="<?php echo $nav['url']; ?>"><?php echo $nav['title']; ?></a>
                            <div class="v_list">
                                <?php if(is_array($nav['child']) || $nav['child'] instanceof \think\Collection || $nav['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $nav['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                    <a href="<?php echo $v['url']; ?>"><?php echo $v['title']; ?></a>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </li>
                    </yunu:nav>
                </ul>
            </div>
            <script type="text/javascript">
                $(".head_nav li").hover(function(){
                    $(this).toggleClass("active").siblings('.head_nav li').removeClass("active");//切换图标
                    $(this).children(".head_nav .v_list").slideToggle(150).siblings("#app_menu .v_list").slideUp(150);
                });
            </script>
        </div>
    </div>

    <div class="m_header">
        <div class="m_head_content" id="mheader">
            <div class="m_head_logo clearfix">
                <a href="<yunu:url name='home'/>"><img src="<yunu:config name="site_logo">"></a>
                <p>
                    <span><yunu:config name="site_title"></span>
                    <i><yunu:block name='head_text1' /></i>
                </p>
            </div>
            <div class="menu" id="menu"><img src="/template/default/index/img/menu.png"></div>
        </div>
        <div class="app_menu" id="app_menu">
            <ul>
                <li><a href="<yunu:url name='home'/>"><span>首页</span></a></li>
                <yunu:nav typeid='1'>
                    <li><a href="<?php echo $nav['url']; ?>"><span><?php echo $nav['title']; ?></span></a></li>
                    <div class="v_list">
                        <?php if(is_array($nav['child']) || $nav['child'] instanceof \think\Collection || $nav['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $nav['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <a href="<?php echo $v['url']; ?>"><?php echo $v['title']; ?></a>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </yunu:nav>
            </ul>
        </div>
        <script type="text/javascript">
            $("#menu").on('click', function (event) {
                if($("#app_menu").css("display")=="none"){
                    $("#app_menu").slideDown(600);
                }else{
                    $("#app_menu").slideUp(600);
                }
            }); 
            $("#app_menu li").eq(1).addClass('on');
            $("#app_menu li").eq(2).addClass('on');
            $("#app_menu li").eq(3).addClass('on');
            $("#app_menu li").eq(5).addClass('on');

            $("#app_menu li").click(function(){
                $(this).toggleClass("active").siblings('#app_menu li').removeClass("active");//切换图标
                $(this).next("#app_menu .v_list").slideToggle(500).siblings("#app_menu .v_list").slideUp(500);
            });
        </script>
    </div>
    <script type="text/javascript">
        $('.head_nav li').eq(0).addClass('on');
    </script>

    <div class="pc_banner">
        <div class="pcbanner">
            <div class="swiper-wrapper">
                <yunu:banner type="1" limit="5">
                    <div class="swiper-slide"><img src="<?php echo $banner['pic']; ?>"></div>
                </yunu:banner>
            </div>
            <div class="pagination"></div>
            <div class="next"></div>
            <div class="prev"></div>
        </div>
        <script type="text/javascript">
            var mySwiper = new Swiper('.pcbanner', {
                preventIntercationOnTransition : true,
                speed: 600,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.pcbanner .pagination',
                    type: 'bullets',
                    clickable :true,
                },
                navigation: {
                    nextEl: '.pcbanner .next',
                    prevEl: '.pcbanner .prev',
                    disabledClass: 'my-button-disabled',
                },
            })
            mySwiper.el.onmouseover = function(){
                mySwiper.autoplay.stop();
            }
        </script>
        <div class="banner_text clearfix">
            <div class="center clearfix">
                <div class="banner_text_content clearfix">
                    <img class="imgleft" id="imgleft" src="/template/default/index/img/jb.png" alt="图片名">
                    <div class="banner_text_bg">
                        <yunu:block name='head_text4' />
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#imgleft').animate({left: "0"},1000);
                            $('#pbottom').animate({top: "0"},1000);
                            $('#fonts').animate({fontSize: "43px"},1000);
                            $('#fonts').animate({opacity: "1"},2000);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="m_banner mtop">
        <div class="mbanner">
            <div class="swiper-wrapper">
                <yunu:banner type="2" limit="5">
                    <div class="swiper-slide"><img src="<?php echo $banner['pic']; ?>"></div>
                </yunu:banner>
            </div>
            <div class="pagination"></div>
        </div>
        <script type="text/javascript">
            var mySwiper = new Swiper('.mbanner', {
                speed: 500,
                autoplay: {
                    delay:5000
                },
                loop : true,
                pagination: {
                    el: '.mbanner .pagination',
                    type: 'bullets',
                },
            })
        </script>
    </div>

    <div class="main">
        <div class="i_box1 clearfix">
            <div class="center">
                <div class="i_box1_content clearfix">
                    <div class="main_l fl">
                        <p>
                            <yunu:type typeid='21'>
                                <span><?php echo $type['title']; ?></span>
                                <i><?php echo $type['subtitle']; ?></i> 
                            </yunu:type>              
                        </p>
                        <ul>
                            <yunu:catlist cid="21" limit="20">
                                <li><a href="<?php echo $catlist['url']; ?>"><span><?php echo $catlist['title']; ?></span></a></li>
                            </yunu:catlist>
                        </ul>
                        <div class="contact">
                            <yunu:type typeid='73'>
                                <a href="<?php echo $type['url']; ?>">
                                    <img src="/template/default/index/img/88.png">
                                    <h3>在线留言</h3>
                                    <span>留下您的宝贵意见</span>
                                </a>
                            </yunu:type>
                        </div>
                    </div>
                    <div class="main_r fr">
                        <div class="pro_list clearfix">
                            <ul>
                                <yunu:list cid="21" limit="8" flag="1" orderby="sort asc">
                                    <li>
                                        <a href="<?php echo $list['url']; ?>">
                                            <div class="imgauto"><img src="<?php echo $list['pic']; ?>" alt="<?php echo $list['title']; ?>"></div>
                                            <span><?php echo $list['title']; ?></span>
                                        </a>
                                    </li>
                                </yunu:list>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="i_box2 clearfix">
            <div class="center">
                <yunu:type typeid='20'>
                    <div class="i_box2_tit clearfix">
                        <p><i><?php echo $type['title']; ?> / <?php echo $type['subtitle']; ?></i></p>
                        <span></span>
                    </div>
                </yunu:type>
                <div class="i_box2_content clearfix">
                    <div class="i_box2_content1 fl">
                        <yunu:type typeid='85'>
                            <div class="flimg">
                                <img src="/template/default/index/img/img1.png">
                            </div>
                            <div class="i_box2_text fr">
                                <p><?php echo $type['desc']; ?></p>
                            </div>
                            <div class="i_box2_img fl">
                                <img src="<?php echo $type['pic']; ?>">
                            </div>
                        </yunu:type>
                        <div class="i_box2_menu fl">
                            <yunu:type typeid='20'>
                                <a href="<?php echo $type['url']; ?>">公司简介</a>
                            </yunu:type>
                            <yunu:type typeid='30'>
                                <a href="<?php echo $type['url']; ?>">联系方式</a>
                            </yunu:type>
                            <yunu:type typeid='73'>
                                <a href="<?php echo $type['url']; ?>">在线留言</a>
                            </yunu:type>
                        </div>
                    </div>
                    <div class="fr">
                        <div class="i_box2_fr_img">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <yunu:list cid="34" limit="4" flag="1">
                                        <div class="swiper-slide">
                                            <div class="imgauto"><img src="<?php echo $list['pic']; ?>" alt="<?php echo $list['title']; ?>"></div>
                                        </div>
                                    </yunu:list>
                                </div>
                                <div class="pagination"></div>
                            </div>
                            <script type="text/javascript">
                                var mySwiper = new Swiper('.i_box2_fr_img .swiper-container', {
                                    speed: 400,
                                    loop : true,
                                    pagination: {
                                        el: '.i_box2_fr_img .pagination',
                                        type: 'bullets',
                                        clickable :true,
                                    },
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="i_box3 clearfix">
            <div class="center">
                <div class="i_box3_tit clearfix">
                    <p>客户案例 / CASES</p>
                </div>
                <div class="i_box3_content clearfix" id="marquee1">
                    <ul>
                        <yunu:list cid="29" limit="8" flag="1">
                            <li><a href="<?php echo $list['url']; ?>"><div class="imgauto"><img src="<?php echo $list['pic']; ?>" onerror="this.src='/template/default/index/img/nopic.png'"></div></a></li>
                        </yunu:list>
                    </ul>
                </div>
                <script src="/template/default/index/js/jquery.kxbdMarquee.js"></script>
                <script type="text/javascript">
                    (function(){
                        $("#marquee1").kxbdMarquee({direction:"left"});
                    })();
                </script>
                <div class="i_box3_a">
                    <yunu:type typeid='29'>
                        <a href="<?php echo $type['url']; ?>">MORE+</a>
                    </yunu:type>
                </div>
            </div>
        </div>

        <div class="i_box4 clearfix">
            <div class="center">
                <div class="i_box4_content clearfix">
                    <div class="fl">
                        <div class="new_list clearfix">
                            <ul>
                                <yunu:list cid="28" limit="5" flag="1">
                                    <li>
                                        <div class="i_box4_time">
                                            <p>
                                                <span><?php echo date('d',$list['create_time']); ?></span>
                                                <i><?php echo date('Y-m',$list['create_time']); ?></i>
                                            </p>
                                        </div>
                                        <div class="i_box4_text">
                                            <a href="<?php echo $list['url']; ?>"><span><?php echo $list['title']; ?></span></a>
                                            <p><?php echo str2sub($list['content'],100, true); ?></p>
                                        </div>
                                    </li>
                                </yunu:list>
                            </ul>
                        </div>
                    </div>
                    <div class="fr">
                        <h3>联系方式 / CONTACT</h3>
                        <p>
                            <b><img src="/template/default/index/img/dz.png" alt="图片名"></b>
                            <span>地址：<yunu:block name='dizhi' /></span>
                        </p>
                        <p>
                            <b><img src="/template/default/index/img/dh.png" alt="图片名"></b>
                            <span>电话：<yunu:block name='dianhua' /></span>
                        </p>
                        <p>
                            <b><img src="/template/default/index/img/sj.png" alt="图片名"></b>
                            <span>手机：<yunu:block name='shouji' /></span>
                        </p>
                        <p>
                            <b><img src="/template/default/index/img/yx.png" alt="图片名"></b>                          
                            <span>邮箱：<yunu:block name='email' /></span>
                        </p>
                        <div class="map">
                            <yunu:block name='map' />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flink">
            <div class="center">
                <div class="flink_tit">
                    <p></p>
                    <span>友情链接 LINKS</span>
                </div>
                <div class="flink_list">
                    <ul>
                        <yunu:link type="1" limit="35">
                            <li><a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a></li>
                        </yunu:link>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flink">
            <div class="center">
                <div class="flink_tit">
                    <p></p>
                    <span>城市分站 CITYS</span>
                </div>
                <div class="flink_list">
                    <ul>
                        <yunu:area type="1" limit="35">
                            <li><a href="<?php echo $area['url']; ?>"><?php echo $area['title']; ?></a></li>
                        </yunu:area>
                    </ul>
                </div>
            </div>
        </div>
    </div>

        <div class="footer">
        <div class="center clearfix">
            <div class="fl">
                <a href="<yunu:url name='home'/>"><yunu:block name='logo2' /></a>
                <div class="foot_contact">
                    <p>TEL：<yunu:block name='dianhua' /></p>   
                    <p>MOB:<yunu:block name='shouji' /></p>  
                    <p>EMAIL：<yunu:block name='email' /></p>
                    <p>公司地址：<yunu:block name='dizhi' /></p>
                    <yunu:config name="site_copyright"/>
                </div>
            </div>
            <div class="fr">
                <yunu:block name='ewm' />
                <span>微信二维码</span>
            </div>
        </div>
    </div>

    <div class="right_fix">
        <div class="text clearfix">
            <ul>
                <li>
                    <div class="pic">
                        <img class="img1" src="/template/default/index/img/fix1.png">
                        <img class="img2" src="/template/default/index/img/fix1.png">
                        <span>产品搜索</span>
                    </div>
                    <div class="text1">
                        <div class="search clearfix">
                            <form  action="<yunu:url name='search'/>" method="post">
                                <input class="inp1" type="text" name="key" placeholder="请输入产品名称">
                                <input class="btn_sub" type="submit" name="" value="">
                            </form>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="pic">
                        <a target="blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<yunu:block name='qq' />&amp;site=qq&amp;menu=yes">
                            <img class="img1" src="/template/default/index/img/fix2.png">
                            <img class="img2" src="/template/default/index/img/fix2.png">
                            <span>在线咨询</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="pic">
                        <img class="img1" src="/template/default/index/img/fix3.png">
                        <img class="img2" src="/template/default/index/img/fix3.png">
                        <span>二维码</span>
                    </div>
                    <div class="text">
                        <yunu:block name='ewm' />
                    </div>
                </li>
                <li class="btntop">
                    <div class="pic">
                        <img class="img1" src="/template/default/index/img/fix4.png">
                        <img class="img2" src="/template/default/index/img/fix4.png">
                        <span>返回顶部</span>
                    </div>
                </li>
                <script type="text/javascript">
                    $('.btntop').click(function(){
                        $('body,html').animate({ scrollTop: 0 },500);
                    })
                </script>
            </ul>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('.imgauto img').imgAuto();
        })
    </script>
    <script type="text/javascript">
        var browser=navigator.appName 
        var b_version=navigator.appVersion 
        var version=b_version.split(";"); 
        var trim_Version=version[1].replace(/[ ]/g,""); 
        if(browser=="Microsoft Internet Explorer" && trim_Version=="MSIE6.0") { 
            $('.iet').show();
        }else if(browser=="Microsoft Internet Explorer" && trim_Version=="MSIE7.0") { 
            $('.iet').show();
        }else if(browser=="Microsoft Internet Explorer" && trim_Version=="MSIE8.0") { 
            $('.iet').show(); 
        }else if(browser=="Microsoft Internet Explorer" && trim_Version=="MSIE9.0") { 
            $('.iet').hide(); 
        } else {
            $('.iet').hide();
        }

    </script>
</body>
</html>