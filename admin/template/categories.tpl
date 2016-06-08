{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if parentCategories %}


    <ul class="sortable-list">
        <li class="head">
            <div class="col-id">#</div>
            <div class="col-name">Название категории</div>
            <div class="col-date">Дата добавления</div>
            <div class="col-date">Дата изменения</div>
            <div class="col-options">Опции</div>
        </li>
        {% for parent in parentCategories %}
        <li id="category-{{parent.categ_id}}">
            <div class="parent-row">
                <div class="col-id"> {{parent.categ_id}} </div>
                <div class="col-name">
                    <a href="/admin/products/category/edit-{{parent.categ_id}}"><strong>{{parent.name}}</strong></a>
                </div>
                <div class="col-date"> {{parent.date_add|rusDate}} </div>
                <div class="col-date"> {{parent.date_edit|rusDate}} </div>
                <div class="col-options">
                    <a href="/admin/products/category/edit-{{parent.categ_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="{{parent.name}}" class="delete-link" href="/admin/products/category/delete-{{parent.categ_id}}"><i class="a-icon-remove"></i></a>
                </div>
            </div>

            <ul>
                {% for child in childCategories[ parent.categ_id ] %}
                    <li id="category-{{child.categ_id}}">
                        <div class="col-id"> {{child.categ_id}} </div>
                        <div class="col-name">
                            &#8594; <a href="/admin/products/category/edit-{{child.categ_id}}">{{child.name}}</a>
                        </div>
                        <div class="col-date"> {{child.date_add|rusDate}} </div>
                        <div class="col-date"> {{child.date_edit|rusDate}} </div>
                        <div class="col-options">
                            <a href="/admin/products/category/edit-{{child.categ_id}}"><i class="a-icon-pencil"></i></a>
                            <a title="{{child.name}}" class="delete-link" href="/admin/products/category/delete-{{child.categ_id}}"><i class="a-icon-remove"></i></a>
                        </div>
                    </li>
                {% endfor %}
            </ul>
        </li>
        {% endfor %}
    </ul>
        
    {% else %}
        <div class="a-mess-yellow">Нет контента для отображения</div>
    {% endif %}
{% endblock %}

{% block right %}
	<a href="/admin/products/category/add" class="a-btn-green">Добавить рубрику</a>
{% endblock %}