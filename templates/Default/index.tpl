<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>{{meta_title}}</title>
    
    {% for name, content in meta_tags %}
    <meta name="{{name}}" content="{{content}}" />
    {% endfor %}
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/jqueryUI/jquery-ui-1.10.2.custom.min.js"></script>
    <script type="text/javascript" src="/assets/jqueryUI/jquery.ui.datepicker-ru.js"></script>
    <script type="text/javascript" src="/assets/monthpicker/jquery.monthpicker.js"></script>
    <link rel="stylesheet" href="/assets/jqueryUI/jquery-ui.custom.css" />
    
    <script type="text/javascript" src="/assets/inputLimiter/jquery.inputlimiter.js"></script>
    
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine-ru.js"></script>
    <link rel="stylesheet" href="/assets/validationEngine/validationEngine.jquery.css" />
    
    <script type="text/javascript" src="/assets/qaptcha/jquery.ui.touch.js"></script>
    <script type="text/javascript" src="/assets/qaptcha/QapTcha.jquery.js"></script>
    <link rel="stylesheet" href="/assets/qaptcha/QapTcha.jquery.css" />
    
    <script type="text/javascript" src="/assets/jqueryForm/jquery.form.js"></script>
    
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.js"></script>
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.ru.js"></script>
    <link rel="stylesheet" href="/assets/redactorjs/redactor.jquery.css" />
    
    <script type="text/javascript" src="/assets/idTabs/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="/assets/acorn-ui/acorn-ui.js"></script>
    
    <script type="text/javascript" src="/assets/uniform/jquery.uniform.js"></script>
    <link rel="stylesheet" href="/assets/uniform/jquery.uniform.base.css" />
    
    <script type="text/javascript" src="/assets/mtip/mtip.js"></script>
    <link rel="stylesheet" href="/assets/mtip/mtip.css" />
    
    <script type="text/javascript" src="/assets/qtip/jquery.qtip.min.js"></script>
    <link rel="stylesheet" href="/assets/qtip/jquery.qtip.css" />
    
    <script type="text/javascript" src="/assets/select2/select2.min.js"></script>
    <link rel="stylesheet" href="/assets/select2/select2.css" />
    
    <script type="text/javascript" src="/assets/ad-gallery/jquery.ad-gallery.min.js"></script>
    <link rel="stylesheet" href="/assets/ad-gallery/jquery.ad-gallery.css" />
    
    <script type="text/javascript" src="/assets/ajaxScroll/jquery-ias.js"></script>
    <link rel="stylesheet" href="/assets/ajaxScroll/jquery.ias.css" />
    
    <script type="text/javascript" src="/assets/fineuploader/jquery.fineuploader.js"></script>
    
    <script type="text/javascript" src="/assets/autosize/jquery.autoresize.js"></script>
    
    <script type="text/javascript" src="/assets/address/jquery.address.min.js"></script>
    
    <script type="text/javascript" src="/assets/scrollUp/jquery.scrollUp.min.js"></script>
    
    <script type="text/javascript" src="/assets/confirm/jquery.confirm.js"></script>
    <link rel="stylesheet" href="/assets/confirm/jquery.confirm.css" />
    
    <script type="text/javascript" src="/assets/knob/jquery.knob.js"></script>
    
    <script type="text/javascript" src="/{{tpl_dir}}/scripts/main.js?{{md5time}}"></script>
    
    <link rel="icon" href="/{{tpl_dir}}/images/navi-favicon.png" type="image/x-icon">
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>  
    
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.box.css" />
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.css" />
    
    <link rel="stylesheet" href="/{{tpl_dir}}/styles/main.css?{{md5time}}" />
