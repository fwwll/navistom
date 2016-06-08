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
    	{% for key, value in resume.categs %}
        	<a href="/activity/categ-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
        {% endfor %}
    </div>
    <div class="col-2">
        {{ resume.date_add|rusDate }}&nbsp; | &nbsp;
        {{resume.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>

<div class="n-ad-full a-clear">
    <div class="col-1">
        {% if resume.image != '' %}
            <img src="/uploads/images/work/160x200/{{resume.image}}" />
        {% elseif resume.avatar != ''%}
            <img src="/uploads/users/avatars/full/{{resume.avatar}}" />
        {% else %}
        
        {% endif %}
    </div>
    <div class="col-2">
    	<h1>{{resume.user_surname}} {{resume.user_name}} {{resume.user_firstname}}</h1>
    	<span class="resume-user-descr">{{resume.categs|join(', ')}}</span>
        
        <dl class="resume-info-list">
            <dt>Дата рождения:</dt>
            <dd>{{resume.user_brith|rusDate}} &nbsp;<span class="a-color-gray">({{resume.years}} {{resume.years|getNameYears}})</span></dd>
            <dt>Желаемый город работы:</dt>
            <dd>{{resume.city_name}}</dd>
            <dt>Занятость:</dt>
            <dd>
            	{% if resume.employment_type == 1 %}
                	полная занятость
                {% elseif resume.employment_type == 2%}
                	неполная занятость
                {% else %}
                	удаленная работа
                {% endif %}
            </dd>
            <dt>Зарплата:</dt>
            <dd><span class="n-price"><b>от {{resume.price|number_format(0, '', ' ')}} {{resume.currency_name}}</b></span></dd>
        </dl>
    </div>
    
    <div class="a-clear"><p>&nbsp;</p></div>
    
    <dl class="resume-data-list a-clear">
    	<dt>
        	Контактная информация
        </dt>
        <dd>
        	<dl class="resume-info-list">
            <dt>Телефон:</dt>
            <dd>{{resume.contact_phones}}</dd>
            <dt>Город проживания:</dt>
            <dd>{{resume.user_city}}</dd>
            </dl>
        	<!--<p>Телефон: <strong>{{resume.contact_phones}}</strong><br /></p>
            <p>Город проживания:</p>-->
        </dd>
    	{% if employment %}
    	<dt>
        	Опыт работы
        </dt>
        <dd>
        	{% for e in employment %}
            <p>
            	<b>{{e.position}}</b>
                {{e.company_name}} &nbsp;<span class="a-color-gray">({{e.activity}})</span> <br />
            	c {{e.date_start}} по {{e.date_end}}
            </p>
            {% endfor %}
        </dd>
        {% endif %}
        {% if education %}
        <dt>
        	Образование
        </dt>
        <dd>
        	{% for e in education %}
            <p>
            	<b>
                {% if e.type == 1 %}
                    Высшее
                {% elseif e.type == 2%}
                    Неоконченное высшее
                {% elseif e.type == 3%}
                    Среднее специальное
                {% else %}
                    Среднее
                {% endif %}
                </b>
                c {{e.date_start}} по {{e.date_end}} <br />
        		{{e.institution}},&nbsp; {{e.faculty}},&nbsp; {{e.location}}
            </p>
            {% endfor %}
        </dd>
        {% endif %}
        {% if traning %}
        <dt>
        	Дополнительное образование
        </dt>
        <dd>
        	{% for t in traning %}
            <p>
            	<b>{{t.name}}</b>
                {{t.description}}
            </p>
            {% endfor %}
        </dd>
        {% endif %}
        {% if langs %}
        <dt>
        	Владение языками
        </dt>
        <dd>
        	{% for l in langs %}
            <p>
            	<b>{{l.name}}</b>
                Уровень: 
                {% if l.level == 1 %}
                    Начинающий
                {% elseif l.level == 2%}
                    Средний
                {% else %}
                    Эксперт
                {% endif %}
            </p>
            {% endfor %}
        </dd>
        {% endif %}
        {% if resume.content %}
        <dt>
        	Дополнительно
        </dt>
        <dd>
        	<p>{{resume.content|raw|nl2br}}</p>
        </dd>
        {% endif %}
    </dl>
    
    {% if gallery and resume.video_link %}
    <div id="idTabs">
        <ul class="idTabs idTabsLeft a-clear">
            <li>
                <a href="#ad-gallery-700">Фото</a>
            </li>
            <li>
                <a href="#ad-video">Видео</a>
            </li>
        </ul>
    {% endif %}
    
    {% if gallery %}
    <div id="ad-gallery-700" class="ad-gallery">
      <div class="ad-image-wrapper">
      </div>
      <div class="ad-nav">
        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
            {% for g in gallery %}
            <li>
              <a href="/uploads/images/work/full/{{g.url_full}}">
                <img alt="{{g.description}}" src="/uploads/images/work/80x100/{{g.url_full}}" />
              </a>
            </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
    {% endif %}
    
    {% if resume.video_link %}
    <div id="ad-video">
        <iframe width="700" height="394" src="//www.youtube.com/embed/{{resume.video_link}}" frameborder="0" allowfullscreen></iframe>
    </div>
    {% endif %}
    
    {% if gallery and resume.video_link %}
    	</div>
    {% endif %}
    
    <p><br /><br /></p>
    <div class="n-title">
        Написать автору
    </div>
    
    {% if user_info %}
    <div class="a-clear">
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/work/send-message-{{resume.work_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{resume.user_id}}" />
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