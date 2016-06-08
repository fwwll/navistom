{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>{{group.name}}</b>
        <span>{{group.description}}</span>
    </h1>
    <dl class="a-horizontal">
    {% for p in permissions %}
    	<dt>{{p.name_sys}}</dt>
        <dd>
        	<dl class="a-horizontal permission-list">
            	<dt>{% if p.flag_view == 1 %} <i class="a-icon-ok"></i> {% else %} <i class="a-icon-remove"></i> {% endif %}</dt>
                <dd>Разрешить просмотр раздела</dd>
                
                <dt>{% if p.flag_add == 1 %} <i class="a-icon-ok"></i> {% else %} <i class="a-icon-remove"></i> {% endif %}</dt>
                <dd>Разрешить пользователям добавлять материал</dd>
                
                <dt>{% if p.mod_type == 1 %} <i class="a-icon-ok"></i> {% else %} <i class="a-icon-remove"></i> {% endif %}</dt>
                <dd>Постмодерация</dd>
                
                {% if p.flag_limit == 1 %}
                <dt></dt>
                <dd>
                    <p></p>
                    <p>Количество разрешенных материалов: <b>{{p.count}}</b></p>
                    <p>Период добавления после регистрации: <b>{% if p.time_limit > 0 %} {{p.time_limit}} дней {% else %} Неограничено {% endif %}</b></p>
                    <p>Период отображения после размещения: <b>{{p.time_life}} дней</b></p>
                    {% if p.flag_date_limit == 1 %}
                        <p>Ограничить период размещения: <b>{{p.date_start|rusDate}} - {{p.date_end|rusDate}}</b></p>
                    {% endif %}
                </dd>
                {% endif %}
            </dl>
        </dd>
    {% endfor %}
    </dl>
{% endblock %}