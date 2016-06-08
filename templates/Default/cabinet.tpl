{% extends "index.tpl" %}

{% block title %}
	{{meta.meta_title}}
{% endblock %}

{% block meta_description %}
	{{meta.meta_description}}
{% endblock %}

{% block meta_keys %}
	{{meta.meta_keys}}
{% endblock %}

{% block search %}
    <form class="navi-search" method="get" action="/articles/search">
        <input value="{% if route.values.search != ''%}{{route.values.search}}{% endif %}" id="search-input" name="q" placeholder="Поиск по статьям..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

<div class="navi-cabinet a-clear">
	<ul class="cabinet-tabs-menu a-clear">
    	<li class="active">
        	<a href="#">Мой аккаунт</a>
        </li>
    </ul>
    <div class="a-clear"></div>
	<div class="cabinet-left">
    	<ul>
        	<li class="active">
            	<a class="profile" href="#">
                	Профиль
                </a>
            </li>
            <li>
            	<span class="mess">6</span>
            	<a class="messages" href="#">
                	Сообщения
                </a>
            </li>
            <li>
            	<a class="materials" href="#">
                	Материалы
                </a>
            </li>
            <li>
            	<a class="faq" href="#">
                	Помощь
                </a>
            </li>
        </ul>
    </div>
    <div class="cabinet-right">
    	<div class="cabinet-user-info a-clear">
        	<div class="a-clear">
                <img src="/uploads/users/avatars/tumb2/{{user.avatar}}" />
                <h4>{{user.name}}<span class="status">{{user.group_name}}</span></h4>
                
                <p></p>
                
                <span class="a-color-gray">
                    <i class="a-icon-map-marker a-icon-gray"></i> г. {{user.city_name}}, {{user.country_name}}
                </span>
                <p></p>
                <a href="/cabinet/profile/edit" class="a-color-gray">
                    <i class="a-icon-edit a-icon-gray"></i> Редактировать профиль
                </a>
            </div>
            <p>&nbsp;</p>
            <h3> Доступ к разделам</h3>
            <p>&nbsp;</p>
            <div class="a-clear">
            	<table class="cabinet-table">
                	<thead>
                    	<tr>
                        	<th>Раздел</th>
                            <th>Разрешено</th>
                            <th>Добавлено</th>
                            <th>Осталось</th>
                            <th>Опции</th>
                        </tr>
                    </thead>
                    <tbody>
                    	{% for p in permissions %}
                        <tr>
                        	<td>{{p.name}}</td>
                            <td>
                            	{% if p.count > 0%}
                                {{p.count}}
                                {% elseif p.count == 0 and p.flag_add == 1 %}
                                неограничено
                                {% else %}
                                0
                                {% endif %}
                            </td>
                            <td>{{counts[p.section_id]}}</td>
                            <td>
                            	{% if p.count > 0%}
                                {{p.count - counts[p.section_id]}}
                                {% elseif p.count == 0 and p.flag_add == 1 %}
                                неограничено
                                {% else %}
                                0
                                {% endif %}
                            </td>
                            <td>
                            	<a class="a-btn-green" href="#">Расширить</a>
                            </td>
                        </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{% endblock %}