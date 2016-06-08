{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

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

    {% if ajax %}
        <div class="vip-request" style="width:800px; margin:-50px">
    {% else %}
        <div class="vip-request">
    {% endif %}
    <div class="vip-request-header">
        <b>Если Вы желаете выделить свои объявления, <br> воспользуйтесь платной услугой «VIP-размещение».</b> <br><br>
        В этом случае ваши объявления получат максимальную видимость<br> и будут оставаться вверху списка на срок 30
        дней.
    </div>
    <div class="vip-request-ad">
        Заявка на VIP - размещение объявления <br>
        <a class="vip-ad-link" target="_blank" href="{{ params.link }}"><b>{{ params.name }}</b></a>
    </div>

    <div class="vip-request-descr">
        VIP-ОБЪЯВЛЕНИЕ МОЖЕТ ОТОБРАЖАТЬСЯ ВВЕРХУ СПИСКА
    </div>

    <div class="vip-request-item a-clear">
        <div class="vip-item-icon">
            <img src="/{{ tpl_dir }}/images/vip-1.png"/>
        </div>
        <div class="vip-item-descr">
            - на главной<br>
            - в разделе<br>
            - в рубрике<br>
            - в подрубрике<br>
            - в объявлениях конкуретов
        </div>
        <div class="vip-item-price">
            <b>500 грн.</b>

            <a class="vip-request-send" href="/vip-request-confirm-{{ section_id }}-{{ resource_id }}-1">Заказать</a>
        </div>
    </div>

    <div class="vip-request-item a-clear">
        <div class="vip-item-icon">
            <img src="/{{ tpl_dir }}/images/vip-2.png"/>
        </div>
        <div class="vip-item-descr">
            - в разделе<br>
            - в рубрике<br>
            - в подрубрике<br>
        </div>
        <div class="vip-item-price">
            <b>250 грн.</b>
            <a class="vip-request-send" href="/vip-request-confirm-{{ section_id }}-{{ resource_id }}-2">Заказать</a>
        </div>
    </div>

    <div class="vip-request-item a-clear">
        <div class="vip-item-icon">
            <img src="/{{ tpl_dir }}/images/vip-3.png"/>
        </div>
        <div class="vip-item-descr">
            - в рубрике<br>
            - в подрубрике<br>
        </div>
        <div class="vip-item-price">
            <b>100 грн.</b>
            <a class="vip-request-send" href="/vip-request-confirm-{{ section_id }}-{{ resource_id }}-3">Заказать</a>
        </div>
    </div>

    <div class="vip-request-descr">
        образец структуры сайта
    </div>

    <div class="a-row a-offset-1">
        <div class="a-cols-4">
            <div class="vip-color">Главная</div>
            Главная NaviStom ->
        </div>
        <div class="a-cols-4">
            <div class="vip-color">Раздел</div>
            Продам Б/У ->
        </div>
        <div class="a-cols-4">
            <div class="vip-color">Рубрика</div>
            Оптика ->
        </div>
        <div class="a-cols-4">
            <div class="vip-color">Подрубрика</div>
            Бинокулярные лупы
        </div>
    </div>

    <!--<div class="vip-ad a-clear">
    	<div style="float:left">
        	Заявка на VIP - размещение объявления <br>
        	<a class="vip-ad-link" target="_blank" href="{{ params.link }}"><b>{{ params.name }}</b></a>
        </div>
        
        <a class="vip-request-send" href="/vip-request-confirm-{{ section_id }}-{{ resource_id }}">Отправить заявку</a>
    </div>-->

    </div>
{% endblock %}