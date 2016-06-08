{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}
	{{meta.meta_title}}
{% endblock %}

{% block meta_description %}
	{{meta.meta_description}}
{% endblock %}

{% block meta_keys %}
	{{meta.meta_keys}}
{% endblock %}

{% block content %}
<div class="n-modal-top-info a-clear">
    <div class="col-1">
    	{% for key, value in vacancy.categs %}
        	<a href="/activity/categ-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
        {% endfor %}
    </div>
    <div class="col-2">
        {{ vacancy.date_add|rusDate }}&nbsp; | &nbsp;
        {{vacancy.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>

<div class="n-ad-full a-clear">
    <div class="col-1">
        {% if vacancy.logotype != '' %}
            <img src="/uploads/images/work/160x200/{{vacancy.logotype}}" />
        {% else %}
        
        {% endif %}
    </div>
    <div class="col-2">
        <h1>Требуется {{vacancy.categs|join(', ')|lower}}</h1>
        <span class="resume-user-descr">{{vacancy.company_name|raw}}</span>
        
        <dl class="resume-info-list">
            <dt>Город:</dt>
            <dd>{{vacancy.city_name}}</dd>
            <dt>Вид занятости:</dt>
            <dd>
            	{% if vacancy.type_id == 1 %}
                    полная занятость
                {% elseif vacancy.type_id == 2%}
                    неполная занятость
                {% else %}
                    удаленная работа
                {% endif %}
            </dd>
            {% if vacancy.price > 0 %}
                <dt>Зарплата:</dt>
                <dd><span class="n-price"><b>от {{vacancy.price|number_format(0, '', ' ')}} {{vacancy.currency_name}}</b></span></dd>
            {% endif %}
        </dl>
    </div>
    
    <div class="a-clear"><p>&nbsp;</p></div>
    <div class="a-clear"><p>&nbsp;</p></div>
    
    <dl class="resume-data-list a-clear">
    	<dt>
        	Контактная информация
        </dt>
        <dd>
        	<dl class="resume-info-list">
                <dt>Телефон:</dt>
                <dd>{{vacancy.contact_phones}}</dd>
                <dt>Контактное лицо:</dt>
                <dd>{{vacancy.user_name}}</dd>
                {% if vacancy.site %}
                <dt>Веб-сайт:</dt>
                <dd><a target="_blank" href="{{vacancy.site}}">{{vacancy.site}}</a></dd>
                {% endif %}
            </dl>
        </dd>
        <dt>
        	Требования к соискателю
        </dt>
        <dd>
        	<dl class="resume-info-list">
                <dt>Опыт работы:</dt>
                <dd>
                {% if vacancy.experience_type == 3 %}
                    от 5 лет
                {% elseif vacancy.experience_type == 2 %}
                	от 2 лет
                {% elseif vacancy.experience_type == 1 %}
                	от 1 года
                {% else %}
                	не имеет значения
                {% endif %}
                </dd>
                <dt>Уровень образования:</dt>
                <dd>
                {% if vacancy.education_type == 4 %}
                    среднее
                {% elseif vacancy.education_type == 3 %}
                	среднее специальное
                {% elseif vacancy.education_type == 2 %}
                	неоконченное высшее
                {% elseif vacancy.education_type == 1 %}
                	высшее
                {% else %}
                	не имеет значения
                {% endif %}
                </dd>
            </dl>
        </dd>
        {% if vacancy.content %}
        <dt>
        	Описание вакансии
        </dt>
        <dd>
        	{{vacancy.content|raw|nl2br}}
        </dd>
        {% endif %}
        <dt>
        	Информация о компании
        </dt>
        <dd>
        	{{vacancy.description|raw|nl2br}}
        </dd>
    </dl>
    
    <p><br /></p>
    
    {% if vacancy.video_link %}
    <iframe width="700" height="394" src="//www.youtube.com/embed/{{vacancy.video_link}}" frameborder="0" allowfullscreen></iframe>
    {% endif %}
    
    <p><br /></p>
    <div class="n-title">
        Написать автору
    </div>
    
    {% if user_info %}
    <div class="a-clear">
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/work/vacancy/send-message-{{vacancy.vacancy_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{vacancy.user_id}}" />
            <div class="form-loader display-none">
                <i class="load"></i>
                Загрузка...
            </div>
            <div class="a-float-right">
                <input class="a-btn-green" type="submit" value="Отправить" />
            </div>
        </form>
    </div>
    {% else %}
    
    <div class="a-mess-yellow">
        Написать автору могут только зарегистрированные пользователи
    </div>
    
    {% endif %}
    
</div>

<div class="a-modal-footer a-clear">
    addThis
</div>
{% endblock %}