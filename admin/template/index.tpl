<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{% block title %} Панель управления сайтом Navistom.net {% endblock %}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
    
    <meta http-equiv="Pragma" content="no-cache" />
    
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
    
    <script type="text/javascript" src="/assets/pGenerator/pGenerator.jquery.js"></script>
    
    <script type="text/javascript" src="/assets/mask/jquery.mask.js"></script>
    
    <script type="text/javascript" src="/assets/tableSorter/jquery.tablesorter.min.js"></script>
    
    <script type="text/javascript" src="/assets/highcharts/highcharts.js"></script>
    
    <script type="text/javascript" src="/assets/customScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <link rel="stylesheet" href="/assets/customScrollbar/jquery.mCustomScrollbar.css" />
    
    <script type="text/javascript" src="/assets/dataTables/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/assets/dataTables/jquery.dataTables.css" />
    
    <script type="text/javascript" src="/assets/inputLimiter/jquery.inputlimiter.js"></script>
    
    <script type="text/javascript" src="/assets/redactorjs/jquery.redactor.min.9.10.js"></script>
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.ru.js"></script>
    <link rel="stylesheet" href="/assets/redactorjs/jquery.redactor.9.10.css" />
    
    <script type="text/javascript" src="/assets/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/moment/lang/ru.js"></script>
    
	<script type="text/javascript" src="/assets/codemirror/codemirror.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/codemirror/codemirror.css"/>
    
    <script type="text/javascript" src="/assets/tagsInput/jquery.tagsinput.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/tagsInput/jquery.tagsinput.css"/>
    
    <script type="text/javascript" src="/assets/fullcalendar/fullcalendar.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/fullcalendar/fullcalendar.css"/>
    
    <script type="text/javascript" src="/assets/confirm/jquery.confirm.js"></script>
    <link rel="stylesheet" href="/assets/confirm/jquery.confirm.css" />
    
    <script type="text/javascript" src="/assets/codemirror/css/css.js"></script>
    <script type="text/javascript" src="/assets/codemirror/php/php.js"></script>
    <script type="text/javascript" src="/assets/codemirror/xml/xml.js"></script>
    <script type="text/javascript" src="/assets/codemirror/mysql/mysql.js"></script>
    <script type="text/javascript" src="/assets/codemirror/javascript/javascript.js"></script>
    
    <script type="text/javascript" src="/assets/fineuploader/jquery.fineuploader.js"></script>
    
    <script type="text/javascript" src="/assets/autosize/jquery.autoresize.js"></script>
    
    <script type="text/javascript" src="/assets/switch/style-checkboxes.js"></script>
    <link rel="stylesheet" href="/assets/switch/style-checkboxes.css" />
    
    <script type="text/javascript" src="/assets/mtip/mtip.js"></script>
    <link rel="stylesheet" href="/assets/mtip/mtip.css" />
    
    <script type="text/javascript" src="/assets/icheck/jquery.icheck.js"></script>
    <link rel="stylesheet" href="/assets/icheck/jquery.icheck.css" />
    
    <script type="text/javascript" src="/assets/uniform/jquery.uniform.js"></script>
    <link rel="stylesheet" href="/assets/uniform/jquery.uniform.base.css" />
    
    <script type="text/javascript" src="/assets/select2/select2.min.js"></script>
    <link rel="stylesheet" href="/assets/select2/select2.css" />
    
    <script type="text/javascript" src="/assets/jqueryForm/jquery.form.js"></script>
    
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine-ru.js"></script>

	

	
    <link rel="stylesheet" href="/assets/validationEngine/validationEngine.jquery.css" />
    
    <script type="text/javascript" src="/assets/expanding/expanding.js"></script>
    
    <script type="text/javascript" src="/assets/address/jquery.address.min.js"></script>
    
    <script type="text/javascript" src="/assets/knob/jquery.knob.js"></script>
    
    <script type="text/javascript" src="/admin/{{tpl_dir}}/scripts/main.js?{{md5time}}"></script>
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'> 
    
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.css" />
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.box.css" />
    <link rel="stylesheet" href="/admin/{{tpl_dir}}/styles/main.css" />
