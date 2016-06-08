<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{% block title %} Вход в панель управления сайтом Navistom.net {% endblock %}</title>
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
    
    <script type="text/javascript" src="/assets/dataTables/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/assets/dataTables/jquery.dataTables.css" />
    
    <script type="text/javascript" src="/assets/inputLimiter/jquery.inputlimiter.js"></script>
    
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.js"></script>
    <script type="text/javascript" src="/assets/redactorjs/redactor.jquery.ru.js"></script>
    <link rel="stylesheet" href="/assets/redactorjs/redactor.jquery.css" />
    
    <script type="text/javascript" src="/assets/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/moment/lang/ru.js"></script>
    
	<script type="text/javascript" src="/assets/codemirror/codemirror.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/codemirror/codemirror.css"/>
    
    <script type="text/javascript" src="/assets/fullcalendar/fullcalendar.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/fullcalendar/fullcalendar.css"/>
    
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
    
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="/assets/validationEngine/jquery.validationEngine-ru.js"></script>
    <link rel="stylesheet" href="/assets/validationEngine/validationEngine.jquery.css" />
    
    <script type="text/javascript" src="/assets/expanding/expanding.js"></script>
    
    <script type="text/javascript" src="/assets/address/jquery.address.min.js"></script>
    
    <script type="text/javascript" src="/admin/{{tpl_dir}}/scripts/main.js?{{md5time}}"></script>
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'> 
    <link rel="stylesheet" href="/assets/acorn-ui/acorn-ui.css" />
    <link rel="stylesheet" href="/admin/{{tpl_dir}}/styles/main.css" />
</head>
<body>
<div class="ad-body a-clear">
	<form method="post" class="ad-form aut-form" action="">
        <h1 class="ad-title">
            <b>Вход в панель управления</b>
            <span>NaviStom.com</span>
        </h1>
    	<div class="a-row">
        	<label for="aut_email">E-mail:</label>
            <input type="text" id="aut_email" name="aut_email" />
        </div>
        <div class="a-row">
        	<label for="aut_passw">Password:</label>
            <input type="password" id="aut_passw" name="aut_passw" />
        </div>
        <div class="a-row">
        	<label for="aut_passw">&nbsp;</label>
            <input type="submit" value="Вход" class="a-btn-green a-float-right" />
        </div>
    </form>
</div>
</body>
</html>