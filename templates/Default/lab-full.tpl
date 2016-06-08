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
    {% for key, value in lab.categs %}
    	<a href="/labs/categ-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
    {% endfor %}
    </div>
    <div class="col-2">
        {{ lab.date_add|rusDate }}&nbsp; | &nbsp;
        {{lab.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>
<h1>
	{{lab.categs|join(', ')|lower|capitalize}}
</h1>
<div class="n-ad-full n-lab-full a-clear">
    <hr />
    
    <div class="n-ad-full-info a-clear">
        <div class="col-1">
            <span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{lab.phones.0}}
        </div>
        <div class="col-2">
            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{lab.user_name}}
        </div>
        <div class="col-3">
            <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> {{lab.region_name}}, {{lab.address}}
        </div>
    </div>
    
    <hr />
    
    <div class="col-1">
        <img src="/uploads/images/labs/160x200/{{lab.image}}" />
        <ul class="n-left-info-list">
            {% if lab.attach %}
            <li>
            	<span class="navi-bg-blue"><i class="a-icon-file a-icon-white"></i></span> 
                <a target="_blank" href="http://navistom.com/uploads/docs/{{service.attach}}">Прайс-лист</a>
            </li>
            {% endif %}
        </ul>
    </div>
    <div class="col-2">
        {{lab.content|raw|nl2br}}
    </div>
    
    <div class="a-clear"></div>
    
    {% if gallery and lab.video_link %}
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
              <a href="/uploads/images/labs/full/{{g.url_full}}">
                <img alt="{{g.description}}" src="/uploads/images/labs/80x100/{{g.url_full}}" />
              </a>
            </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
    {% endif %}
    
    {% if ads.video_link %}
    <div id="ad-video">
        <iframe width="700" height="394" src="//www.youtube.com/embed/{{labs.video_link}}" frameborder="0" allowfullscreen></iframe>
    </div>
    {% endif %}
    
    {% if gallery and ads.video_link %}
    	</div>
    {% endif %}
    
    <p><br /></p>
    <div class="n-title">
        Написать автору
    </div>
    
    {% if user_info %}
    <div class="a-clear">
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/lab/send-message-{{lab.lab_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{lab.user_id}}" />
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