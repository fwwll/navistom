{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}
<div style="width:700px">

<h1 class="n-form-title">
    <span>Выбор материалов для VIP размещения</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="activity-add-form" class="n-add-form a-clear" method="post" action="/index.ajax.php?route=/cabinet/vip_ajax-{{route.values.section_id}}">
	{% for i in 0..data.0.count - 1 %}
    <div class="a-row">
    	<label>Выберите материал на позицию {{i + 1}}</label>
        <input type="hidden" name="material[]" data-link="/cabinet/get_user_materials-{{route.values.section_id}}" class="select-2-search" id="select-2-search-{{i}}" />
    </div>
	{% endfor %}
	<div class="a-row">
    	<label>&nbsp;</label>
        <input class="a-btn-green" type="submit" value="Сохранить"  />
    </div>
</form>

</div>

{% endblock %}