</head>
<body>
<div class="ad-body a-clear">
	<div class="ad-top a-clear">
    	<div style="margin-left:270px" class="a-float-left">
                <a href="http://navistom.com/" target="_blank">Перейти на сайт</a>
                &nbsp;|&nbsp;
                <a target="_blank" href="/cabinet">Личный кабинет</a>
                 &nbsp;|&nbsp;
                 <a href="/admin/exit">Выход</a>
                 &nbsp;&nbsp;
                 {% if banners_ended_count > 0%}
                 <a href="/admin/banners"> <span class="a-count-red">{{banners_ended_count}}</span> Срок отображения баннеров подходит к концу</a>
                 {% endif %}
        	{% block left %} {{status}} {% endblock %}
        </div>
        <div class="a-float-right">
        	{% block right %} {% endblock %}
        </div>
    </div>
    
	<div class="ad-left">
    	<a href="/admin" class="ad-info"></a>
        <div id="scroll">
    	<ul class="ad-left-menu">
        	 <li>
            	<a href="#">
                	<i class="a-icon-envelope a-icon-white"></i> Обратная связь
                    {% if moder_count.user_errors > 0 or moder_count.user_feedback_mess > 0 or moder_count.user_access_mess > 0 %}
                    <span class="ad-count">{{moder_count.user_errors + moder_count.user_feedback_mess + moder_count.user_access_mess}}</span>
                    {% endif %}
                </a>
                <ul>
                    <li>
                        <a href="/admin/feedback/access">
                            Заявки на доступ
                            {% if moder_count.user_access_mess > 0 %}
                                <span class="ad-count">{{moder_count.user_access_mess}}</span>
                            {% endif %}
                        </a>
                    </li>
                	<li>
                    	<a href="/admin/feedback">
                        	Письма из обратной связи
                            {% if moder_count.user_feedback_mess > 0 %}
                            <span class="ad-count">{{moder_count.user_feedback_mess}}</span>
                            {% endif %}
                        </a>
                    </li>
                	<li>
                    	<a href="/admin/feedback/errors">
                        	Сообщения об ошибках
                            {% if moder_count.user_errors > 0 %}
                            <span class="ad-count">{{moder_count.user_errors}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/feedback/mess-tpls">
                        	Настройка шаблонов писем
                        </a>
                    </li>
					<li>
                    	<a href="/admin/users/zayavka">
                        	Заявки на удаление 
							
							{%if zavavka_count > 0%}
							<span class="ad-count">{{zavavka_count}}</span>
							{% endif %}
                        </a>
                    </li>
					
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-book a-icon-white"></i> Статьи
                    {% if moder_count.articles > 0 %}
                    <span class="ad-count">{{moder_count.articles}}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/article/add">Добавить статью</a>
                    </li>
                    <li>
                    	<a href="/admin/articles/moder">
                        	На модерации
                            {% if moder_count.articles > 0 %}
                            <span class="ad-count">{{moder_count.articles}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/articles/calendar">Календарь выхода статей</a>
                    </li>
                	<li>
                    	<a href="/admin/articles">Все статьи</a>
                    </li>
     				<li>
                    	<a href="/admin/articles/comments">Комментарии</a>
                    </li>
                    <li>
                    	<a href="/admin/articles/interviews">Опросы</a>
                    </li>
                    <li>
                    	<a href="/admin/articles/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/articles/tags">Метки</a>
                    </li>
                    <li>
                    	<a href="/admin/articles/statistic">Статистика статей</a>
                    </li>
                    <li>
                    	<a href="/admin/articles/removed">Корзина статей</a>
                    </li>
                    
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-shopping-cart a-icon-white"></i> Продам новое
                    {% if moder_count.products_new > 0 or moder_count.producers > 0 or moder_count.products > 0 %}
                    <span class="ad-count">{{moder_count.products_new + moder_count.producers + moder_count.products}}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/products">Все предложения</a>
                    </li>
                    <li>
                    	<a href="/admin/products/filter-moderation">
                        	На модерации 
                        	{% if moder_count.products_new > 0 %}
                            <span class="ad-count">{{moder_count.products_new}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/products/filter-stocks">Акционные предложения</a>
                    </li>
                    <li>
                    	<a href="/admin/products/filter-stocks/moderation">Акции на модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/products/producers">
                        	Производители
                            {% if moder_count.producers > 0 %}
                            <span class="ad-count">{{moder_count.producers}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/products/producers_products">
                        	Производитель + товар
                        	{% if moder_count.products > 0 %}
                            <span class="ad-count">{{moder_count.products}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/products/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/products/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/products/filter-removed">Корзина продам новое</a>
                    </li>
                    
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-bullhorn a-icon-white"></i> Продам Б/У
                    {% if moder_count.ads > 0 %}
                    <span class="ad-count">{{moder_count.ads}}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/ads">Все Б/У предложения</a>
                    </li>
                    <li>
                    	<a href="/admin/ads/filter-moderation">
                        	На модерации
                            {% if moder_count.ads > 0 %}
                            <span class="ad-count">{{moder_count.ads}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/ads/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/ads/filter-removed">Корзина продам Б/У</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-wrench a-icon-white"></i> Сервис
                </a>
                <ul>
                	<li>
                    	<a href="/admin/services">Все предложения</a>
                    </li>
                    <li>
                    	<a href="/admin/services/filter-moderation">На модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/services/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/services/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/services/filter-removed">Корзина сервис</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-edit a-icon-white"></i> Спрос
                </a>
                <ul>
                	<li>
                    	<a href="/admin/demand">Все заявки</a>
                    </li>
                    <li>
                    	<a href="/admin/demand/filter-moderation">На модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/demand/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/demand/filter-removed">Корзина спрос</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-calendar a-icon-white"></i> Мероприятия
                    {% if moder_count.activity > 0 %}
                    <span class="ad-count">{{moder_count.activity}}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/activity">Все мероприятия</a>
                    </li>
                    <li>
                    	<a href="/admin/activity/filter-moderation">На модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/activity/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/activity/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/activity/filter-removed">Корзина мероприятия</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-briefcase a-icon-white"></i> Работа
                </a>
                <ul>
                	<li>
                    	<a href="/admin/work/vacancy">Все вакансии</a>
                    </li>
                    <li>
                    	<a href="/admin/work/vacancy/filter-moderation">Вакансии на модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/work/resume">Все резюме</a>
                    </li>
                    <li>
                    	<a href="/admin/work/resume/filter-moderation">Резюме на модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/work/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/work/vacancy/statistic">Статистика вакансии</a>
                    </li>
                    <li>
                    	<a href="/admin/work/resume/statistic">Статистика резюме</a>
                    </li>
                    <li>
                    	<a href="/admin/work/vacancy/filter-removed">Корзина вакансии</a>
                    </li>
                    <li>
                    	<a href="/admin/work/resume/filter-removed">Корзина резюме</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-filter a-icon-white"></i> З/Т лаборатории
                    {% if moder_count.labs > 0 %}
                    <span class="ad-count">{{moder_count.labs}}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/labs">
                        	Все З/Т лаборатории
                            {% if moder_count.labs > 0 %}
                            <span class="ad-count">{{moder_count.labs}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/labs/filter-moderation">На модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/labs/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/labs/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/labs/filter-removed">Корзина З/Т лаборатории</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-home a-icon-white"></i> Недвижимость
                    {% if moder_count.realty > 0 %}
                    <span class="ad-count">{{moder_count.realty}}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/realty">
                        	Вся недвижимость
                            {% if moder_count.realty > 0 %}
                            <span class="ad-count">{{moder_count.realty}}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/realty/filter-moderation">На модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/realty/categories">Рубрикатор</a>
                    </li>
                    <li>
                    	<a href="/admin/realty/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/realty/filter-removed">Корзина недвижимость</a>
                    </li>
                </ul>
            </li>
            
            <!--li>
            	<a href="#">
                	<i class="a-icon-tasks a-icon-white"></i> Диагностика
                </a>
                <ul>
                	<li>
                    	<a href="/admin/diagnostic">Все предложения</a>
                    </li>
                    <li>
                    	<a href="/admin/diagnostic/filter-moderation">На модерации</a>
                    </li>
                    <li>
                    	<a href="/admin/diagnostic/statistic">Статистика</a>
                    </li>
                    <li>
                    	<a href="/admin/diagnostic/filter-removed">Корзина дигностика</a>
                    </li>
                </ul>
            </li-->
            <li>
            	<a href="#">
                	<i class="a-icon-user a-icon-white"></i> Пользователи
                    {% if users_moder > 0 or usersAccessWarningsCount > 0 %}
                        <span class="ad-count">{{ users_moder + usersAccessWarningsCount }}</span>
                    {% endif %}
                </a>
                <ul>
                	<li>
                    	<a href="/admin/users">
                            Все пользователи
                            {% if users_moder > 0 %}
                                <span class="ad-count">{{ users_moder }}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/users/administrators">Администраторы</a>
                    </li>
                    <li>
                    	<a href="/admin/users/unconfirmed">Неподтвержденные</a>
                    </li>
                    <li>
                        <a href="/admin/users/unique-permissions">
                            Заканчивается проплата
                            {% if usersAccessWarningsCount > 0 %}
                                <span class="ad-count">{{ usersAccessWarningsCount }}</span>
                            {% endif %}
                        </a>
                    </li>
                    <li>
                    	<a href="/admin/user/add">Добавить пользователя</a>
                    </li>
                    <li>
                    	<a href="/admin/users/groups">Группы пользователей</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-cog a-icon-white"></i> Управление разделами сайта
                </a>
                <ul>
                	<li>
                    	<a href="/admin/sections">Все разделы</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-signal a-icon-white"></i> Статистика
                </a>
                <ul>
                	<li>
                    	<a href="/admin/statistic">Статистика портала</a>
                    </li>
                    <li>
                    	<a href="/admin/statistic/subscribe">Статистика подписки</a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-folder-close a-icon-white"></i> Журнал
                </a>
                <ul>
                	<li>
                    	<a href="/admin/journal/add">
                        	Добавить журнал
                        </a>
                    </li>
                	<li>
                    	<a href="/admin/journal">
                        	Все журналы
                        </a>
                    </li>
					<li>
                    	<a href="/admin/publications">
                        	Публикаций в журнале
                        </a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-leaf a-icon-white"></i> Управление баннерами
                </a>
                <ul>
                	<li>
                    	<a href="/admin/banners">
                        	Все баннера
                        </a>
                    </li>
                    <li>
                        <a href="/admin/top-providers">
                            Топ поставщики
                        </a>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="#">
                	<i class="a-icon-leaf a-icon-white"></i> Курс валют
                </a>
                <ul>
                	<li>
                    	<a href="/admin/exchange-rates">
                        	Настройка курса валют
                        </a>
                    </li>
                </ul>
            </li>
			
			<li>
            	<a href="#">
                	<i class="a-icon-leaf a-icon-white"></i> Платежи
                </a>
                <ul>
                	<li>
                    	<a href="/admin/payment/">
							Платежи
                        </a>
                    </li>
					<li>
                    	<a href="/admin/price">
							редактор цен
                        </a>
                    </li>
                </ul>
            </li>
			
			<li>
            	<a href="#">
                	<i class="a-icon-leaf a-icon-white"></i> Тексты
                </a>
                <ul>
                	<li>
                    	<a href="/admin/reclama/">
							реклама
                        </a>
                    </li>
                </ul>
            </li>
			<li>
            	<a href="#">
                	<i class="a-icon-leaf a-icon-white"></i> дополнительные мета теги
                </a>
                <ul>
                	<li>
                    	<a href="/admin/meta-abs-all-producers">
							ВСЕ ПРОИЗВОДИТЕЛИ В Б/У
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-abs-all-salespeople">
							ВСЕ ПРОДАВЦЫ В Б/У
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-abs-all-categories">
							ВСЕ РУБРИК В Б/У
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-products-all-producers">
							ВСЕ ПРОИЗВОДИТЕЛИ В НОВОЕ
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-products-all-salespeople">
							ВСЕ ПРОДАВЦЫ В НОВОЕ
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-products-all-categories">
							ВСЕ РУБРИК В НОВОЕ
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-activity-all-users">
							   ВСЕ ОРГАНИЗАТОРЫ МЕРОПРИЯТИЙ  
                        </a>
                    </li>
					<li>
                    	<a href="/admin/meta-journals">
						  ЖУРНАЛ  
                        </a>
                    </li>
                </ul>
            </li>
           </ul>
		   
        </div>
    </div>
    <div class="ad-right a-clear">
        {% block content%}
        
        {% endblock %}
    </div>
    
    <div class="ad-footer">
    	<ul class="ad-footer-menu">
        	<li>
            	<a href="/admin/templates">Управление шаблонами</a>
            </li>
            <li>
            	<a href="/admin/settings">Настройки сайта</a>
            </li>
            <li>
            	<a href="/admin/modules">Модули</a>
            </li>
        </ul>
    </div>
</div>
</body>
</html>