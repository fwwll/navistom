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

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}

    <h1 class="n-form-title">
        <span>Добавить статью</span>
        {% if user_info %}
            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        {% endif %}
    </h1>
    {% if is_add_access %}
        <form id="article-add-form" class="n-add-form a-clear" method="post"
              action="/index.ajax.php?route=/article/add">
            <div class="a-row">
                <label><font class="a-red">*</font> Заголовок статьи </label>
                <input class="validate[required]" maxlength="80" type="text" name="name" id="name"/>
            </div>
            <div class="a-row">
                <label>Автор статьи</label>
                <input type="text" name="author" id="author"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Название источника
                    <span class="a-form-descr">название организации или производителя</span>
                </label>
                <input type="text" name="source_name" id="source_name"/>
            </div>
            <div class="a-row">
                <label>
                    Ссылка на источник
                    <span class="a-form-descr">Например Ваш сайт</span>
                </label>
                <input type="text" name="source_link" id="source_link"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Рубрика</label>
                <select placeholder="Выберите из списка" multiple="multiple"
                        class="select-2 validate[required, minSize=1]" name="categs[]" id="categs">
                    {% for key, value in categs %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Текст статьи </label>
                <textarea class="editor" name="content" id="content"></textarea>
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
                <label>Ваши пожелания по дате размещения и оформлению статьи</label>
                <textarea name="user_comment" id="user_comment"></textarea>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Сохранить"/>
            </div>
        </form>
    {% else %}
        {% if user_info %}
            {% include 'access-denied.tpl' with {'sectionId': 16} %}
        {% else %}
            {% include 'user-no-auth-mess.tpl' %}
        {% endif %}
    {% endif %}

    </div>

{% endblock %}