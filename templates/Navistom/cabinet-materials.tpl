{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}

{% endblock %}

{% block cabinet_content %}
    <table class="cabinet-materials">
        {% for p in permissions %}
            <tr>
                <td class="graph">
                    <input class="chart" data-min="0" data-max="{{ p.count }}" value="{{ counts[p.section_id] }}"
                           data-width="100" data-height="100" data-thickness=".2"/>
                </td>
                <td class="descr">
                    <b>{{ p.name }}</b> <br/>
                    <span class="a-color-gray a-font-smal">Добавлено {{ counts[p.section_id] }}
                        из {{ p.count }}</span><br/>

                </td>
                <td class="option">
                    {% if p.section_id == 1 %}

                    {% elseif p.section_id == 2 %}
                        <a href="/products/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}">Перейти
                            к моим предложениям</a> <br/>
                    {% else %}
                        <a href="{{ p.link }}/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}">Перейти
                            к моим предложениям</a> <br/>

                    {% endif %}

                    {% if p.section_id == 2 %}

                    {% elseif p.section_id == 1 %}
                        <a href="#/article/add">Добавить статью</a>
                    {% elseif p.section_id == 3 %}
                        <a href="#/product/add">Добавить предложение</a>
                    {% elseif p.section_id == 7 %}
                        <a href="#/lab/add">Добавить предложение</a>
                    {% elseif p.section_id == 9 %}
                        <a href="#/service/add">Добавить предложение</a>
                    {% else %}
                        <a href="#{{ p.link }}/add">Добавить предложение</a>
                    {% endif %}
                </td>
                <td class="vip">

                </td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}