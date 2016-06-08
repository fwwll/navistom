<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>NaviStom</title>
    
    <script type="text/javascript" src="assets/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="assets/idTabs/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="assets/acorn-ui/acorn-ui.js"></script>
    
    <script type="text/javascript" src="assets/select2/select2.min.js"></script>
    <link rel="stylesheet" href="assets/select2/select2.css" />
    
    <script type="text/javascript" src="scripts/main.js"></script>
    
    <link rel="icon" href="images/navi-favicon.png" type="image/x-icon">
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>  
    
    <link rel="stylesheet" href="assets/acorn-ui/acorn-ui.css" />
    
    <link rel="stylesheet" href="styles/main.css" />
</head>
<body>
	<!-- modal window 
    
	<div class="a-modal-bg"></div>
    
    <div class="a-modal-parent">
    	123123
    </div>
    
    -->
    
	<div class="navi-body">
    	<div class="navi-top-menu">
        	<ul>
            	<li class="active">
                	<a href="/"> <i class="a-icon-home a-icon-white"></i> Главная</a>
                </li>
                <li>
                	<a href="#">Акции</a>
                </li>
                <li>
                	<a href="#">Продам новое</a>
                </li>
                <li>
                	<a href="#">Продам Б/У</a>
                </li>
                <li>
                	<a href="#">Мероприятия</a>
                </li>
                <li>
                	<a href="#">Работа</a>
                </li>
                <li>
                	<a href="#">З / Т лаб.</a>
                </li>
                <li>
                	<a href="#">Недвижимость</a>
                </li>
                <li>
                	<a href="#">Сервис</a>
                </li>
                <li>
                	<a href="#">Диагностика</a>
                </li>
                <li>
                	<a href="#">Спрос</a>
                </li>
                <li class="navi-flag">
                	<a class="a-toggle-down" href="#">
                    	<div class="navi-triangle-down"></div>
                    	<i class="navi-icon-ua"></i>
                    </a>
                    <ul class="a-dropdown-menu a-dropdown-menu-right">
                        <li>
                            <a href="#">Украина <i class="navi-icon-ua"></i></a>
                        </li>
                        <li>
                        	<a href="#">Россия &nbsp;&nbsp;<i class="navi-icon-ru"></i></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
        <div class="navi-parent">
        	<div class="navi-header">
            	<div class="col-1">
                	<a href="/">
                    	<img src="images/logo.gif" />
                    </a>
                </div>
                <div class="col-2">
                	<div class="a-clear">
                    	<div class="a-float-left a-color-gray a-font-smal">
                            <a class="a-color-gray" href="#">Вход</a> &nbsp;|&nbsp;
                            <a class="a-color-gray" href="#">Регистрация</a>
                        </div>
                    	<div class="a-float-right">
                            <a href="#"><i class="navi-social-vk"></i></a>
                            <a href="#"><i class="navi-social-twitter"></i></a>
                            <a href="#"><i class="navi-social-facebook"></i></a>
                            <a href="#"><i class="navi-social-google"></i></a>
                        </div>
                    </div>
                	<form class="navi-search" method="get">
                    	<input name="q" placeholder="Поиск..." type="text" />
                        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
                    </form>
                </div>
                <div class="col-3">
                	<a href="#">
                    	<img src="images/banner.jpg" />
                    </a>
                </div>
            </div>
            
            <div class="navi-row-2">
            	<div class="col-1">
                	<div class="navi-ads-filter navi-edu-filter">
                    	<form method="post" action="">
                        	<select class="select-2" id="category" name="category">
                            	<option value="0">Все рубрики</option>
                            </select>
                            <select class="select-2 region" id="region" name="region">
                            	<option value="0">Место проведения</option>
                            </select>
                            
                            <div class="a-group-btn">
                            	<a class="a-btn active" href="#">Свежие</a>
                                <a class="a-btn" href="#">Популярные</a>
                                <a class="a-btn" href="#">Ближайшие</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="n-ad-top-info a-clear">
                            <div class="col-1">
                                <a href="#">Установки стоматологические</a>
                            </div>
                            <div class="col-2">
                                7 февраля 2013 г. 23:34
                            </div>
                        </div>
                        
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-date">
                            	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                            </div>
                            <a href="#" class="n-ad-title n-ad-title-edu">
                            	Центральная окклюзия. Центральне соотношение. Привычная окклюзия
                            </a>
                            
                            <div class="n-ad-info-region">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                    <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. Ивано-Франковск
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="n-ad-top-info a-clear">
                            <div class="col-1">
                                <a href="#">Установки стоматологические</a>
                            </div>
                            <div class="col-2">
                                7 февраля 2013 г. 23:34
                            </div>
                        </div>
                        
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-date">
                            	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                            </div>
                            <a href="#" class="n-ad-title n-ad-title-edu">
                            	Центральная окклюзия. Центральне соотношение. Привычная окклюзия
                            </a>
                            
                            <div class="n-ad-info-region">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                    <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. Ивано-Франковск
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="n-ad-top-info a-clear">
                            <div class="col-1">
                                <a href="#">Установки стоматологические</a>
                            </div>
                            <div class="col-2">
                                7 февраля 2013 г. 23:34
                            </div>
                        </div>
                        
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-date">
                            	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                            </div>
                            <a href="#" class="n-ad-title n-ad-title-edu">
                            	Центральная окклюзия. Центральне соотношение. Привычная окклюзия
                            </a>
                            
                            <div class="n-ad-info-region">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                    <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. Ивано-Франковск
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="n-ad-top-info a-clear">
                            <div class="col-1">
                                <a href="#">Установки стоматологические</a>
                            </div>
                            <div class="col-2">
                                7 февраля 2013 г. 23:34
                            </div>
                        </div>
                        
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-date">
                            	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                            </div>
                            <a href="#" class="n-ad-title n-ad-title-edu">
                            	Центральная окклюзия. Центральне соотношение. Привычная окклюзия
                            </a>
                            
                            <div class="n-ad-info-region">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                    <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. Ивано-Франковск
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="n-ad-top-info a-clear">
                            <div class="col-1">
                                <a href="#">Установки стоматологические</a>
                            </div>
                            <div class="col-2">
                                7 февраля 2013 г. 23:34
                            </div>
                        </div>
                        
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-date">
                            	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                            </div>
                            <a href="#" class="n-ad-title n-ad-title-edu">
                            	Центральная окклюзия. Центральне соотношение. Привычная окклюзия
                            </a>
                            
                            <div class="n-ad-info-region">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                    <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. Ивано-Франковск
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    
                    
                </div>
                
                <div class="col-2">
                	<a class="navi-btn-orange" href="#">
                    	<b><i class="a-icon-plus a-icon-white"></i></b>
                    	Добавить мероприятие
                    </a>
                    
                    <ul class="navi-ads-vip">
                    	<li class="a-clear">
                        	<a href="#">
                            	<div class="navi-ad-edu-date">
                                	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                                </div>
                            	<img src="images/64x80.png" />
                            	Продам становку стоматологическую Kavo
                                <div class="navi-ad-edu-author">ММАКСИМУС</div>
                                <div>
                                	<i class="a-icon-phone"></i> 0631981503
                                </div>
                        	</a>
                        </li>
                        <li class="a-clear">
                        	<a href="#">
                            	<div class="navi-ad-edu-date">
                                	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                                </div>
                            	<img src="images/64x80.png" />
                            	Продам становку стоматологическую Kavo
                                <div class="navi-ad-edu-author">ММАКСИМУС</div>
                                <div>
                                	<i class="a-icon-phone"></i> 0631981503
                                </div>
                        	</a>
                        </li>
                        <li class="a-clear">
                        	<a href="#">
                            	<div class="navi-ad-edu-date">
                                	<span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span> 17 апреля - 19 апреля
                                </div>
                            	<img src="images/64x80.png" />
                            	Продам становку стоматологическую Kavo
                                <div class="navi-ad-edu-author">ММАКСИМУС</div>
                                <div>
                                	<i class="a-icon-phone"></i> 0631981503
                                </div>
                        	</a>
                        </li>
                    </ul>
                    
                    <hr />
                    
                    <a href="#">
                    	<img src="images/banner-right.png" />
                    </a>
                </div>
            </div>
        </div>
        
        <div class="navi-footer">
            <div class="footer-content">
            	<div class="navi-row-3">
                    <div class="col-1">Bigmir</div>
                    <div class="col-2">
                        <ul>
                            <li>
                                <a href="#">О сайте</a> &nbsp;|&nbsp;
                            </li>
                            <li>
                                <a href="#">Обратная связь</a> &nbsp;|&nbsp; 
                            </li>
                            <li>
                                <a href="#">Реклама на Navistom.net</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-3">addThis</div>
                </div>
            </div>
            
            <a class="navi-footer-banner" href="#">
            	<img src="images/banner-footer.png" />
            </a>
        </div>
    </div>
</body>
</html>