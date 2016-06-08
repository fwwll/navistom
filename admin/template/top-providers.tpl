{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>

    {% if providers %}
        <table class="a-table tablesorter sortable">
            <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Опубл.</th>
                <th>Переходов</th>
                <th>До окончания</th>
                <th>Опции</th>
            </tr>
            </thead>
            <tbody id="top-provider">
            {% for item in providers %}
                <tr {% if item.days < 8 %} style="background:#FFE8E8" {% endif %} id="provider_id-{{ item.provider_id }}">
                    <td>{{ item.provider_id }}</td>
                    <td>
                        <a href="/admin/top-provider/edit-{{ item.provider_id }}">{{ item.name }}</a>
                    </td>
                    <td>{{ item.flag|flag }}</td>
                    <td>{{ item.transitions }}</td>
                    <td>{{ item.days }} дней</td>
                    <td class="ad-table-option">
                        <a href="/admin/top-provider/edit-{{ item.provider_id }}"><i class="a-icon-pencil"></i></a>
                        <a title="Топ-поставщик {{ item.name }}" class="delete-link" data-href="/admin/top-provider/delete-{{ item.provider_id }}"><i class="a-icon-trash"></i></a>
                    </td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    {% else %}
        <div class="a-mess">Нет контента для отображения</div>
    {% endif %}
{% endblock %}

{% block right %}
    <a href="/admin/top-provider/add" class="a-btn-green">Добавить топ-поставщика</a>
{% endblock %}