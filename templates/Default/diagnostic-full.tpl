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
    </div>
    <div class="col-2">
        {{ diagnostic.date_add|rusDate }}&nbsp; | &nbsp;
        {{diagnostic.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>
<h1>
	{{diagnostic.name|capitalize}}
</h1>
<div class="n-ad-full n-lab-full a-clear">
    <hr />
    
    <div class="n-ad-full-info a-clear">
        <div class="col-1">
            <span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{diagnostic.phones.0}}
        </div>
        <div class="col-2">
            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{diagnostic.user_name}}
        </div>
        <div class="col-3">
            <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> {{diagnostic.city_name}}, {{diagnostic.address}}
        </div>
    </div>
    
    <hr />
    
    <div class="col-1">
    	{% if diagnostic.url_full %}
        	<img src="/uploads/images/diagnostic/160x200/{{diagnostic.url_full}}" />
        {% else %}
        	<img src="/uploads/images/160x200.jpg" />
        {% endif %}
        
        <ul class="n-left-info-list">
            {% if diagnostic.attach %}
            <li>
            	<span class="navi-bg-blue"><i class="a-icon-file a-icon-white"></i></span> 
                <a target="_blank" href="http://navistom.com/uploads/docs/{{diagnostic.attach}}">Прайс-лист</a>
            </li>
            {% endif %}
        </ul>
    </div>
    <div class="col-2">
        {{diagnostic.content|raw|nl2br}}
    </div>
    
    <div class="a-clear"></div>
    
   {% if gallery or diagnostic.video_link or diagnostic.address %}
    
    <div id="idTabs">
        <ul class="idTabs idTabsLeft a-clear">
        	{% if gallery %}
            <li>
                <a href="#ad-gallery-700">Фото</a>
            </li>
            {% endif %}
            {% if diagnostic.video_link %}
            <li>
                <a href="#ad-video">Видео</a>
            </li>
            {% endif %}
            {% if diagnostic.address %}
            <li>
                <a href="#n-ya-map">Карта</a>
            </li>
            {% endif %}
        </ul>
        
    {% endif %}
    
    {% if diagnostic.address %}
    <div  style="width:700px; height:400px;" id="n-ya-map"></div>
    <script type="text/javascript">
		var R = '{{diagnostic.city_name}}, {{diagnostic.address}}';
		window.my_map = function(ymaps){
		var myGeocoder = ymaps.geocode(R);
			myGeocoder.then(function (res) {
				var map = new ymaps.Map("n-ya-map", {
					center: res.geoObjects.get(0).geometry.getCoordinates(),
					zoom: 17, 
					type: "yandex#map"
				});
				map.controls
					.add("zoomControl")
					.add("mapTools")
					.add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
				map.geoObjects.add(res.geoObjects);
			});
		};					
	</script>

	<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=my_map"></script>
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
              <a href="/uploads/images/diagnostic/full/{{g.url_full}}">
                <img alt="{{g.description}}" src="/uploads/images/diagnostic/80x100/{{g.url_full}}" />
              </a>
            </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
    {% endif %}
    
    {% if diagnostic.video_link %}
    <div id="ad-video">
        <iframe width="700" height="394" src="//www.youtube.com/embed/{{diagnostic.video_link}}" frameborder="0" allowfullscreen></iframe>
    </div>
    {% endif %}
    
    {% if gallery or diagnostic.video_link or diagnostic.address %}
    </div>
    {% endif %}
    
    <p><br /></p>
    <div class="n-title">
        Написать автору
    </div>
    
    {% if user_info %}
    <div class="a-clear">
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/diagnostic/send-message-{{diagnostic.diagnostic_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{diagnostic.user_id}}" />
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