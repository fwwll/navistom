{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}
<ul class="cabinet-tabs-menu a-clear">
    <li class="active">
        <a href="/cabinet/messages">Все диалоги</a>
    </li>
</ul>
{% endblock %}

{% block cabinet_content %}
{% for d in dialogs %}
<a href="/cabinet/dialog-{{d.from_id}}-{{d.resource_id}}-{{d.section_id}}-{{d.status}}" class="ajax-link n-dialog a-clear {% if d.status == 0%}n-dialog-no-view{% endif%}">
    <img src="/uploads/users/avatars/tumb2/{{d.avatar}}" />
    <div class="n-dialog-user">
        <h5>{{d.name}}</h5>
        {{d.message}}
    </div>
    <div class="n-dialog-info">
        <span class="a-color-gray a-font-smal">{{d.section_name}}</span>
        <div>{{d.info.name}}</div>
        <span class="a-color-gray a-font-smal">{{d.info.description}}</span>
    </div>
</a>
{% else %}
<div class="a-mess">
	У Вас нет ни одного сообщения.
</div>
{% endfor %}
{% endblock %}