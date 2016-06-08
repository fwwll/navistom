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
        <span>Редактировать статью</span>

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>
    {% if is_admin %}
        <form id="article-add-form" class="n-add-form a-clear" method="post"
              action="/index.ajax.php?route=/article/edit_ajax-{{ data.article_id }}">
            <div class="a-row">
                <label><font class="a-red">*</font> Заголовок статьи </label>
                <input value="{{ data.name }}" class="validate[required]" maxlength="80" type="text" name="name"
                       id="name"/>
            </div>
            <div class="a-row">
                <label>Автор статьи</label>
                <input value="{{ data.author }}" type="text" name="author" id="author"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Название источника</label>
                <input value="{{ data.source_name }}" type="text" name="source_name" id="source_name"/>
            </div>
            <div class="a-row">
                <label>Ссылка на источник</label>
                <input value="{{ data.source_link }}" type="text" name="source_link" id="source_link"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Рубрика</label>
                <select placeholder="Выберите из списка" multiple="multiple"
                        class="select-2 validate[required, minSize=1]" name="categs[]" id="categs">
                    {% for key, value in categs %}
                        <option {% if key in data.categs|split(',') %}selected="selected"{% endif %}
                                value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label> Метки</label>
                <select placeholder="Выберите из списка" multiple="multiple" class="select-2" name="tags[]" id="tags">
                    {% for t in tags %}
                        <option {% if t.tag_id in data.tags|split(',') %}selected="selected"{% endif %}
                                value="{{ t.tag_id }}">{{ t.name }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label>Дата публикации</label>
                <input value="{{ data.date_public }}" class="datetimepicker" type="text" name="date_public"
                       id="date_public"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Описание</label>
                <textarea name="content_min">{{ data.content_min|raw }}</textarea>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Текст статьи </label>
                <textarea class="admin-editor" name="content" id="content">{{ data.content }}</textarea>
            </div>
            <div class="a-row">
                <label>Фотографии</label>

                <ul class="uploader" id="uploader">
                    {% for i in images %}
                        <li class="image-added">
                            <input type="hidden" value="{{ i.image_id }}" name="images[]"/>
                            <img src="/uploads/images/articles/100x150/{{ i.url_full }}" alt="{{ i.image_id }}"/>
                        </li>
                    {% endfor %}

                    {% if images_count != -1 %}

                        {% for i in 0..images_count %}
                            <li></li>
                        {% endfor %}
                    {% endif %}
                </ul>

            </div>
            <div class="a-row">
                <label>Ссылка на видео с YouTube</label>
                <input value="{{ data.video_link }}" type="text" name="video_link" id="video_link"/>
            </div>
            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-html"></i> Meta - описание</span>
                </div>
            </div>
            <div class="a-row">
                <label>Meta title</label>
                <textarea name="meta_title">{{ data.meta_title }}</textarea>
            </div>
            <div class="a-row">
                <label>Meta description</label>
                <textarea name="meta_description">{{ data.meta_description }}</textarea>
            </div>
            <div class="a-row">
                <label>Meta keywords</label>
                <textarea name="meta_keys">{{ data.meta_keys }}</textarea>
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