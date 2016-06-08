{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}


<form enctype="multipart/form-data"  method='post' action='/admin/reclama/'>
<label> Название рекламы <br/>
  <input type='text' name='title' value='{{title}}'> 
</label>
 
<textarea  class='editor' name='content' >{{content}}</textarea> 
 <br/>
 {% if file %}
    
 {%else%}
   
 {%endif%}
 
<label> Подпись файла <br/>
   <input type='text'  name='file_title' value='{{file_title}}' />
</label>
<br/>
<p>{{file}} </p>
 
 <input type='file'  name='file' />
 <input type='submit' value='Сохранить' />
 </form>
 
 
{% endblock %}

{% block right %}

{% endblock %}