</head>
<body>
	<div class="navi-body">
    	<div class="navi-top-menu">
        	<ul class="a-clear">
            	{% for s in sections %}
                <li {% if s.controller == route.controller %} class="active" {% elseif s.class != '' %} class="{{s.class}}" {% endif %}>
                	<a href="{{ s.link }}"> {{ s.icon|raw }} {{ s.name }}</a>
                </li>
                {% endfor %}
                
                <li class="navi-flag">
                	<a class="a-toggle-down" href="#">
                    	<div class="navi-triangle-down"></div>
                    	{% if country == 1 %}
                        	<i class="navi-icon-ua"></i>
                        {% else %}
                        	<i class="navi-icon-ru"></i>
                        {% endif %}
                    </a>
                    <ul class="a-dropdown-menu a-dropdown-menu-right">
                        <li>
                            <a href="/ua/{{route.controller}}">Украина <i class="navi-icon-ua"></i></a>
                        </li>
                        <li>
                        	<a href="/ru/{{route.controller}}">Россия &nbsp;&nbsp;<i class="navi-icon-ru"></i></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="navi-parent">
        	
            <div class="navi-header">
                <div class="col-1">
                    {% block logotype %}
                    	<a href="/">
                            <img src="/{{tpl_dir}}/images/navi-logo.png" />
                        </a>
                    {% endblock %}
                </div>
                <div class="col-2">
                    {% block search %}
                    	<form class="navi-search" method="get">
                            <input name="q" placeholder="Поиск..." type="text" />
                            <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
                        </form>
                    {% endblock %}
                </div>
                <div class="col-3">
                	<div class="a-float-right a-color-gray a-font-smal">
                        {% if user_info %}
                            {% if user_info.info.group_id == 4 %}
                                <a target="_blank" class="a-color-gray" href="/admin">Панель управления</a> &nbsp;|&nbsp;
                                <a class="a-color-gray" href="/exit">Выход</a>
                            {% else %}
                            	<div class="n-user-info-top-block a-clear">
                            		<a class="a-color-gray" href="/cabinet"><img src="/uploads/users/avatars/tumb2/{{user_info.info.avatar}}" /></a>
                                    <div>{{user_info.info.name}}</div>
                                    <a class="a-color-gray" href="/cabinet">Личный кабинет</a><br />
                                	<a class="a-color-gray" href="/exit">Выход</a>
                                </div>
                            {% endif %}
                        {% else %}
                        	<p><br /></p>
                            <a class="a-color-gray ajax-link" href="/login">Вход</a> &nbsp;|&nbsp;
                            <a class="a-color-gray ajax-link" href="/registration">Регистрация</a>
                        {% endif %}
                    </div>
                    <div class="a-float-left n-social-links">
                        <a href="#"><i class="navi-social-vk"></i></a>
                        <a href="#"><i class="navi-social-twitter"></i></a>
                        <a href="#"><i class="navi-social-facebook"></i></a>
                        <a href="#"><i class="navi-social-google"></i></a>
                    </div>
                </div>
            </div>
            
        	{% block content%}
            
            {% endblock %}
        </div>
        <div class="navi-footer">
            <div class="footer-content">
            	<div class="navi-row-3">
                    <div class="col-1">
                    	<!--bigmir)net TOP 100-->
						<script type="text/javascript" language="javascript"><!--
                        function BM_Draw(oBM_STAT){
                        document.write('<table cellpadding="0" cellspacing="0" border="0" ><tr><td><div style="font-family:Tahoma;font-size:10px;padding:0px;margin:0px;"><div style="width:7px;float:left;background:url(\'http://i.bigmir.net/cnt/samples/default/b53_left.gif\');height:17px;line-height:17px;background-repeat:no-repeat;"></div><div style="float:left;background:url(\'http://i.bigmir.net/cnt/samples/default/b53_center.gif\');text-align:left;height:17px;background-repeat:repeat-x;"><a href="http://top.bigmir.net/report/16928089/" target="_blank" style="color:#0000ab;text-decoration:none;">bigmir<span style="color:#ff0000;">)</span>net</a>  <span style="color:#969696;">хиты</span> <span style="color:#003596;font:10px Tahoma;">'+oBM_STAT.hits+'</span> <span style="color:#969696;">хосты</span> <span style="color:#003596;font:10px Tahoma;">'+oBM_STAT.hosts+'</span></div><div style="width:7px;float: left;background:url(\'http://i.bigmir.net/cnt/samples/default/b53_right.gif\');height:17px;background-repeat:no-repeat;"></div></div></td></tr></table>');
                        }
                        //-->
                        </script>
                        <script type="text/javascript" language="javascript"><!--
                        bmN=navigator,bmD=document,bmD.cookie='b=b',i=0,bs=[],bm={o:1,v:16928089,s:16928089,t:0,c:bmD.cookie?1:0,n:Math.round((Math.random()* 1000000)),w:0};
                        for(var f=self;f!=f.parent;f=f.parent)bm.w++;
                        try{if(bmN.plugins&&bmN.mimeTypes.length&&(x=bmN.plugins['Shockwave Flash']))bm.m=parseInt(x.description.replace(/([a-zA-Z]|\s)+/,''));
                        else for(var f=3;f<20;f++)if(eval('new ActiveXObject("ShockwaveFlash.ShockwaveFlash.'+f+'")'))bm.m=f}catch(e){;}
                        try{bm.y=bmN.javaEnabled()?1:0}catch(e){;}
                        try{bmS=screen;bm.v^=bm.d=bmS.colorDepth||bmS.pixelDepth;bm.v^=bm.r=bmS.width}catch(e){;}
                        r=bmD.referrer.slice(7);if(r&&r.split('/')[0]!=window.location.host){bm.f=escape(r).slice(0,400);bm.v^=r.length}
                        bm.v^=window.location.href.length;for(var x in bm) if(/^[ovstcnwmydrf]$/.test(x)) bs[i++]=x+bm[x];
                        bmD.write('<sc'+'ript type="text/javascript" language="javascript" src="http://c.bigmir.net/?'+bs.join('&')+'"></sc'+'ript>');
                        //-->
                        </script>
                        <noscript>
                        <a href="http://www.bigmir.net/" target="_blank"><img src="http://c.bigmir.net/?v16928089&s16928089&t2" width="88" height="31" alt="bigmir)net TOP 100" title="bigmir)net TOP 100" border="0" /></a>
                        </noscript>
                        <!--bigmir)net TOP 100-->
                    </div>
                    <div class="col-2">
                        <ul>
                            <li>
                                <a class="ajax-link" href="/faq">О сайте</a> &nbsp;|&nbsp;
                            </li>
                            <li>
                                <a class="ajax-link" href="/feedback">Обратная связь</a> &nbsp;|&nbsp; 
                            </li>
                            <li>
                                <a class="ajax-link" href="/advert">Реклама на Navistom.net</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-3">
                    	<!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style ">
                            <a class="addthis_button_preferred_1"></a>
                            <a class="addthis_button_preferred_2"></a>
                            <a class="addthis_button_preferred_3"></a>
                            <a class="addthis_button_preferred_4"></a>
                            <a class="addthis_button_compact"></a>
                            <a class="addthis_counter addthis_bubble_style"></a>
                        </div>
                        <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f7d770f68a3c8a2"></script>
                        <!-- AddThis Button END -->
                    </div>
                </div>
            </div>
            
            <a class="navi-footer-banner" href="#">
            	<img src="/{{tpl_dir}}/images/banner-footer.png" />
            </a>
        </div>
    </div>
</body>
</html>