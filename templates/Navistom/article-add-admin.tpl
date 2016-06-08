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

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>
    {% if is_admin %}
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
                    <span class="a-form-descr">Название организации или производителя</span>
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
                <label> Метки</label>
                <select placeholder="Выберите из списка" multiple="multiple" class="select-2" name="tags[]" id="tags">
                    {% for t in tags %}
                        <option value="{{ t.tag_id }}">{{ t.name }}</option>
                    {% endfor %}
                </select>

                <div class="a-float-right">
                    <a id="new-tag-add" class="a-color-gray a-font-small" href="#">Добавить новые метки</a>
                </div>
            </div>
            <div class="a-row">
                <label>Дата публикации</label>
                <input class="datetimepicker" type="text" name="date_public" id="date_public"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Описание</label>
                <textarea name="content_min"></textarea>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Текст статьи </label>
                <textarea class="admin-editor" name="content" id="content"></textarea>
            </div>
            <div class="a-row">
                <label>Фотографии <font class="a-red">*</font></label>

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
                <div class="form-separator">
                    <span><i class="a-icon-html"></i> Meta - описание</span>
                </div>
            </div>
            <div class="a-row">
                <label>Meta title</label>
                <textarea name="meta_title"></textarea>
            </div>
            <div class="a-row">
                <label>Meta description</label>
                <textarea name="meta_description"></textarea>
            </div>
            <div class="a-row">
                <label>Meta keywords</label>
                <textarea name="meta_keys"></textarea>
            </div>
            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-edit"></i> опрос к статье</span>
                </div>
            </div>
            <div class="a-row">
                <label>Название опроса</label>
                <input type="text" name="interview_name" id="interview_name"/>
            </div>
            <div class="a-row">
                <label>Варианты ответов</label>
                <input type="text" class="tags-input" name="interview_versions" id="interview_versions"/>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Сохранить"/>
            </div>
        </form>
    {% else %}
        <div style="width:700px" class="a-mess-yellow">
            У Вас нет доступа к редактирования этой информации. <br/>
            Для получения дополнительной информации обратитесь к администратору портала.
        </div>
    {% endif %}
    </div>

{% endblock %}