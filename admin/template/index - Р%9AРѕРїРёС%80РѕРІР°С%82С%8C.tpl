<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{% block title %} {% endblock %}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
    
    <link rel="icon" href="/admin/{{tpl_dir}}/images/navi-favicon.png" type="image/x-icon">
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/jqueryUI/jquery-ui-1.10.2.custom.min.js"></script>
    <script type="text/javascript" src="/assets/jqueryUI/jquery.ui.datepicker-ru.js"></script>
    <link rel="stylesheet" href="/assets/jqueryUI/jquery-ui.custom.css" />
    
    <script type="text/javascript" src="/assets/jqueryUI/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="/assets/jqueryUI/jquery-ui-timepicker-ru.js"></script>
    <link rel="stylesheet" href="/assets/jqueryUI/jquery-ui-timepicker-addon.css" />
    
    <script type="text/javascript" src="/assets/jquerypp/jquerypp.custom.js"></script>
    
    <script type="text/javascript" src="/assets/jqueryCookie/jquery.cookie.js"></script>
    
    <script type="text/javascript" src="/assets/tableSorter/jquery.tablesorter.min.js"></script>
    
    <script type="text/javascript" src="/assets/dataTables/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/assets/dataTables/jquery.dataTables.css" />
    
    <script type="text/javascript" src="/assets/inputLimiter/jquery.inputlimiter.js"></script>
    
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.js"></script>
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.ru.js"></script>
    <link rel="stylesheet" href="/assets/redactorjs/redactor.jquery.css" />
    
    
	<script type="text/javascript" src="/assets/codemirror/codemirror.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/codemirror/codemirror.css"/>
    
    <script type="text/javascript" src="/assets/codemirror/css/css.js"></script>
    <script type="text/javascript" src="/assets/codemirror/php/php.js"></script>
    <script type="text/javascript" src="/assets/codemirror/xml/xml.js"></script>
    <script type="text/javascript" src="/assets/codemirror/mysql/mysql.js"></script>
    <script type="text/javascript" src="/assets/codemirror/javascript/javascript.js"></script>
    
    <script type="text/javascript" src="/assets/autosize/jquery.autoresize.js"></script>
    
    <script type="text/javascript" src="/assets/switch/style-checkboxes.js"></script>
    <link rel="stylesheet" href="/assets/switch/style-checkboxes.css" />
    
    <script type="text/javascript" src="/assets/icheck/jquery.icheck.js"></script>
    <link rel="stylesheet" href="/assets/icheck/jquery.icheck.css" />
    
    <!--<script type="text/javascript" src="/assets/scrollbar/jquery.fs.scroller.js"></script>
    <link rel="stylesheet" href="/assets/scrollbar/jquery.fs.scroller.css" />-->
    
    <script type="text/javascript" src="/assets/uniform/jquery.uniform.js"></script>
    <link rel="stylesheet" href="/assets/uniform/jquery.uniform.base.css" />
    
    <script type="text/javascript" src="/assets/select2/select2.min.js"></script>
    <link rel="stylesheet" href="/assets/select2/select2.css" />
    
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine-ru.js"></script>
    <link rel="stylesheet" href="/assets/validationEngine/validationEngine.jquery.css" />
    
    <script type="text/javascript" src="/assets/expanding/expanding.js"></script>
    
    <script type="text/javascript" src="/assets/address/jquery.address.min.js"></script>
    
    <script type="text/javascript" src="/admin/{{tpl_dir}}/scripts/main.js"></script>
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'> 
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.css" />
    <link rel="stylesheet" href="/admin/{{tpl_dir}}/styles/main.css" />
</head>
<body>

