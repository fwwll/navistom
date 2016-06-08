{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if tpls %}
    
    <table class="a-table tablesorter sortable"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название шаблона</th> 
                <th>Раздел</th>
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="services/category"> 
            {% for t in tpls %}
            <tr id="categ-{{c.categ_id}}">
            	<td>{{t.mess_id}}</td> 
            	<td>
                	<a href="/admin/feedback/mess-tpls/edit-{{t.mess_id}}">{{t.title}}</a>
                </td>
                <td>{{t.section_name}}</td>
                <td class="ad-table-option">
                	<a href="/admin/feedback/mess-tpls/edit-{{t.mess_id}}"><i class="a-icon-pencil"></i></a>
                    <a class="delete-link" href="/admin/feedback/mess-tpls/delete-{{t.mess_id}}"><i class="a-icon-remove"></i></a>
                </td>
            </tr>
            {% endfor %}
        </tbody> 
    </table>
        
    {% else %}
        <div class="a-mess-yellow">Нет контента для отображения</div>
    {% endif %}
{% endblock %}

{% block right %}
	<a href="/admin/feedback/mess-tpls/add" class="a-btn-green">Добавить новый шаблон</a>
{% endblock %}