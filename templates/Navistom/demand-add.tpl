{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block title %}
    {{ meta.meta_title }}
{% endblock %}

{% block meta_description %}
    {{ meta.meta_description }}
{% endblock %}

{% block meta_keys %}
    {{ meta.meta_keys }}
{% endblock %}

{% block content %}
    <div>
        <h1 class="n-form-title">
            <span>Добавить заявку в куплю / спрос</span>
            {% if user_info %}
                <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
            {% endif %}
        </h1>
        {% if is_add_access %}
            <form id="activity-add-form" class="n-add-form a-clear all_f" method="post" enctype="multipart/form-data"
                  action="/index.ajax.php?route=/demand/add_ajax">
                <div class="a-mess-orange" style="font-size:15px">КУПЛЮ / СПРОС - раздел для размещения ЗАЯВОК на поиск
                    товаров, услуг, анонсов мероприятий и т.д.
                </div>
                <div class="a-row n-title-description">
                    <label><font class="a-red">*</font> Заголовок</label>
                    <input maxlength="70" placeholder="Что Вы ищете?" class="validate[required]" type="text" name="name"
                           id="name"/>

                    <div class="n-ad-add-desc a-clear">
                        <h5>Пишите заголовок правильно:</h5>

                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">1</span>
                            </div>
                            <div class="col-2">
                                Начинайте с ключевого слова: КУПЛЮ, ИЩУ, ИНТЕРЕСУЮСЬ и т.д.
                            </div>
                        </div>
                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">2</span>
                            </div>
                            <div class="col-2">
                                Используйте ключевые слова: СТОМАТОЛОГИЧЕСКИЙ/ЗУБОТЕХНИЧЕСКИЙ, ДЛЯ
                                СТОМАТОЛОГИИ/ЗУБОТЕХНИКИ и т.д.
                            </div>
                        </div>

                        <p>
                            <b>Неправильно:</b> <br/>
                            Автоклав б/у на 20 л.
                        </p>

                        <b>Правильно:</b> <br/>
                        Куплю б/у автоклав для стоматологии объемом 20 л.
                    </div>
                </div>

                <div class="a-row">
                    <label>Описание Вашей заявки</label>
                    <textarea class="autosize" maxlength="3000" name="content"></textarea>
                </div>
                <div class="a-row">
                    <label>Фотографии</label>

                    <ul class="uploader" id="uploader">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li class="last"></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li class="last"></li>
                    </ul>
                </div>
                <div class="a-row">
                    <label>Ссылка на видео с YouTube</label>
                    <input type="text" name="video_link" id="video_link"/>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> Контактные телефоны</label>
                    <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                           class="phones-input"/>
                </div>
                <div class="a-row">

                    {#% include 'informer.tpl'%#}
                    {#% include 'price_inc.tpl'%#}
                    {% include 'price_inc_noy.tpl' %}




                    {#% include 'placement_rules.tpl'%#}

            </form>
        {% else %}
            {% if user_info %}
                {% include 'access-denied.tpl' with {'sectionId': 11} %}
            {% else %}
                {% include 'user-no-auth-mess.tpl' %}
            {% endif %}
        {% endif %}
    </div>
{% endblock %}