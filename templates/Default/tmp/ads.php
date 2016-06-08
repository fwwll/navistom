<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>NaviStom</title>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    
    <script type="text/javascript" src="assets/inputLimiter/jquery.inputlimiter.js"></script>
    
    <script type="text/javascript" src="assets/validationEngine/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="assets/validationEngine/jquery.validationEngine-ru.js"></script>
    <link rel="stylesheet" href="assets/validationEngine/validationEngine.jquery.css" />
    
    <script type="text/javascript" src="assets/redactorjs/redactor.jquery.js"></script>
    <script type="text/javascript" src="assets/redactorjs/redactor.jquery.ru.js"></script>
    <link rel="stylesheet" href="assets/redactorjs/redactor.jquery.css" />
    
    <script type="text/javascript" src="assets/idTabs/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="assets/acorn-ui/acorn-ui.js"></script>
    
    <script type="text/javascript" src="assets/uniform/jquery.uniform.js"></script>
    <link rel="stylesheet" href="assets/uniform/jquery.uniform.base.css" />
    
    <script type="text/javascript" src="assets/select2/select2.min.js"></script>
    <link rel="stylesheet" href="assets/select2/select2.css" />
    
    <script type="text/javascript" src="assets/autosize/jquery.autosize.min.js"></script>
    
    <script type="text/javascript" src="scripts/main.js"></script>
    
    <link rel="icon" href="images/navi-favicon.png" type="image/x-icon">
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>  
    
    <link rel="stylesheet" href="assets/acorn-ui/acorn-ui.css" />
    
    <link rel="stylesheet" href="styles/main.css" />