<div class="ad-body a-clear">
	<div class="ad-top a-clear">
    	<div class="a-float-left">
        	<!--<div class="ad-load-info">
            	<span class="ad-loaded"></span>
                Идет сохранение данных...
            </div>-->
        </div>
        <div class="a-float-right">
        	<div class="a-group-btn a-margin-right">
            	<input name="form-save" id="form-save" class="a-btn-green form-submit" type="submit" value="Сохранить" />
                <input name="form-apply" id="form-apply" class="a-btn-green form-submit" type="submit" value="Применить" />
            </div>
            
            <input name="form-cancel" id="form-cancel" type="submit" class="a-btn form-submit" value="Отмена" />
        </div>
    </div>
    
	<div class="ad-left">
    	<div class="ad-info"></div>
    	<ul class="ad-left-menu">
        	<li>
            	<a href="#">
                	<i class="a-icon-user a-icon-white"></i> Пользователи
                </a>
                <ul>
                	<li>
                    	<a class="ajax-link" href="/users">Все пользователи</a>
                    </li>
                    <li>
                    	<a href="#">Администраторы</a>
                    </li>
                    <li>
                    	<a href="#">Неподтвержденные</a>
                    </li>
                    <li>
                    	<a href="#">Добавить пользователя</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-book a-icon-white"></i> Статьи
                </a>
                <ul>
                	<li>
                    	<a href="#">Все статьи</a>
                    </li>
                    <li>
                    	<a href="#">VIP статьи</a>
                    </li>
                    <li>
                    	<a href="#">Календарь выхода статей</a>
                    </li>
                    <li>
                    	<a href="#">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="#">Метки</a>
                    </li>
                    <li>
                    	<a href="#">Корзина статей</a>
                    </li>
                    <li>
                    	<a href="#">Добавить статью</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-shopping-cart a-icon-white"></i> Новые товары
                    <span class="ad-count">5</span>
                </a>
                <ul>
                	<li>
                    	<a href="#">Все товары</a>
                    </li>
                    <li>
                    	<a href="#">VIP товары</a>
                    </li>
                    <li>
                    	<a href="#">Не просмотреные</a>
                    </li>
                    <li>
                    	<a href="#">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="#">Корзина</a>
                    </li>
                    <li>
                    	<a href="#">Статистика</a>
                    </li>
                    <li>
                    	<a href="#">Добавить товар</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-bullhorn a-icon-white"></i> Товары Б/У
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-calendar a-icon-white"></i> Мероприятия
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-briefcase a-icon-white"></i> Работа
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-filter a-icon-white"></i> З/Т Лаб.
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-home a-icon-white"></i> Недвижимость
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-wrench a-icon-white"></i> Сервис
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-tasks a-icon-white"></i> Диагностика
                </a>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-edit a-icon-white"></i> Спрос
                </a>
            </li>
        </ul>
    </div>
    <div class="ad-right a-clear">
    	<h1 class="ad-title">
            <b>Добавить что-то</b>
            <span>Здесь что-то написано (для идиотов)</span>
        </h1>
    	{{form|raw}}
        <!--<h1 class="ad-title">
            <b>Tabs</b>
            <span>tabs</span>
        </h1>
        <form method="post" action="" class="ad-form a-clear validation">  
            <div class="section">
                <ul class="tabs a-clear">
                    <li class="current">Основная информация</li>
                    <li>Текст статьи</li>
                </ul>
                <div class="box visible">
					<div class="a-row">
                        <label><font class="a-red">*</font> Заголовок статьи </label>
                        <input class="validate[required]" maxlength="80" type="text" name="ar-title" id="ar-title" />
                    </div>
                </div>
                <div class="box">
                    <textarea class="editor" name="ar-content" id="ar-content"></textarea>
                </div>
            </div>
        </form>
        
        <h1 class="ad-title">
        	<b>Формы</b>
            <span>form</span>
        </h1>
       
       <form class="ad-form a-clear validation">
            <div class="a-row">
                <label><font class="a-red">*</font> Заголовок статьи </label>
                <input class="validate[required]" maxlength="80" type="text" name="ar-title" id="ar-title" />
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
                <label>Ваши пожелания по дате размещения и оформлению статьи</label>
                <textarea class="autosize" name="ar-user-comment" id="ar-user-comment"></textarea>
            </div>
        </form>
    
    	<h1 class="ad-title">
        	<b>TableSorter jQuery plugin</b>
            <span>Tablesorter</span>
        </h1>
       
       <table class="a-table tablesorter"> 
            <thead> 
            <tr> 
                <th class="header">Last Name</th> 
                <th class="header">First Name</th> 
                <th class="header">Email</th> 
                <th class="header">Due</th> 
                <th class="header headerSortDown">Web Site</th> 
            </tr> 
            </thead> 
            <tbody> 
            <tr> 
                <td>Bach</td> 
                <td>Frank</td> 
                <td>fbach@yahoo.com</td> 
                <td>$50.00</td> 
                <td>http://www.frank.com</td> 
            </tr><tr> 
                <td>Doe</td> 
                <td>Jason</td> 
                <td>jdoe@hotmail.com</td> 
                <td>$100.00</td> 
                <td>http://www.jdoe.com</td> 
            </tr><tr> 
                <td>Smith</td> 
                <td>John</td> 
                <td>jsmith@gmail.com</td> 
                <td>$50.00</td> 
                <td>http://www.jsmith.com</td> 
            </tr><tr> 
                <td>Conway</td> 
                <td>Tim</td> 
                <td>tconway@earthlink.net</td> 
                <td>$50.00</td> 
                <td>http://www.timconway.com</td> 
            </tr></tbody> 
        </table>
       
       <p>&nbsp;</p>
       
       <h1 class="ad-title">
        	<b>dataTable jQuery plugin</b>
            <span>dataTable</span>
        </h1>
       
       <table class="a-table datatables"> 
            <thead> 
            <tr> 
                <th class="header">Last Name</th> 
                <th class="header">First Name</th> 
                <th class="header">Email</th> 
                <th class="header">Due</th> 
                <th class="header headerSortDown">Web Site</th> 
            </tr> 
            </thead> 
            <tbody> 
            <tr> 
                <td>Bach</td> 
                <td>Frank</td> 
                <td>fbach@yahoo.com</td> 
                <td>$50.00</td> 
                <td>http://www.frank.com</td> 
            </tr><tr> 
                <td>Doe</td> 
                <td>Jason</td> 
                <td>jdoe@hotmail.com</td> 
                <td>$100.00</td> 
                <td>http://www.jdoe.com</td> 
            </tr><tr> 
                <td>Smith</td> 
                <td>John</td> 
                <td>jsmith@gmail.com</td> 
                <td>$50.00</td> 
                <td>http://www.jsmith.com</td> 
            </tr><tr> 
                <td>Conway</td> 
                <td>Tim</td> 
                <td>tconway@earthlink.net</td> 
                <td>$50.00</td> 
                <td>http://www.timconway.com</td> 
            </tr>
            <tr> 
                <td>Bach</td> 
                <td>Frank</td> 
                <td>fbach@yahoo.com</td> 
                <td>$50.00</td> 
                <td>http://www.frank.com</td> 
            </tr><tr> 
                <td>Doe</td> 
                <td>Jason</td> 
                <td>jdoe@hotmail.com</td> 
                <td>$100.00</td> 
                <td>http://www.jdoe.com</td> 
            </tr><tr> 
                <td>Smith</td> 
                <td>John</td> 
                <td>jsmith@gmail.com</td> 
                <td>$50.00</td> 
                <td>http://www.jsmith.com</td> 
            </tr><tr> 
                <td>Conway</td> 
                <td>Tim</td> 
                <td>tconway@earthlink.net</td> 
                <td>$50.00</td> 
                <td>http://www.timconway.com</td> 
            </tr>
            <tr> 
                <td>Bach</td> 
                <td>Frank</td> 
                <td>fbach@yahoo.com</td> 
                <td>$50.00</td> 
                <td>http://www.frank.com</td> 
            </tr><tr> 
                <td>Doe</td> 
                <td>Jason</td> 
                <td>jdoe@hotmail.com</td> 
                <td>$100.00</td> 
                <td>http://www.jdoe.com</td> 
            </tr><tr> 
                <td>Smith</td> 
                <td>John</td> 
                <td>jsmith@gmail.com</td> 
                <td>$50.00</td> 
                <td>http://www.jsmith.com</td> 
            </tr><tr> 
                <td>Conway</td> 
                <td>Tim</td> 
                <td>tconway@earthlink.net</td> 
                <td>$50.00</td> 
                <td>http://www.timconway.com</td> 
            </tr>
            <tr> 
                <td>Bach</td> 
                <td>Frank</td> 
                <td>fbach@yahoo.com</td> 
                <td>$50.00</td> 
                <td>http://www.frank.com</td> 
            </tr><tr> 
                <td>Doe</td> 
                <td>Jason</td> 
                <td>jdoe@hotmail.com</td> 
                <td>$100.00</td> 
                <td>http://www.jdoe.com</td> 
            </tr><tr> 
                <td>Smith</td> 
                <td>John</td> 
                <td>jsmith@gmail.com</td> 
                <td>$50.00</td> 
                <td>http://www.jsmith.com</td> 
            </tr><tr> 
                <td>Conway</td> 
                <td>Tim</td> 
                <td>tconway@earthlink.net</td> 
                <td>$50.00</td> 
                <td>http://www.timconway.com</td> 
            </tr>
            
            </tbody> 
        </table>-->
       
    </div>
    
    <div class="ad-footer">
    	<ul class="ad-footer-menu">
        	<li>
            	<a href="#">Управление шаблонами</a>
            </li>
            <li>
            	<a href="#">Настройки сайта</a>
            </li>
            <li>
            	<a href="#">Модули</a>
            </li>
        </ul>
    </div>
</div>
</body>
</html>