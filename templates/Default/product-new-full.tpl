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
        <a href="/products/sub_categ-{{product.sub_categ_id}}-{{product.categ_name|translit}}">{{product.categ_name}}</a>
    </div>
    <div class="col-2">
        {{ product.date_add|rusDate }}&nbsp; | &nbsp;
        {{product.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>
<h1>
	{% if product.stock_flag %} <span class="navi-stock-marker">Акция</span> {% endif %}
	{{product.product_name}}
</h1>
<div class="a-font-smal a-color-gray">{{product.description}}</div>
<div class="n-ad-full a-clear">
    <hr />
    
    <div class="n-ad-full-info a-clear">
        <div class="col-1">
            <span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{product.phones.0}}
        </div>
        <div class="col-2">
            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{product.user_name}}
        </div>
        <div class="col-3">
            <div class="n-ad-full-price n-price">{{product.price}} {{product.currency_name}}</div>
        </div>
    </div>
    
    <hr />
    
    <div class="col-1">
        <img src="/uploads/images/products/160x200/{{product.image}}" />
    </div>
    <div class="col-2">
        {% if product.content %}
        	{{product.content|nl2br}}
        {% else %}
        	{{product.description}}
        {% endif %}
    </div>
    
    <div class="a-clear"></div>
    
    {% if gallery or product.video_link %}
    
    <div id="idTabs">
        <ul class="idTabs idTabsLeft a-clear">
            {% if gallery %}
            <li>
                <a href="#ad-gallery-700">Фото</a>
            </li>
            {% endif %}
            {% if product.video_link %}
            <li>
                <a href="#ad-video">Видео</a>
            </li>
            {% endif %}
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
              <a href="/uploads/images/products/full/{{g.url_full}}">
                <img alt="{{g.description}}" src="/uploads/images/products/80x100/{{g.url_full}}" />
              </a>
            </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
    {% endif %}
    
    {% if product.video_link %}
    <div id="ad-video">
        <iframe width="700" height="394" src="//www.youtube.com/embed/{{product.video_link}}" frameborder="0" allowfullscreen></iframe>
    </div>
    {% endif %}
    
    {% if gallery or product.video_link %}
    </div>
    {% endif %}
    
    <p><br /></p>
    <div class="n-title">
        Написать автору
    </div>
    
    {% if user_info %}
    <div class="a-clear">
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/product/send-message-{{product.product_new_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{product.user_id}}" />
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
    <!-- AddThis Button BEGIN -->
    <div style="width:300px; float:left;" class="addthis_toolbox addthis_default_style ">
        <a class="addthis_button_vk"></a>
        <a class="addthis_button_facebook"></a>
        <a class="addthis_button_odnoklassniki_ru"></a>
        <a class="addthis_button_twitter"></a>
        <a class="addthis_button_google_plusone_share"></a>
        <a class="addthis_button_compact"></a>
    </div>
    <script type="text/javascript">
        var addthis_config = {
            "data_track_addressbar":false,
            "pubid" : "ra-4f7d770f68a3c8a2"
        };
        
        var addthis_share = {
           "url" : "",
           "title" : "",
           "description" : ""
        }
        
    </script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f7d770f68a3c8a2"></script>
</div>
{% endblock %}