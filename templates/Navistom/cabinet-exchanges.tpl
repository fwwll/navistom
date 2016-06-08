{% extends "cabinet-index.tpl" %}

{% block assets %}
    <script src="/assets/floatingbox/jquery.floatingbox.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.currency-input').inputFloat();
        });
    </script>
{% endblock %}

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
        <li class="active">
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

    {% if complete.message %}
        {% if complete.succes %}
            <div class="a-mess-green">
                {{ complete.message }}
            </div>
        {% else %}
            <div class="a-mess-yellow">
                {{ complete.message }}
            </div>
        {% endif %}
    {% endif %}
    <div class="a-mess">
        На NaviStom есть два варианта цены на товар: <br/>
        - в национальной валюте без привязки к курсу доллара и евро<br/>
        - в долларах или евро с привязкой к курсу и автоматическим пересчетом при изменении <br/><br/>

        По умолчанию установлена привязка к курсу НБУ <br/>

        При изменении Вашего курса валют цены пересчитываются во всех Ваших объявлениях.
    </div>

    <form class="n-edit-form validation" method="post" action="">

        {% for c in currensies %}
            <div class="a-row">
                <label for="passw">1 {{ c.name }}</label>
                <input value="{{ exchanges[c.currency_id] }}" class="currency-input" type="text"
                       name="rate[{{ c.currency_id }}]" id="rate[{{ c.currency_id }}]"/>
                <span class="currency-default-name">{{ currency_default.name_min }}</span>
            </div>
        {% endfor %}
        <div style="margin: 0px 120px" class="a-row">
            <div class="a-cols-2">
                <input {% if isUserExchanges == 0 %} checked="checked" {% endif %} type="radio" name="exchange_default"
                                                                                   id="exchange_default" value="1"/>
                <label for="exchange_default">Установить курс НБУ</label>
            </div>
            <div class="a-cols-2">
                <input {% if isUserExchanges != 0 %} checked="checked" {% endif %} type="radio" name="exchange_default"
                                                                                   id="exchange_default_1" value="0"/>
                <label for="exchange_default_1">Установить свой курс</label>
            </div>
        </div>

        <div class="a-row">
            <label>&nbsp;</label>
            <input class="a-btn-green" type="submit" value="Сохранить"/>
        </div>
    </form>
{% endblock %}