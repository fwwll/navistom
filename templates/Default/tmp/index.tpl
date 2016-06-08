<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>NaviStom</title>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    
    <script type="text/javascript" src="assets/jqueryUI/jquery-ui.custom.min.js"></script>
    <link rel="stylesheet" href="assets/jqueryUI/jquery-ui.custom.css" />
    
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
    
    <script type="text/javascript" src="assets/autosize/jquery.autosize.js"></script>
    
    <script type="text/javascript" src="{{constant('TPL_PATH')}}/scripts/main.js"></script>
    
    <link rel="icon" href="images/navi-favicon.png" type="image/x-icon">
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>  
    
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.css" />
    
    <link rel="stylesheet" href="{{constant('TPL_PATH')}}/styles/main.css" />
</head>
<body>
	<!--<div class="a-modal-bg"></div>
    <div class="a-modal-parent">
        <div class="a-modal">
            <a class="a-modal-closer" href="#"></a>
            <div class="a-modal-table">
                <div class="a-modal-content">
                	<a class="a-modal-close" href="#"><i class="a-icon-remove"></i></a>
                    <h1 class="n-form-title">
                    	<span>Добавить статью</span>
                        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
                    </h1>
                    <form class="n-add-form a-clear validation">
                    	<div class="a-row">
                        	<label><font class="a-red">*</font> Заголовок статьи </label>
                            <input class="validate[required]" maxlength="80" type="text" name="ar-title" id="ar-title" />
                        </div>
                        <div class="a-row">
                        	<label>Автор статьи</label>
                            <input class="datepicker" type="text" name="ar-author" id="ar-author" />
                        </div>
                        <div class="a-row">
                        	<label><font class="a-red">*</font> Название источника</label>
                            <input type="text" name="ar-source" id="ar-source" />
                        </div>
                        <div class="a-row">
                        	<label>Ссылка на источник</label>
                            <input type="text" name="ar-source-link" id="ar-source-link" />
                        </div>
                        <div class="a-row">
                        	<label><font class="a-red">*</font> Рубрика</label>
                            <select placeholder="Выберите из списка" multiple="multiple" class="select-2 validate[required, minSize=1]" name="ar-categs">
                            	<option value="0">Выберите из списка</option>
                                <option value="1">Выберите из списка</option>
                                <option value="2">Выберите из списка</option>
                            </select>
                        </div>
                        <div class="a-row">
                        	<label><font class="a-red">*</font> Текст статьи </label>
                            <textarea class="editor" name="ar-content" id="ar-content"></textarea>
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
                        	<label>Ваши пожелания по дате размещения и оформлению статьи</label>
                            <textarea name="ar-user-comment" id="ar-user-comment"></textarea>
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
    </div>-->
	<!-- VIEW ARTICLE FULL	
    <div class="a-modal-bg"></div>
    <div class="a-modal-parent">
        <div class="a-modal">
            <a class="a-modal-closer" href="#"></a>
            <div class="a-modal-table">
                <div class="a-modal-content">
                	<a class="a-modal-close" href="#"><i class="a-icon-remove"></i></a>
                    <div class="n-article-full">
                    	<div class="n-modal-top-info a-clear">
                        	<div class="col-1">
                            	<a href="#">Новости</a>, 
                                <a href="#">Ортодонтия</a>
                            </div>
                            <div class="col-2">
                            	6 Февраля 2013г.&nbsp; | &nbsp;
                                2 <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                                215 <i class="a-icon-eye-open a-icon-gray"></i>
                            </div>
                        </div>
                    	<h1>Подростки умирают из-за моды на фальшивые брекеты</h1>
                        
                        <img src="images/600x300.png" />
                        
                        <p>
                        	Сейчас в Азии брекеты являются признаком материального достатка. К примеру, в Бангкоке стоимость установки настоящих брекетов составляет около $1200, что является ощутимой суммой для жителей Таиланда.
                        </p>
                        <p>
                        	Установка фальшивых брекетов не требует участия стоматолога, в настоящее время такие услуги предлагают многие салоны красоты в азиатских странах. Кроме того, существуют и более дешёвые наборы «сделай сам», стилизованные под определённую тематику, такую как Hello Kitty или Микки Маус, цена которых составляет чуть менее $100.
                        </p>
                        <p>
                        В то время как азиатские подростки считают свои красочные брекеты модными и привлекательными, эксперты утверждают, что их ношение может быть опасно для здоровья и даже способно привести к смерти. Фальшивые брекеты могут отцепиться и застрять в горле, вызвать язвы на слизистой оболочке щек и десен, а в некоторых из них используется проволока, содержащая свинец.
                        </p>
                        
                        <div class="n-article-author">
                        	<a href="#">
                            	<i class="a-icon-user a-icon-gray"></i> Vasya
                            </a>
                        </div>
                        
                        <div class="n-title">
                        	Опрос
                        </div>
                        
                        <b>Как Вы оцениваете данную статью?</b>
                        
                        <!--<form method="post" action="">
                        	<div class="a-row">
                            	<input type="radio" name="interview" id="name_1" value="1" />
                                <label for="name_1">Великолепно!</label>
                            </div>
                            <div class="a-row">
                            	<input type="radio" name="interview" id="name_2" value="1" />
                                <label for="name_2">Хорошо</label>
                            </div>
                            <div class="a-row">
                            	<input type="radio" name="interview" id="name_3" value="1" />
                                <label for="name_3">Плохо!</label>
                            </div>
                            <div class="a-row">
                            	<input type="radio" name="interview" id="name_4" value="1" />
                                <label for="name_4">Полное гавно!</label>
                            </div>
                        </form>-->
                        <!--
                        <div class="n-interview-result a-clear">
                        	<div class="n-interview-desc">Великолепно!</div>
                        	<div class="n-inerview-bg">
                            	<div class="n-inerview-res"></div>
                                <span>10</span>
                            </div>
                            <div class="n-interview-right">70%</div>
                            
                            <div class="n-interview-desc">Хорошо</div>
                        	<div class="n-inerview-bg">
                            	<div style="width:20%" class="n-inerview-res"></div>
                                <span>5</span>
                            </div>
                            <div class="n-interview-right">20%</div>
                            
                            <div class="n-interview-desc">Плохо!</div>
                        	<div class="n-inerview-bg">
                            	<div style="width:10%" class="n-inerview-res"></div>
                                <span>2</span>
                            </div>
                            <div class="n-interview-right">10%</div>
                        </div>
                        
                        <div class="n-title">
                        	Комментарии
                        </div>
                        
                        <div class="n-comments a-clear">
                        	<div class="n-comment a-clear">
                            	<div class="col-1">
                                	<img src="images/navi-no-avatar.png" />
                                </div>
                                <div class="col-2">
                                	<div class="n-ad-top-info a-clear">
                                        <div class="col-1">
                                            <a href="#"><i class="a-icon-user a-icon-gray"></i> Vasya</a>
                                        </div>
                                        <div class="col-2">
                                            7 февраля 2013 г. 23:34
                                        </div>
                                    </div>
                                	Была-бы хоть действительно неотразимой!
                                    Вот америкосы дают жару
                                </div>
                            </div>
                            <div class="n-comment a-clear">
                            	<div class="col-1">
                                	<img src="images/navi-no-avatar.png" />
                                </div>
                                <div class="col-2">
                                	<div class="n-ad-top-info a-clear">
                                        <div class="col-1">
                                            <a href="#"><i class="a-icon-user a-icon-gray"></i> Vasya</a>
                                        </div>
                                        <div class="col-2">
                                            7 февраля 2013 г. 23:34
                                        </div>
                                    </div>
                                	Была-бы хоть действительно неотразимой!
                                    Вот америкосы дают жару
                                </div>
                            </div>
                            <div class="n-comment a-clear">
                            	<div class="col-1">
                                	<img src="images/navi-no-avatar.png" />
                                </div>
                                <div class="col-2">
                                	<div class="n-ad-top-info a-clear">
                                        <div class="col-1">
                                            <a href="#"><i class="a-icon-user a-icon-gray"></i> Vasya</a>
                                        </div>
                                        <div class="col-2">
                                            7 февраля 2013 г. 23:34
                                        </div>
                                    </div>
                                	Была-бы хоть действительно неотразимой!
                                    Вот америкосы дают жару
                                </div>
                            </div>
                            
                            <div class="n-title">
                                Добавить комментарий
                            </div>
                            
                            <form class="n-comment-add a-clear" method="post" action="">
                            	<textarea class="autosize" placeholder="Начните вводить текст..." name="comment"></textarea>
                                
                                <div class="a-float-right">
                                	<input class="a-btn-green" type="submit" value="Отправить" />
                                </div>
                            </form>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>-->
    
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
            
            <div class="navi-row-3">
            	<div class="col-1">
                	<a class="navi-btn-orange" href="#">
                    	<b><i class="a-icon-plus a-icon-white"></i></b>
                    	Добавить статью
                    </a>
                    
                    <ul class="navi-categs-list">
                    	<li>
                        	<a href="#">Новости</a>
                        </li>
                        <li>
                        	<a href="#">Управление клиникой</a>
                        </li>
                        <li>
                        	<a href="#">Релиз</a>
                        </li>
                        <li>
                        	<a href="#">Технологии</a>
                        </li>
                        <li>
                        	<a href="#">Эстетическая стоматология</a>
                        </li>
                        <li>
                        	<a href="#">Гигиена полости рта</a>
                        </li>
                        <li>
                        	<a href="#">Пародонтология</a>
                        </li>
                        <li>
                        	<a href="#">Терапевтическая стоматология</a>
                        </li>
                        <li>
                        	<a href="#">Эндодонтия</a>
                        </li>
                        <li>
                        	<a href="#">Хирургическая стоматология</a>
                        </li>
                        <li>
                        	<a href="#">Ортопедическая стоматология</a>
                        </li>
                    </ul>
                    
                    <hr />
                    	<font class="a-size-16">Метки</font>
                    <hr />
                </div>
                <div class="col-2">
                	<div class="navi-article-box">
                    	<a href="#" class="navi-article-title">Как пациенты выбирают стоматолога?</a>
                        <div class="navi-article-info a-clear a-font-smal">
                        	<div class="a-float-left a-color-gray">
                            	6 Февраля 2013г.&nbsp; | &nbsp;
                                2 <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                                215 <i class="a-icon-eye-open a-icon-gray"></i>
                            </div>
                            <div class="a-float-right">
                            	<a href="#" class="a-color-gray">Управление клиникой</a>
                            </div>
                        </div>
                        <div class="navi-article-row-2 a-clear">
                        	<div class="a-float-left">
                            	<img src="images/100x150.png" />
                            </div>
                            <div class="a-float-right">
                            	«Если пациенту понравился приём – он обязательно поделится своими впечатлениями минимум с тремя людьми, в случае неудачного опыта – об этом узнают больше одиннадцати».
                                <div class="navi-article-tags a-font-smal a-color-gray">
                                	<a href="#">Маркетинг</a>,
                                    <a href="#">Менеджмент</a>,
                                    <a href="#">Стоматклиника</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="navi-article-box">
                    	<a href="#" class="navi-article-title">Canary System - первое в мире устройство для выявления начального кариеса под пломбой</a>
                        <div class="navi-article-info a-clear a-font-smal">
                        	<div class="a-float-left a-color-gray">
                            	6 Февраля 2013г.&nbsp; | &nbsp;
                                2 <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                                215 <i class="a-icon-eye-open a-icon-gray"></i>
                            </div>
                            <div class="a-float-right">
                            	<a href="#" class="a-color-gray">Управление клиникой</a>
                            </div>
                        </div>
                        <div class="navi-article-row-2 a-clear">
                        	<div class="a-float-left">
                            	<img src="images/100x150.png" />
                            </div>
                            <div class="a-float-right">
                            	«Если пациенту понравился приём – он обязательно поделится своими впечатлениями минимум с тремя людьми, в случае неудачного опыта – об этом узнают больше одиннадцати».
                                <div class="navi-article-tags a-font-smal a-color-gray">
                                	<a href="#">Маркетинг</a>,
                                    <a href="#">Менеджмент</a>,
                                    <a href="#">Стоматклиника</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="navi-article-box">
                    	<a href="#" class="navi-article-title">Предпочтения пациентов мужчин и женщин в стоматологических клиниках</a>
                        <div class="navi-article-info a-clear a-font-smal">
                        	<div class="a-float-left a-color-gray">
                            	6 Февраля 2013г.&nbsp; | &nbsp;
                                2 <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                                215 <i class="a-icon-eye-open a-icon-gray"></i>
                            </div>
                            <div class="a-float-right">
                            	<a href="#" class="a-color-gray">Управление клиникой</a>
                            </div>
                        </div>
                        <div class="navi-article-row-2 a-clear">
                        	<div class="a-float-left">
                            	<img src="images/100x150.png" />
                            </div>
                            <div class="a-float-right">
                            	«Если пациенту понравился приём – он обязательно поделится своими впечатлениями минимум с тремя людьми, в случае неудачного опыта – об этом узнают больше одиннадцати».
                                <div class="navi-article-tags a-font-smal a-color-gray">
                                	<a href="#">Маркетинг</a>,
                                    <a href="#">Менеджмент</a>,
                                    <a href="#">Стоматклиника</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="navi-article-box">
                    	<a href="#" class="navi-article-title">Как пациенты выбирают стоматолога?</a>
                        <div class="navi-article-info a-clear a-font-smal">
                        	<div class="a-float-left a-color-gray">
                            	6 Февраля 2013г.&nbsp; | &nbsp;
                                2 <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                                215 <i class="a-icon-eye-open a-icon-gray"></i>
                            </div>
                            <div class="a-float-right">
                            	<a href="#" class="a-color-gray">Управление клиникой</a>
                            </div>
                        </div>
                        <div class="navi-article-row-2 a-clear">
                        	<div class="a-float-left">
                            	<img src="images/100x150.png" />
                            </div>
                            <div class="a-float-right">
                            	«Если пациенту понравился приём – он обязательно поделится своими впечатлениями минимум с тремя людьми, в случае неудачного опыта – об этом узнают больше одиннадцати».
                                <div class="navi-article-tags a-font-smal a-color-gray">
                                	<a href="#">Маркетинг</a>,
                                    <a href="#">Менеджмент</a>,
                                    <a href="#">Стоматклиника</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="navi-article-box">
                    	<a href="#" class="navi-article-title">Как пациенты выбирают стоматолога?</a>
                        <div class="navi-article-info a-clear a-font-smal">
                        	<div class="a-float-left a-color-gray">
                            	6 Февраля 2013г.&nbsp; | &nbsp;
                                2 <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                                215 <i class="a-icon-eye-open a-icon-gray"></i>
                            </div>
                            <div class="a-float-right">
                            	<a href="#" class="a-color-gray">Управление клиникой</a>
                            </div>
                        </div>
                        <div class="navi-article-row-2 a-clear">
                        	<div class="a-float-left">
                            	<img src="images/100x150.png" />
                            </div>
                            <div class="a-float-right">
                            	«Если пациенту понравился приём – он обязательно поделится своими впечатлениями минимум с тремя людьми, в случае неудачного опыта – об этом узнают больше одиннадцати».
                                <div class="navi-article-tags a-font-smal a-color-gray">
                                	<a href="#">Маркетинг</a>,
                                    <a href="#">Менеджмент</a>,
                                    <a href="#">Стоматклиника</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                	<ul class="idTabs a-clear">
                    	<li>
                        	<a href="#articles-vip">VIP</a>
                        </li>
                    	<li>
                        	<a href="#articles-video">Видео</a>
                        </li>
                        <li>
                        	<a href="#articles-archive">Архив</a>
                        </li>
                    </ul>
                    <div id="articles-vip">
                    	<ul>
                        	<li class="a-clear">
                            	<a href="#">
                                    <img src="images/50x75.png" />
                                    Как пациенты выбирают стоматолога?
                                </a>
                            </li>
                            <li class="a-clear">
                            	<a href="#">
                                    <img src="images/50x75.png" />
                                    Антисептический ополаскиватель для полости рта Eliminator Mouthwash
                                </a>
                            </li>
                            <li class="a-clear">
                            	<a href="#">
                                    <img src="images/50x75.png" />
                                    Canary System - первое в мире устройство для выявления начального кариеса под пломбой
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="articles-video">
                    	<ul>
                        	<li>
                            	<a href="#">
                                	<div class="navi-video-marker"></div>
                                	<img src="images/175x250.png" />
                                    Предпочтения пациентов мужчин и женщин в стоматологических клиниках
                                </a>
                            </li>
                            <li>
                            	<a href="#">
                                	<div class="navi-video-marker"></div>
                                	<img src="images/175x250.png" />
                                    Предпочтения пациентов мужчин и женщин в стоматологических клиниках
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="articles-archive">
                    	<ul>
                        	<li>
                            	<a href="#">Декабрь 2012</a>
                            </li>
                            <li>
                            	<a href="#">Ноябрь 2012</a>
                            </li>
                            <li>
                            	<a href="#">Октябрь 2012</a>
                            </li>
                            <li>
                            	<a href="#">Сентябрь 2012</a>
                            </li>
                            <li>
                            	<a href="#">Август 2012</a>
                            </li>
                            <li>
                            	<a href="#">Июль 2012</a>
                            </li>
                            <li>
                            	<a href="#">Июнь 2012</a>
                            </li>
                            <li>
                            	<a href="#">Май 2012</a>
                            </li>
                            <li>
                            	<a href="#">Апрель 2012</a>
                            </li>
                            <li>
                            	<a href="#">Март 2012</a>
                            </li>
                        </ul>
                    </div>
                    
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