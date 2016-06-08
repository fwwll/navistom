{% extends "index.tpl" %}

{% block title %}{{meta.name}}{% endblock %}

{% block content %}
     
	<h1 class='ad-title'>{{meta.name}}</h1>
<div class=' a-clear'>
	<form class='ad-form a-clear validation' method='post'>
	<div class='a-row'><label><b>Description:</b></label> <input type='text' value='{{meta.description}}' name='description' /> <br/></div>
	<div class='a-row'><label><b>Keywords:</b></label> <input type='text' value='{{meta.keywords}}' name='keywords' /> <br/></div>
		<div class='a-row'><label><b>Title:</b></label><input type='text' value='{{meta.title}}' name='title' /><br/></div>
		<div class='a-row'><label><b>H1:</b></label><input type='text' value='{{meta.h1}}' name='h1' /><br/></div>
		<input type='hidden' value='{{meta.pages}}' name='pages' />
		
	</form>	
</div>
    
	
{% endblock %}

{% block right %}
    
	<div class="a-group-btn a-margin-right">
        <input name="form-save" id="form-save" class="a-btn-green form-submit" type="submit" value="Сохранить" />
        <input name="form-apply" id="form-apply" class="a-btn-green form-submit" type="submit" value="Применить" />
    </div>
    
    <input name="form-cancel" id="form-cancel" type="submit" class="a-btn" value="Отмена" />
{% endblock %}