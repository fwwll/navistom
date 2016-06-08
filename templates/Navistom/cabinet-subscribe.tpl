{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}
    <ul class="cabinet-tabs-menu a-clear">
        <li>
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
            <li class="active">
                <a href="/cabinet/profile/subscribe">Управление подпиской</a>
            </li>
        {% endif %}
    </ul>
{% endblock %}

{% block cabinet_content %}


    <div class="s-mess-info">
        Выберите разделы, на которые Вы хотите подписаться.
    </div>

    {% if complete %}
        {% if complete.success %}
            <div style="margin-bottom:30px" class="s-mess-green">{{ complete.message }}</div>
        {% else %}
            <div class="a-mess-yellow">{{ complete.message }}</div>
        {% endif %}
    {% endif %}
    <form method="post" id="subscribe-form" class="n-edit-form a-clear" action="">
    <div class="subscribe-item a-clear s-option-row">
        Ваш текущий адрес электронной почты: &nbsp;<b
                style="border-bottom:#444 1px dashed">{{ user_info.info.email }}</b>
        <a href="/cabinet/profile/edit" class="s-btn a-float-right"><i class="a-icon-pencil a-icon-white"></i> &nbsp;&nbsp;Изменить
            e-mail</a>
    </div>

    <!--<div style="line-height:22px" class="subscribe-item a-clear s-option-row">
    	Управление новостной лентой сайта:
        <input type="hidden" name="news_status" value="{{ status.news }}">
        
        {% if status.news %}
        	<span class="s-status-ok">отображаются новости из Вашей подписки</span>
            
            <button type="submit" name="news_status" value="0" class="s-btn a-float-right">
                Отображать все новости портала
            </button>
        {% else %}
        	<span class="s-status-ok">отображаются все новости</span>
            
            <button type="submit" name="news_status" value="1" class="s-btn a-float-right">
                Отображать только результаты моей подписки
            </button>
        {% endif %}
    </div>-->

    <div style="line-height:22px" class="subscribe-item a-clear s-option-row">
        Управление статусом подписки:
        <input type="hidden" name="subscribe_status" value="{{ status.subscribe }}">

        {% if status.subscribe %}
            <span class="s-status-ok">подписка активна</span>

            <button type="submit" name="subscribe_status" value="0" class="s-btn-red a-float-right">
                <i class="a-icon-minus-sign a-icon-white"></i> &nbsp;Выключить подписку
            </button>

        {% else %}
            <span class="s-status-false">подписка приостановлена</span>

            <button type="submit" name="subscribe_status" value="1" class="s-btn-green a-float-right">
                <i class="a-icon-ok a-icon-white"></i> &nbsp;Включить подписку
            </button>
        {% endif %}
    </div>

    {% for s in subscribe_sections %}
        <div class="subscribe-item a-clear">
            <div {% if s.section_id == 11 %} style="min-height:10px" {% endif %} class="item-name">
                {{ s.icon|raw }}
                <div class="name">{% if s.name == 'Работа' %} Резюме {% else %}{{ s.name|raw }}{% endif %}</div>

                {% if s.section_id != 11 %}
                    <a title="Подписаться на весь раздел" href="#" class="s-btn-turquoise select-all">
                        <i class="a-icon-check a-icon-white"></i> &nbsp;&nbsp;Выбрать все
                    </a>

                    <a title="Сбросить настройки раздела" href="#" class="subscribe-section-reset">
                        Сбросить настройки
                    </a>
                {% endif %}
            </div>

            <div class="subscribe-item-form">
                {% if s.section_id == 16 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик:</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for key, value in articles_categs %}
                                <option {% if key in subscribe_sub_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 3 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик:</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for c in products_categs %}
                                <option {% if c.categ_id in subscribe_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ c.categ_id }}">{{ c.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Выберите один или несколько разделов:</label>
                        <select data-values="{{ subscribe_sub_categs[s.section_id]|join(',') }}" multiple="multiple"
                                placeholder="Сначала выберите рубрику..." class="select-2 sub-categs"
                                id="sub_categs_{{ s.section_id }}" name="sub_categs[{{ s.section_id }}][]">
                        </select>
                    </div>
                {% elseif s.section_id == 4 %}
                    <div class="row">
                        <label>Рубрика</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for c in products_categs %}
                                <option {% if c.categ_id in subscribe_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ c.categ_id }}">{{ c.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Раздел</label>
                        <select data-values="{{ subscribe_sub_categs[s.section_id]|join(',') }}" multiple="multiple"
                                placeholder="Сначала выберите рубрику..." class="select-2 sub-categs"
                                id="sub_categs_{{ s.section_id }}" name="sub_categs[{{ s.section_id }}][]">
                        </select>
                    </div>

                {% elseif s.section_id == 2 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for c in products_categs %}
                                <option {% if c.categ_id in subscribe_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ c.categ_id }}">{{ c.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Раздел</label>
                        <select data-values="{{ subscribe_sub_categs[s.section_id]|join(',') }}" multiple="multiple"
                                placeholder="Сначала выберите рубрику..." class="select-2 sub-categs"
                                id="sub_categs_{{ s.section_id }}" name="sub_categs[{{ s.section_id }}][]">
                        </select>
                    </div>
                {% elseif s.section_id == 9 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for key, value in services_categs %}
                                <option {% if key in subscribe_sub_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Населенный пункт</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 cities"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            <option {% if subscribe_cities[s.section_id][0] == -1 %}selected="selected"{% endif %}
                                    value="-1">Все населенные пункты
                            </option>
                            {% for key, value in cities %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 11 %}
                    <div class="row checkbox-row">
                        <span>Подписаться на раздел</span>
                        <input {% if subscribe_sub_categs[s.section_id][0] == 1 %}checked="checked"{% endif %}
                               type="radio" name="section_id[]" value="11"/> Да<br/>
                        <input {% if subscribe_sub_categs[s.section_id][0] == 0 %}checked="checked"{% endif %}
                               type="radio" name="section_id[]" value="0"/> Нет
                    </div>
                {% elseif s.section_id == 5 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for c in activity_categs %}
                                <option {% if c.categ_id in subscribe_sub_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ c.categ_id }}">{{ c.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Населенный пункт</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 cities"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            <option {% if subscribe_cities[s.section_id][0] == -1 %}selected="selected"{% endif %}
                                    value="-1">Все населенные пункты
                            </option>
                            {% for key, value in cities %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 6 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for key, value in work_categs %}
                                <option {% if key in subscribe_sub_categs[6] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Населенный пункт</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 cities"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            <option {% if subscribe_cities[6][0] == -1 %}selected="selected"{% endif %} value="-1">Все
                                населенные пункты
                            </option>
                            {% for key, value in cities %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 15 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for key, value in work_categs %}
                                <option {% if key in subscribe_sub_categs[15] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Населенный пункт</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 cities"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            <option {% if subscribe_cities[15][0] == -1 %}selected="selected"{% endif %} value="-1">Все
                                населенные пункты
                            </option>
                            {% for key, value in cities %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 7 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for c in labs_categs %}
                                <option {% if c.categ_id in subscribe_sub_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ c.categ_id }}">{{ c.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Регион</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            {% for key, value in regions %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 8 %}
                    <div class="row">
                        <label>Выберите одну или несколько рубрик</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2"
                                id="categs_{{ s.section_id }}" name="categs[{{ s.section_id }}][]">
                            {% for c in realty_categs %}
                                <option {% if c.categ_id in subscribe_sub_categs[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ c.categ_id }}">{{ c.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="row">
                        <label>Населенный пункт</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 cities"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            <option {% if subscribe_cities[s.section_id][0] == -1 %}selected="selected"{% endif %}
                                    value="-1">Все населенные пункты
                            </option>
                            {% for key, value in cities %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% elseif s.section_id == 10 %}
                    <div class="row">
                        <label>Населенный пункт</label>
                        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 one-cities"
                                id="cities_{{ s.section_id }}" name="cities[{{ s.section_id }}][]">
                            <option {% if subscribe_cities[s.section_id][0] == -1 %}selected="selected"{% endif %}
                                    value="-1">Все населенные пункты
                            </option>
                            {% for key, value in cities %}
                                <option {% if key in subscribe_cities[s.section_id] %}selected="selected"{% endif %}
                                        value="{{ key }}">{{ value }}</option>
                            {% endfor %}
                        </select>
                    </div>
                {% endif %}
            </div>
        </div>
    {% endfor %}
    <div style="line-height:22px" class="subscribe-item a-clear s-option-row">
        Управление статусом подписки:

        {% if status.subscribe %}
            <span class="s-status-ok">подписка активна</span>

            <button type="submit" name="subscribe_status" value="0" class="s-btn-red a-float-right">
                <i class="a-icon-minus-sign a-icon-white"></i> &nbsp;Выключить подписку
            </button>

        {% else %}
            <span class="s-status-false">подписка приостановлена</span>

            <button type="submit" name="subscribe_status" value="1" class="s-btn-green a-float-right">
                <i class="a-icon-ok a-icon-white"></i> &nbsp;Включить подписку
            </button>
        {% endif %}
    </div>
    <div class="subscribe-item a-clear submit-row">
        <input type="hidden" name="subscribe" value="1">

        <button type="submit" class="s-btn-green a-float-right">
            <i class="a-icon-save a-icon-white"></i> &nbsp;Сохранить
        </button>
    </div>
    </form>

{% endblock %}