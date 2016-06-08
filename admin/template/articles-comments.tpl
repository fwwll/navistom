{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    <ul class="ad-comments-list">
    {% for c in comments %}
    	<li>
        	<a href="/admin/article/edit-{{c.article_id}}" class="comm-article-name">{{c.name}}</a>
            <span class="comm-date-add"><i class="a-icon-calendar"></i> {{c.date_add|rusDate}}</span>
        	<div class="comm-content">{{c.comment|raw|nl2br}}</div>
            <span class="comm-user"><i class="a-icon-user"></i> {{c.user_name}}</span>
            <span class="comm-options">
            	<a class="delete-link" href="/admin/articles/comment/delete-{{c.comment_id}}"><i class="a-icon-remove"></i></a>&nbsp;
                <a href="/admin/articles/comment/edit-{{c.comment_id}}"> <i class="a-icon-pencil"></i></a>
            </span>
        </li>
    {% endfor %}
    </ul>
    
{% endblock %}

{% block right %}
	<a href="/admin/article/add" class="a-btn-green">Добавить статью</a>
{% endblock %}