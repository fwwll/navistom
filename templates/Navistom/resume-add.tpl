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
        <span>Разместить резюме</span>

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>
    {% if is_add_access %}
        <form id="activity-add-form" class="n-add-form a-clear all_f" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/work/resume_add_ajax">
            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-user"></i> Личные данные</span>
                </div>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Фамилия</label>
                <input class="validate[required]" type="text" name="user_surname" id="user_surname"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Имя</label>
                <input class="validate[required]" type="text" name="user_name" id="user_name"/>
            </div>
            <div class="a-row">
                <label> Отчество</label>
                <input type="text" name="user_firstname" id="user_firstname"/>
            </div>
            <!--div class="a-row">
                <label><font class="a-red">*</font> Дата рождения</label>
                <select class="date_day validate[required]" name="birth_date_day" id="birth_date_day">
                                    <option value="">день</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>						</select>
                    <select class="date_month validate[required]" name="birth_date_month" id="birth_date_month">
                                    <option value="">месяц</option>
                    <option value="1">января</option><option value="2">февраля</option><option value="3">марта</option><option value="4">апреля</option><option value="5">мая</option><option value="6">июня</option><option value="7">июля</option><option value="8">августа</option><option value="9">сентября</option><option value="10">октября</option><option value="11">ноября</option><option value="12">декабря</option>						</select>
                    <select class="date_year validate[required]" name="birth_date_year" id="birth_date_year">
                                    <option value="">год</option>
                    <option value="1940">1940</option><option value="1941">1941</option><option value="1942">1942</option><option value="1943">1943</option><option value="1944">1944</option><option value="1945">1945</option><option value="1946">1946</option><option value="1947">1947</option><option value="1948">1948</option><option value="1949">1949</option><option value="1950">1950</option><option value="1951">1951</option><option value="1952">1952</option><option value="1953">1953</option><option value="1954">1954</option><option value="1955">1955</option><option value="1956">1956</option><option value="1957">1957</option><option value="1958">1958</option><option value="1959">1959</option><option value="1960">1960</option><option value="1961">1961</option><option value="1962">1962</option><option value="1963">1963</option><option value="1964">1964</option><option value="1965">1965</option><option value="1966">1966</option><option value="1967">1967</option><option value="1968">1968</option><option value="1969">1969</option><option value="1970">1970</option><option value="1971">1971</option><option value="1972">1972</option><option value="1973">1973</option><option value="1974">1974</option><option value="1975">1975</option><option value="1976">1976</option><option value="1977">1977</option><option value="1978">1978</option><option value="1979">1979</option><option value="1980">1980</option><option value="1981">1981</option><option value="1982">1982</option><option value="1983">1983</option><option value="1984">1984</option><option value="1985">1985</option><option value="1986">1986</option><option value="1987">1987</option><option value="1988">1988</option><option value="1989">1989</option><option value="1990">1990</option><option value="1991">1991</option><option value="1992">1992</option><option value="1993">1993</option><option value="1994">1994</option><option value="1995">1995</option><option value="1996">1996</option><option value="1997">1997</option><option value="1998">1998</option><option value="1999">1999</option><option value="2000">2000</option><option value="2001">2001</option><option value="2002">2002</option><option value="2003">2003</option>						</select>
            </div-->
            <div class="a-row">
                <label><font class="a-red">*</font> Контактные телефоны</label>
                <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                       class="phones-input validate[required]"/>
            </div>
            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-file"></i> Общая информация</span>
                </div>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Ваша специализация</label>
                <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]"
                        id="activity_categ_id" name="categ_id[]">
                    {% for key, value in categories %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Желаемый регион работы</label>
                <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id"
                        name="region_id">
                    <option value></option>
                    {% for key, value in regions %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Желаемый город работы</label>
                <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
                    <option value></option>
                </select>
            </div>
            <div class="a-row">
                <label>Готов к переезду</label>

                <input type="checkbox" name="leave" id="leave" value="1"/>
            </div>
            <div class="a-row">
                <label>Желаемая зарплата</label>
                <input class="n-price-input validate[min[1]]" type="text" name="price" id="price"/>

                <select class="n-currensy-input" name="currency_id" id="currency_id">
                    {% for c in currency %}
                        <option value="{{ c.currency_id }}">{{ c.name_min }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Вид занятости</label>
                <select class="validate[required]" id="employment_type" name="employment_type">
                    <option value="1">полная занятость</option>
                    <option value="2">неполная занятость</option>
                    <option value="3">удаленная работа</option>
                    <option value="4">посменно</option>
                </select>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-briefcase"></i> Опыт работы</span>
                </div>
            </div>
            <div id="resume-work">
                <div class="a-row a-form-mess">
                    <i class="a-icon-info-sign"></i>
                    Заполняя информацию об опыте работы, постарайтесь раскрыть для работодателя ваши положительные
                    стороны, которые в наибольшей мере соответствуют заголовку вашего резюме. Опишите ваши обязанности,
                    но не останавливайтесь только на них. Не забудьте упомянуть о ваших знаниях, умениях и о
                    положительном опыте применения их в работе.
                    <p>
                        <a href="#" id="resume-add-work" class="a-btn-green a-float-right"> Добавить место работы</a>
                    </p>
                </div>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-book"></i> Образование</span>
                </div>
            </div>

            <div id="resume-experience">
                <div class="a-row a-form-mess">
                    <i class="a-icon-info-sign"></i>
                    Во время редактирования этого блока, вы можете добавить в резюме образование, которое вы получили
                    или о которой хотите упомянуть в соответствии с целями данного резюме.
                    <p>
                        <a href="#" id="resume-add-experience" class="a-btn-green a-float-right"> Добавить место
                            учебы</a>
                    </p>
                </div>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-book"></i> Дополнительное образование</span>
                </div>
            </div>

            <div id="resume-traning">
                <div class="a-row a-form-mess">
                    <i class="a-icon-info-sign"></i>
                    Если вы окончили какие-либо курсы, принимали участие в тренингах, семинарах, т. е. повышали свой
                    профессиональный уровень, добавьте такую информацию в этом блоке.
                    <p>
                        <a href="#" id="resume-add-traning" class="a-btn-green a-float-right"> Добавить курс или
                            тренинг</a>
                    </p>
                </div>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-globe"></i> Владение языками</span>
                </div>
            </div>

            <div id="resume-langs">
                <div class="a-row a-form-mess">
                    <i class="a-icon-info-sign"></i>
                    Используйте этот блок резюме, чтобы указать, какие языки вы знаете, и оценить степень владения ими.
                    Даже если знание языков напрямую и не требуется в вакансиях, на которые вы претендуете, эта
                    информация будет полезна как дополнительная.
                    <p>
                        <a href="#" id="resume-add-lang" class="a-btn-green a-float-right"> Добавить язык</a>
                    </p>
                </div>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-edit"></i> Дополнительно</span>
                </div>
            </div>

            <div class="a-row">
                <label>Описание Ваших навыков</label>
                <textarea class="autosize" maxlength="3000" name="content"></textarea>
            </div>
            <div class="a-row">
                <label>Ваше фото или фотографии работ</label>

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

                {#% include 'informer.tpl'%#}
                {% include 'price_inc_noy.tpl' %}



                {#% include 'placement_rules.tpl'%#}


        </form>
    {% else %}
        {% if user_info %}
            {% include 'access-denied.tpl' with {'sectionId': 6} %}
        {% else %}
            {% include 'user-no-auth-mess.tpl' %}
        {% endif %}
    {% endif %}
    </div>
{% endblock %}