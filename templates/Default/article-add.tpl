{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}
	{{meta.meta_title}}
{% endblock %}

{% block meta_description %}
	{{meta.meta_description}}
{% endblock %}

{% block meta_keys %}
	{{meta.meta_keys}}
{% endblock %}

{% block content %}

<div style="width:600px">

<h1 class="n-form-title">
    <span>Добавить статью</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
{% if is_add_access %}
<form id="article-add-form" class="n-add-form a-clear" method="post" action="/index.ajax.php?route=/article/add">
    <div class="a-row">
        <label><font class="a-red">*</font> Заголовок статьи </label>
        <input class="validate[required]" maxlength="80" type="text" name="name" id="name" />
    </div>
    <div class="a-row">
        <label>Автор статьи</label>
        <input type="text" name="author" id="author" />
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Название источника</label>
        <input type="text" name="source" id="source" />
    </div>
    <div class="a-row">
        <label>Ссылка на источник</label>
        <input type="text" name="source_link" id="source_link" />
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select placeholder="Выберите из списка" multiple="multiple" class="select-2 validate[required, minSize=1]" name="categs[]" id="categs">
            {% for key, value in categs %}
            <option value="{{key}}">{{value}}</option>
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
        <input type="text" name="video_link" id="video_link" />
    </div>
    <div class="a-row">
        <label>Ваши пожелания по дате размещения и оформлению статьи</label>
        <textarea name="user_comment" id="user_comment"></textarea>
    </div>
    <div class="a-row">
        <div class="n-form-add-btns a-clear">
            <div class="col-1">
                <div class="n-title-orange">VIP размещение</div>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut odio neque.
                <div>
                    <input name="vip" class="n-add-btn-orange input-submit" type="submit" value="VIP Размещение" />
                </div>
            </div>
            <div class="col-2">
                <div class="n-title-gray">Обычное размещение</div>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut odio neque.
                <div>
                    <input name="default" class="n-add-btn-gray input-submit" type="submit" value="Добавить" />
                </div>
            </div>
        </div>
    </div>
</form>
{% else %}
	<div style="width:700px" class="a-mess-yellow">
    	У Вас нет доступа к размещению статей. <br />
        Для получения дополнительной информации обратитесь к администратору портала.
    </div>
{% endif %}

</div>

{% endblock %}