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
        <a href="/ads/sub_categ-{{product.sub_categ_id}}-{{product.categ_name|translit}}">{{ads.categ_name}}</a>
    </div>
    <div class="col-2">
        {{ ads.date_add|rusDate }}&nbsp; | &nbsp;
        {{ads.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>
<h1>
	{{ads.product_name}}
</h1>
<div class="a-font-smal a-color-gray">{{ads.description}}</div>
<div class="n-ad-full a-clear">
    <hr />
    
    <div class="n-ad-full-info a-clear">
        <div class="col-1">
            <span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{ads.phones.0}}
        </div>
        <div class="col-2">
            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{ads.user_name}}
        </div>
        <div class="col-3">
            <div class="n-ad-full-price n-price">{{ads.price}} {{ads.currency_name}}</div>
        </div>
    </div>
    
    <hr />
    
    <div class="col-1">
        <img src="/uploads/images/ads/160x200/{{ads.image}}" />
    </div>
    <div class="col-2">
        {% if ads.content %}
        	{{ads.content|nl2br}}
        {% else %}
        	{{ads.description}}
        {% endif %}
    </div>
    
    <div class="a-clear"></div>
    
    {% if gallery and ads.video_link %}
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
              <a href="/uploads/images/ads/full/{{g.url_full}}">
                <img alt="{{g.description}}" src="/uploads/images/ads/80x100/{{g.url_full}}" />
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
        <iframe width="700" height="394" src="//www.youtube.com/embed/{{ads.video_link}}" frameborder="0" allowfullscreen></iframe>
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
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/ads/send-message-{{ads.ads_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{ads.user_id}}" />
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