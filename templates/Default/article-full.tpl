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

<div class="n-article-full">
    <div class="n-modal-top-info a-clear">
        <div class="col-1">
        	{% for key, value in article.categs %}
                <a href="/articles/categ-{{key}}-{{value|translit}}" class="a-color-gray">{{value}}</a>
            {% endfor %}
        </div>
        <div class="col-2">
            {{ article.date_public|rusDate }}&nbsp; | &nbsp;
            {{comm_count}} <i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
            {{ article.views }} <i class="a-icon-eye-open a-icon-gray"></i>
        </div>
    </div>
    <h1>{{article.name}}</h1>
    
    {{article.content|raw}}
    
    <p>&nbsp;</p>
    
    {% if gallery and article.video_link %}
    <div id="idTabs">
        <ul class="idTabs idTabsLeft a-clear">
            <li>
                <a href="#ad-gallery">Фото</a>
            </li>
            <li>
                <a href="#ad-video">Видео</a>
            </li>
        </ul>
    {% endif %}
    
    {% if gallery %}
    <div id="ad-gallery" class="ad-gallery">
      <div class="ad-image-wrapper">
      </div>
      <div class="ad-nav">
        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
            {% for g in gallery %}
            <li>
              <a href="/uploads/images/articles/full/{{g.url_full}}">
                <img alt="{{g.description}}" src="/uploads/images/articles/100x150/{{g.url_full}}" />
              </a>
            </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
    {% endif %}
    
    {% if article.video_link %}
    <div id="ad-video">
        <iframe width="600" height="394" src="//www.youtube.com/embed/{{article.video_link}}" frameborder="0" allowfullscreen></iframe>
    </div>
    {% endif %}
    
    {% if gallery and article.video_link %}
    	</div>
    {% endif %}
    
    {% if article.author %}
    <div class="n-article-author">
        <a href="#">
            <i class="a-icon-user a-icon-gray"></i> {{article.author}}
        </a>
    </div>
    {% endif %}
    
    <div class="a-font-smal a-color-gray">
        <i class="a-icon-tags a-icon-gray"></i>&nbsp;
        {% for key, value in article.tags %}
            <a href="/articles/tag-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
        {% endfor %}
        <p>&nbsp;</p>
    </div>
    <div class="a-font-smal a-color-gray">
    	<i class="a-icon-link a-icon-gray"></i>&nbsp; 
    	<a target="_blank" href="{{ article.source_link}}">
        	{{ article.source_name }}
        </a>
    	<p>&nbsp;</p>
    </div>
    
    {% if interview %}
        <div class="n-title">
            Опрос
        </div>
        
        <b>{{interview.vote.name}}</b>
        
        {% if is_user_vote or is_user == 0 %}
        	<div class="n-interview-result a-clear">
                {% for v in interview.versions %}
                    <div class="n-interview-desc">{{v.name}}</div>
                    <div class="n-inerview-bg">
                        <div style="width:{{'%.0f'|format( (v.count * 100) / interview.sum )}}%" class="n-inerview-res"></div>
                        <span>{{v.count}}</span>
                    </div>
                    <div class="n-interview-right">{{'%.0f'|format( (v.count * 100) / interview.sum )}}%</div>
                {% endfor %}
            </div>
        {% else %}
        	<div id="article-votes-list">
                <form id="article-vote-add" name="article-vote" method="post" action="/index.ajax.php?route=/article/vote_result_add">
                    <input type="hidden" name="vote_id" value="{{interview.vote.vote_id}}" />
                    {% for key, value in interview.versions %}
                        <div class="a-row">
                            <input type="radio" name="version_id" value="{{key}}" id="version_{{key}}" />
                            <label for="version_{{key}}">{{value}}</label>
                        </div>
                    {% endfor %}
                </form>
            </div>
        {% endif %}
        
        <p><br /></p>
        
    {% endif %}
    
    <div class="n-title">
        Комментарии
    </div>
    
    <div class="n-comments a-clear">
    
    	<div id="comment-list">
            {% for c in comments %}
                <div class="n-comment a-clear">
                    <div class="col-1">
                        <img src="/uploads/users/avatars/tumb2/{{c.avatar}}" />
                    </div>
                    <div class="col-2">
                        <div class="n-ad-top-info a-clear">
                            <div class="col-1">
                                <a href="#"><i class="a-icon-user a-icon-gray"></i> {{c.name}}</a>
                            </div>
                            <div class="col-2">
                                {{ c.date_add|rusDate }}
                            </div>
                        </div>
                        {{c.comment}}
                    </div>
                </div>
            {% else %}
                <p class="n-no-comments">К этой статье пока нет комментариев</p>
            {% endfor %}
        </div>
        
        <br />
        
        <div class="n-title">
            Добавить комментарий
        </div>
        
        {% if is_user %}
        
        <form id="n-comment-add" class="n-comment-add a-clear ajax-form" method="post" action="/index.ajax.php?route=/article/comment_add">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="comment"></textarea>
            <input type="hidden" name="article_id" value="{{article.article_id}}" />
            <div class="a-float-right">
                <input class="a-btn-green" type="submit" value="Отправить" />
            </div>
        </form>
        
        {% else %}
        
        <div class="a-mess-yellow">
        	Добавлять комментарии могут только зарегистрированные пользователи
        </div>
        
        {% endif %}
    </div>
    
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
           "url" : "http://navistom.com/ua/#/article/{{article.article_id}}-{{article.name|translit}}",
           "title" : "",
           "description" : ""
        }
        
    </script>
    <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f7d770f68a3c8a2"></script>
</div>
{% endblock %}