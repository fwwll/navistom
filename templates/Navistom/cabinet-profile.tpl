{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}
    <ul class="cabinet-tabs-menu a-clear">
        <li class="active">
            <a href="/cabinet">Мой аккаунт</a>
        </li>
        <li>
            <a href="/cabinet/profile/edit">Редактировать профиль</a>
        </li>
        <li>
            <a href="/cabinet/profile/passw">Сменить пароль</a>
        </li>
        <li>
            <a href="/cabinet/profile/exchanges">Мой курс валют</a>
        </li>
        {% if turnSubscribe %}
            <li>
                <a href="/cabinet/profile/subscribe">Управление подпиской</a>
            </li>
        {% endif %}
    </ul>
{% endblock %}

{% block cabinet_content %}
    <div class="cabinet-user-info a-clear">
        <div class="a-clear">
            <img src="/uploads/users/avatars/tumb2/{{ user.avatar }}"/>
            <h4>{{ user.name }}<span class="status">{{ user.group_name }}</span></h4>

            <p></p>
        
        <span class="a-color-gray">
            <i class="a-icon-map-marker a-icon-gray"></i> г. {{ user.city_name }}, {{ user.country_name }}
        </span>

            <p></p>
            <a href="/cabinet/profile/edit" class="a-color-gray">
                <i class="a-icon-edit a-icon-gray"></i> Редактировать профиль
            </a>
        </div>

        <p>&nbsp;</p>

        {% if warnings %}
            <div class="a-mess-orange">
                <p><b>Внимание!</b></p>

                <ul class="n-cabinet-warning-list">
                    {% for item in warnings %}
                        <li>
                            {% if item.type == 1 %}
                                Период размещения в разделе <b>{{ item.name }}</b> закончился.<br/>
                                Все Ваши объявления в этом разделе скрыты.
                            {% elseif item.type == 2 %}
                                Период размещения в разделе <b>{{ item.name }}</b> закончится через
                                <b>{{ item.diff|day }}</b>.<br/>
                                По окончанию периода все Ваши объявления в этом разделе будут скрыты.
                            {% else %}
                                Лимит объявлений в разделе <b>{{ item.name }}</b> исчерпан.
                            {% endif %}
                        </li>
                    {% endfor %}
                </ul>

                <p>
                    Для продления доступа обратитесь к администратору сайта по тел. <b>+38- 044-573-97-73</b> <br/> или
                    отправьте заявку
                </p>
                <a class="send-permission-request" href="javascript:Main.sendPermissionRequest(1)">Отправить заявку</a>
            </div>
        {% endif %}

        <h3>Мои объявления</h3>

        <table class="cabinet-materials">
            {% for p in permissions %}

                {% if p.flag_add %}
                    {% if p.flag_limit > 0 %}
                        {% set count = p.count %}
                    {% else %}
                        {% set count = 1000 %}
                    {% endif %}
                {% else %}
                    {% set count = 0 %}
                {% endif %}

                <tr>
                    <td class="graph">
                        <input class="chart {% if count == 0 or (p.section_id|getAccessLimit('date') and p.section_id|getAccessLimit('dateDiff') <= 0) %}chart-gray{% endif %}"
                               data-min="0" data-max="{{ count }}" value="{{ countsDetail[p.section_id]['count'] }}"
                               data-width="100" data-height="100" data-thickness=".2"/>
                    </td>
                    <td class="descr">
                        <b>{{ p.name }}</b> <br/>
                <span class="a-color-gray a-font-smal">Добавлено {{ countsDetail[p.section_id]['count'] }}
                    из {{ count }}
                </span><br/>
                        {% if p.flag_date_limit %}
                            <!--<span class="a-color-gray a-font-smal">Действительны до: <br> {{ p.date_end|rusDate }}</span>-->
                        {% endif %}

                        <dl class="a-list a-horizontal" style="margin-top: 10px; color: #666">
                            <dt style="width: 60%; font-weight: 400;">
                                Скрытых:
                            </dt>
                            <dd>
                                <strong>{{ countsDetail[p.section_id]['hide']|default(0) }}</strong>
                            </dd>
                            <dt style="width: 60%; font-weight: 400;">
                                На модерации:
                            </dt>
                            <dd>
                                <strong>{{ countsDetail[p.section_id]['moderation']|default(0) }}</strong>
                            </dd>
                        </dl>
                    </td>
                    <td class="option">
                        {% if p.section_id == 16 %}

                        {% elseif p.section_id == 2 %}
                            <a href="/products/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}">Перейти
                                к моим предложениям</a> <br/>

                            {% if countsDetail[p.section_id]['hide'] %}
                                <a href="{{ p.link }}/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}::0">Скрытые
                                    предложения</a> <br/><br/>
                            {% endif %}
                        {% else %}
                            <a href="{{ p.link }}/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}">Перейти
                                к моим предложениям</a> <br/>

                            {% if countsDetail[p.section_id]['hide'] and p.section_id != 16 %}
                                <a href="{{ p.link }}/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}::0">Скрытые
                                    предложения</a> <br/><br/>
                            {% endif %}

                        {% endif %}

                        {% if p.section_id == 2 %}

                        {% elseif p.section_id == 16 %}
                            <a class="ajax-link" href="/article/add">Добавить статью</a>
                        {% elseif p.section_id == 3 %}
                            <a class="ajax-link" href="/product/add">Добавить предложение</a>
                        {% elseif p.section_id == 7 %}
                            <a class="ajax-link" href="/lab/add">Добавить предложение</a>
                        {% elseif p.section_id == 9 %}
                            <a class="ajax-link" href="/service/add">Добавить предложение</a>
                        {% else %}
                            <a class="ajax-link" href="{{ p.link }}/add">Добавить предложение</a>
                        {% endif %}
                    </td>
                    <td class="vip">
                        {% if p.section_id != 16 and p.section_id != 2 and updates_counts[p.section_id] > 0 %}
                            <a class="update-link"
                               href="{{ p.link }}/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}/updates-1">
                                <i class="a-icon-arrow-up a-icon-white"></i> Поднять вверх
                            </a>
                        {% endif %}
                    </td>
                </tr>
            {% endfor %}
        </table>
    </div>

    <div>

        <a id='aj' href='/cabinet/zayavka-{{ user_info.info.user_id }}'>  {% if zayavka %}<i
                    class='a-icon-remove a-icon-gray'></i>Отменить удаление аккаунта {% else %}<i
                    class='a-icon-basket a-icon-gray'></i>Заявка на удаление аккаунта {% endif %}</a>
    </div>


{% endblock %}