</head>
<body>
	
    <div class="a-modal-bg"></div>
    <div class="a-modal-parent">
        <div class="a-modal">
            <a class="a-modal-closer" href="#"></a>
            <div class="a-modal-table">
                <div class="a-modal-content">
                	<a class="a-modal-close" href="#"><i class="a-icon-remove"></i></a>
                    <h1 class="n-form-title">
                    	<span>Добавить новый товар</span>
                        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
                    </h1>
                    <form class="n-add-form a-clear validation">
                    	<div class="a-row">
                        	<label><font class="a-red">*</font> Рубрика</label>
                            <select placeholder="Выберите из списка" class="select-2" name="ar-categs">
                            	<option value="0">Выберите из списка</option>
                                <option value="1">Выберите из списка</option>
                                <option value="2">Выберите из списка</option>
                            </select>
                        </div>
                        <div class="a-row">
                        	<label><font class="a-red">*</font> Раздел</label>
                            <select placeholder="Выберите из списка" class="select-2" name="ar-categs">
                            	<option value="0">Выберите из списка</option>
                                <option value="1">Выберите из списка</option>
                                <option value="2">Выберите из списка</option>
                            </select>
                        </div>
                        <div class="a-row">
                        	<label><font class="a-red">*</font> Товар</label>
                            <select placeholder="Выберите из списка" class="select-2" name="ar-categs">
                            	<option value="0">Выберите из списка</option>
                                <option value="1">Выберите из списка</option>
                                <option value="2">Выберите из списка</option>
                            </select>
                        </div>
                        <div class="a-row">
                        	<label>Стоимость</label>
                            <input class="n-price-input" type="text" name="ar-source-link" id="ar-source-link" />
                            
                            <select class="n-currensy-input" name="ad-currensy" >
                            	<option value="1">Гривен</option>
                                <option value="2">Гривен</option>
                                <option value="3">Гривен</option>
                            </select>
                        </div>
                        <div class="a-row">
                        	<label>Описание цены</label>
                            <input type="text" name="ar-source-link" id="ar-source-link" />
                        </div>
                       
                        <hr class="n-shadow-top" />
                        <div class="a-row">
                            <label>Заголовок</label>
                            <input type="text" name="ar-source-link" id="ar-source-link" />
                            
                            <div class="n-ad-add-desc a-clear">
                            	<h5>Делайте заголовок эффективным!</h5>

                                <div class="col-1">
                                	<span class="n-circle">1</span>
                                </div>
                                <div class="col-2">
                                	Начинайте с ключевого слова: КУПЛЮ, ПРОДАМ, ИЩУ, СДАМ В АРЕНДУ, ТРЕБУЕТСЯ и т.д.
                                    <p><i class="a-icon-remove"></i> Неправильно: Установка 2007</p>
                                    <p><i class="a-icon-ok"></i> Правильно: Продам установку стоматологическую 2007 года</p>
                                </div>
                                
                                <div class="col-1">
                                	<span class="n-circle">2</span>
                                </div>
                                <div class="col-2">
                                	Начинайте с ключевого слова: КУПЛЮ, ПРОДАМ, ИЩУ, СДАМ В АРЕНДУ, ТРЕБУЕТСЯ и т.д.
                                    <p><i class="a-icon-remove"></i> Неправильно: Установка 2007</p>
                                    <p><i class="a-icon-ok"></i> Правильно: Продам установку стоматологическую 2007 года</p>
                                </div>
                                
                                <div class="col-1">
                                	<span class="n-circle">3</span>
                                </div>
                                <div class="col-2">
                                	Начинайте с ключевого слова: КУПЛЮ, ПРОДАМ, ИЩУ, СДАМ В АРЕНДУ, ТРЕБУЕТСЯ и т.д.
                                    <p><i class="a-icon-remove"></i> Неправильно: Установка 2007</p>
                                    <p><i class="a-icon-ok"></i> Правильно: Продам установку стоматологическую 2007 года</p>
                                </div>
                            </div>
                        </div>
                        <hr class="n-shadow-bottom" />
                        
                        <div class="a-row">
                        	<label>Описание товара</label>
                            <textarea class="autosize" maxlength="300" name="ad-description"></textarea>
                        </div>
                        <div class="a-row">
                        	<label>Фотографии</label>
                            
                            <div class="n-image-uploader a-clear">
                            	<div class="n-image-add primary image-added">
                                	<img src="images/80x100.png" />
                                    <div class="options">
                                    	<a title="Добавить описание" href="#"><i class="a-icon-pencil"></i></a>
                                        <a title="Удалить" href="#"><i class="a-icon-trash"></i></a>
                                    </div>
                                </div>
                                <div class="n-image-add"><i class="a-icon-plus a-icon-gray"></i></div>
                                <div class="n-image-add"><i class="a-icon-plus a-icon-gray"></i></div>
                                <div class="n-image-add last"><i class="a-icon-plus a-icon-gray"></i></div>
                                
                                <div class="n-image-add"><i class="a-icon-plus a-icon-gray"></i></div>
                                <div class="n-image-add"><i class="a-icon-plus a-icon-gray"></i></div>
                                <div class="n-image-add"><i class="a-icon-plus a-icon-gray"></i></div>
                                <div class="n-image-add last"><i class="a-icon-plus a-icon-gray"></i></div>
                            </div>
                        </div>
                        <div class="a-row">
                        	<label>Ссылка на видео с YouTube</label>
                            <input type="text" name="ar-video-link" id="ar-video-link" />
                        </div>
                        
                        <div class="a-row">
                        	<div class="n-form-add-btns a-clear">
                            	<div class="col-1">
                                	<div class="n-title-orange">VIP размещение</div>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut odio neque.
                                    <div>
                                    	<input name="ar-vip" class="n-add-btn-orange" type="submit" value="VIP Размещение" />
                                    </div>
                                </div>
                                <div class="col-2">
                                	<div class="n-title-gray">Обычное размещение</div>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut odio neque.
                                    <div>
                                    	<input name="ar-add" class="n-add-btn-gray" type="submit" value="Добавить" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
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
                	<div class="navi-ads-filter">
                    	<form method="post" action="">
                        	<select class="select-2" id="category" name="category">
                            	<option value="0">Все рубрики</option>
                            </select>
                            <select class="select-2" id="sub_category" name="sub_category">
                            	<option value="0">Все разделы</option>
                            </select>
                            <select class="select-2 region" id="region" name="region">
                            	<option value="0">Все регионы</option>
                            </select>
                        </form>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-top-info">
                            	<div class="col-1">
                                	<a href="#">Стомат. оборудование б/у</a>
                                </div>
                                <div class="col-2">
                                	7 февраля 2013 г. 23:34
                                </div>
                            </div>
                            
                            <a href="#" class="n-ad-title-price">Продам воздушный стерилизатор "Витязь" ШСТ ГП-40-410 </a>
                            
                            <div class="n-ad-price">4000 грн.</div>
                            
                            <div class="n-ad-info">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                	<a href="#">
                                    	<span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-top-info">
                            	<div class="col-1">
                                	<a href="#">Стомат. оборудование б/у</a>
                                </div>
                                <div class="col-2">
                                	7 февраля 2013 г. 23:34
                                </div>
                            </div>
                            
                            <a href="#" class="n-ad-title-price">Продам воздушный стерилизатор "Витязь" ШСТ ГП-40-410 </a>
                            
                            <div class="n-ad-price">4000 грн.</div>
                            
                            <div class="n-ad-info">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                	<a href="#">
                                    	<span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-top-info">
                            	<div class="col-1">
                                	<a href="#">Стомат. оборудование б/у</a>
                                </div>
                                <div class="col-2">
                                	7 февраля 2013 г. 23:34
                                </div>
                            </div>
                            
                            <a href="#" class="n-ad-title-price">Продам воздушный стерилизатор "Витязь" ШСТ ГП-40-410 </a>
                            
                            <div class="n-ad-price">4000 грн.</div>
                            
                            <div class="n-ad-info">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                	<a href="#">
                                    	<span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-top-info">
                            	<div class="col-1">
                                	<a href="#">Стомат. оборудование б/у</a>
                                </div>
                                <div class="col-2">
                                	7 февраля 2013 г. 23:34
                                </div>
                            </div>
                            
                            <a href="#" class="n-ad-title-price">Продам воздушный стерилизатор "Витязь" ШСТ ГП-40-410 </a>
                            
                            <div class="n-ad-price">4000 грн.</div>
                            
                            <div class="n-ad-info">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                	<a href="#">
                                    	<span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="n-ad a-clear">
                    	<div class="col-1">
                        	<img src="images/80x100.png" />
                        </div>
                        <div class="col-2">
                        	<div class="n-ad-top-info">
                            	<div class="col-1">
                                	<a href="#">Стомат. оборудование б/у</a>
                                </div>
                                <div class="col-2">
                                	7 февраля 2013 г. 23:34
                                </div>
                            </div>
                            
                            <a href="#" class="n-ad-title-price">Продам воздушный стерилизатор "Витязь" ШСТ ГП-40-410 </a>
                            
                            <div class="n-ad-price">4000 грн.</div>
                            
                            <div class="n-ad-info">
                            	<div class="col-1">
                                	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> 063-198-15-03
                                </div>
                                <div class="col-2">
                                	<a href="#">
                                		<span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> Никита Анатольевич
                                	</a>
                                </div>
                                <div class="col-3">
                                	<a href="#">
                                    	<span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-2">
                	<a class="navi-btn-orange" href="#">
                    	<b><i class="a-icon-plus a-icon-white"></i></b>
                    	Добавить товар
                    </a>
                    
                    <ul class="navi-ads-vip">
                    	<li class="a-clear">
                        	<a href="#">
                            	<img src="images/64x80.png" />
                            	Продам становку стоматологическую Kavo
                                <div class="navi-ad-price">15 000 грн.</div>
                                <div>
                                	<i class="a-icon-phone"></i> 0631981503
                                </div>
                        	</a>
                        </li>
                        <li class="a-clear">
                        	<a href="#">
                            	<img src="images/64x80.png" />
                            	Продам становку стоматологическую Kavo
                                <div class="navi-ad-price">15 000 грн.</div>
                                <div>
                                	<i class="a-icon-phone"></i> 0631981503
                                </div>
                        	</a>
                        </li>
                        <li class="a-clear">
                        	<a href="#">
                            	<img src="images/64x80.png" />
                            	Продам становку стоматологическую Kavo
                                <div class="navi-ad-price">15 000 грн.</div>
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