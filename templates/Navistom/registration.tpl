{% extends ajax ? "index-ajax.tpl" : "index_new_noban.tpl" %}

{% block content %}
{% if ajax %}
<div style="width:700px">
    {% else %}
    <div class="item">
        {% endif %}

        <h1 class="n-form-title">
            <span>Регистрация</span>

            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        </h1>

        <form id="registration-form" class="n-add-form" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/registration_ajax">
            <div class="a-row">
                <label for="user_name"><font class="a-red">*</font> Ваше имя или ник</label>
                <input class="validate[required, minSize[4]]" type="text" name="user_name" id="user_name"/>
            </div>
            <!--div class="a-row">
                <label for="user_avatar">Аватар, фото или логотип</label>
                <input type="file" name="user_avatar" id="user_avatar" />
            </div-->
            <div class="a-row">
                <label for="user_email"><font class="a-red">*</font> Действующий E-mail адрес</label>
                <input class="validate[required, custom[email], ajax[ajaxUserCall]]" type="text" name="user_email"
                       id="user_email"/>
            </div>
            <div class="a-row">
                <label for="user_passw"><font class="a-red">*</font> Пароль</label>
                <input class="validate[required, minSize[6]]" type="password" name="user_passw" id="user_passw"/>
            </div>
            <div class="a-row">
                <label for="user_passw_2"><font class="a-red">*</font> Повторите пароль</label>
                <input class="validate[required, equals[user_passw]]" type="password" name="user_passw_2"
                       id="user_passw_2"/>
            </div>
            <div class="a-row">
                <label for="user_region"><font class="a-red">*</font> Область</label>
                <select class="select-2 validate[required]" name="user_region" id="user_region">
                    <option value="0">Выберите из списка</option>
                    {% for key, value in regions %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label for="user_city"><font class="a-red">*</font> Населенный пункт</label>
                <select class="select-2 validate[required]" name="user_city" id="user_city">
                    <option value="0">Выберите область...</option>
                </select>
            </div>

            <!--div class="a-row">
                <label>&nbsp;</label>
                <span>Контактная информация будет отображатся возле ваших объявлений.</span>
            </div-->
            <div class="a-row">
                <label for="user_contact_phone_1"><font class="a-red">*</font> Контактные телефоны</label>
                <input type="text" name="user_contact_phone" class="phones-input" id="user_contact_phone"/>
            </div>
            <!--div class="a-row">
                <label for="user_site">Ссылка на Ваш сайт</label>
                <input type="text" name="user_site" id="user_site" />
            </div-->
            <!--div class="a-row">
                <label for="user_icq">ICQ</label>
                <input type="text" name="user_icq" id="user_icq" />
            </div-->
            <!--div class="a-row">
                <label for="user_skype">Skype</label>
                <input type="text" name="user_skype" id="user_skype" />
            </div-->
            <div class="a-row">
                <label>&nbsp;</label>

                <div class="qaptcha"></div>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                Регистрируясь Вы принимаете <a class="link" target="_blank" href="/user-agreement">пользовательское
                    соглашение</a>
            </div>
            <div class="a-row a-row-bottom" style="line-height: 30px">
                <label>&nbsp;
                    <div class="form-loader display-none">
                        <i class="load"></i>
                        Загрузка...
                    </div>
                </label>
                <span>NaviStom +38-044-573-97-73 пн-пт с 10-00 до 17-00</span>
                <input value="Регистрация" type="submit" class="a-btn-green a-float-right"/>
            </div>
        </form>
    </div>
    {% endblock %}
