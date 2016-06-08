{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    <div class="section">
        <ul class="tabs a-clear">
            <li class="current">Украина</li>
            <li class="">Другие страны</li>
        </ul>
        <div class="box visible">
             {% if banners %}
           	<h3 class="stat-title">Рекламные баннера</h3>
            
            <table class="a-table"> 
                <thead> 
                    <tr> 
                        <th>#</th>
                        <th>Название</th> 
                        <th>Процент показов</th>
                        <th>Опубл.</th> 
                        <th>Показов</th>
                        <th>Переходов</th>
                        <th>CTR</th>
                        <th>До окончания</th>
                        <th>Опции</th> 
                    </tr> 
                </thead> 
                <tbody id="products_new"> 
                    {% for b in banners[1] %}
                    <tr {% if b.days < 8 %} style="background:#FFE8E8" {% endif %}> 
                        <td>{{b.banner_id}}</td>
                        <td>
                            <a href="/admin/banner/edit-{{b.banner_id}}">{{b.company}} - {{b.name}}</a>
                        </td>
                        <td>{{b.percent}} %</td>
                        <td>{% if b.flag > 0%} Да {% else %} Нет {% endif %}</td>
                        <td>{{b.views|number_format(0, ',', ' ')}}</td>
                        <td>{{b.clicks}}</td>
                        <td>
                        	{% if b.clicks > 0 and b.views > 0%}
                            	{{((b.clicks / b.views) * 100)|number_format(3, ',', ' ')}}%
                            {%else%}
                            	0%
                            {% endif %}
                        </td>
                        <td>{{b.days}} дней</td>
                        <td class="ad-table-option">
                            <a href="/admin/banner/edit-{{b.banner_id}}"><i class="a-icon-pencil"></i></a>
                            <a class="delete-link" href="/admin/banner/delete-{{b.banner_id}}"><i class="a-icon-trash"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody> 
            </table>
             
            <h3 class="stat-title">Баннера по умолчанию</h3>
            
            <table class="a-table"> 
                <thead> 
                    <tr> 
                        <th>#</th>
                        <th>Название</th> 
                        <th>Процент показов</th>
                        <th>Опубл.</th> 
                        <th>Показов</th>
                        <th>Переходов</th>
                        <th>CTR</th>
                        <th>Опции</th> 
                    </tr> 
                </thead> 
                <tbody id="products_new"> 
                    {% for b in banners_default[1] %}
                    <tr> 
                        <td>{{b.banner_id}}</td>
                        <td>
                            <a href="/admin/banner/edit-{{b.banner_id}}">{{b.company}} - {{b.name}}</a>
                        </td>
                        <td>{{b.percent}} %</td>
                        <td>{% if b.flag > 0%} Да {% else %} Нет {% endif %}</td>
                        <td>{{b.views|number_format(0, ',', ' ')}}</td>
                        <td>{{b.clicks}}</td>
                        <td>
                        	{% if b.clicks > 0 and b.views > 0%}
                            	{{((b.clicks / b.views) * 100)|number_format(3, ',', ' ')}}%
                            {%else%}
                            	0%
                            {% endif %}
                        </td>
                        <td class="ad-table-option">
                            <a href="/admin/banner/edit-{{b.banner_id}}"><i class="a-icon-pencil"></i></a>
                            <a class="delete-link" href="/admin/banner/delete-{{b.banner_id}}"><i class="a-icon-trash"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody> 
            </table>
            
            <h3 class="stat-title">Не активные</h3>
             
             {% if no_active[1] %}
             <table class="a-table"> 
                <thead> 
                    <tr> 
                        <th>#</th>
                        <th>Название</th> 
                        <th>Процент показов</th>
                        <th>Опубл.</th> 
                        <th>Показов</th>
                        <th>Переходов</th>
                        <th>CTR</th>
                        <th>Опции</th> 
                    </tr> 
                </thead> 
                <tbody id="products_new"> 
                    {% for b in no_active[1] %}
                    <tr> 
                        <td>{{b.banner_id}}</td>
                        <td>
                            <a href="/admin/banner/edit-{{b.banner_id}}">{{b.company}} - {{b.name}}</a>
                        </td>
                        <td>{{b.percent}} %</td>
                        <td>{% if b.flag > 0%} Да {% else %} Нет {% endif %}</td>
                        <td>{{b.views|number_format(0, ',', ' ')}}</td>
                        <td>{{b.clicks}}</td>
                        <td>
                        	{% if b.clicks > 0 and b.views > 0%}
                            	{{((b.clicks / b.views) * 100)|number_format(3, ',', ' ')}}%
                            {%else%}
                            	0%
                            {% endif %}
                        </td>
                        <td class="ad-table-option">
                            <a href="/admin/banner/edit-{{b.banner_id}}"><i class="a-icon-pencil"></i></a>
                            <a class="delete-link" href="/admin/banner/delete-{{b.banner_id}}"><i class="a-icon-trash"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody> 
            </table>
             {% else %}
             	<div class="a-mess-yellow">Нет контента для отображения</div>
             {% endif %}
             
            {% else %}
                <div class="a-mess-yellow">Нет контента для отображения</div>
            {% endif %}
        </div>
        <div class="box">
           	<h3 class="stat-title">Рекламные баннера</h3>
            
            {% if banners[0]%}
            <table class="a-table"> 
                <thead> 
                    <tr> 
                        <th>#</th>
                        <th>Название</th> 
                        <th>Процент показов</th>
                        <th>Опубл.</th> 
                        <th>Показов</th>
                        <th>Переходов</th>
                        <th>CTR</th>
                        <th>До окончания</th>
                        <th>Опции</th> 
                    </tr> 
                </thead> 
                <tbody id="products_new"> 
                    {% for b in banners[0] %}
                    <tr> 
                        <td>{{b.banner_id}}</td>
                        <td>
                            <a href="/admin/banner/edit-{{b.banner_id}}">{{b.company}} - {{b.name}}</a>
                        </td>
                        <td>{{b.percent}} %</td>
                        <td>{% if b.flag > 0%} Да {% else %} Нет {% endif %}</td>
                        <td>{{b.views|number_format(0, ',', ' ')}}</td>
                        <td>{{b.clicks}}</td>
                        <td>
                        	{% if b.clicks > 0 and b.views > 0%}
                            	{{((b.clicks / b.views) * 100)|number_format(3, ',', ' ')}}%
                            {%else%}
                            	0%
                            {% endif %}
                        </td>
                        <td>{{b.days}} дней</td>
                        <td class="ad-table-option">
                            <a href="/admin/banner/edit-{{b.banner_id}}"><i class="a-icon-pencil"></i></a>
                            <a class="delete-link" href="/admin/banner/delete-{{b.banner_id}}"><i class="a-icon-trash"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody> 
            </table>
            {% else %}
             	<div class="a-mess-yellow">Нет контента для отображения</div>
             {% endif %}
             
            <h3 class="stat-title">Баннера по умолчанию</h3>
            
            {% if banners_default[0] %}
            <table class="a-table"> 
                <thead> 
                    <tr> 
                        <th>#</th>
                        <th>Название</th> 
                        <th>Процент показов</th>
                        <th>Опубл.</th> 
                        <th>Показов</th>
                        <th>Переходов</th>
                        <th>CTR</th>
                        <th>Опции</th> 
                    </tr> 
                </thead> 
                <tbody id="products_new"> 
                    {% for b in banners_default[0] %}
                    <tr> 
                        <td>{{b.banner_id}}</td>
                        <td>
                            <a href="/admin/banner/edit-{{b.banner_id}}">{{b.company}} - {{b.name}}</a>
                        </td>
                        <td>{{b.percent}} %</td>
                        <td>{% if b.flag > 0%} Да {% else %} Нет {% endif %}</td>
                        <td>{{b.views|number_format(0, ',', ' ')}}</td>
                        <td>{{b.clicks}}</td>
                        <td>
                        	{% if b.clicks > 0 and b.views > 0%}
                            	{{((b.clicks / b.views) * 100)|number_format(3, ',', ' ')}}%
                            {%else%}
                            	0%
                            {% endif %}
                        </td>
                        <td class="ad-table-option">
                            <a href="/admin/banner/edit-{{b.banner_id}}"><i class="a-icon-pencil"></i></a>
                            <a class="delete-link" href="/admin/banner/delete-{{b.banner_id}}"><i class="a-icon-trash"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody> 
            </table>
            {% else %}
             	<div class="a-mess-yellow">Нет контента для отображения</div>
             {% endif %}
            
            <h3 class="stat-title">Не активные</h3>
             
             {% if no_active[0] %}
             <table class="a-table"> 
                <thead> 
                    <tr> 
                        <th>#</th>
                        <th>Название</th> 
                        <th>Процент показов</th>
                        <th>Опубл.</th> 
                        <th>Показов</th>
                        <th>Переходов</th>
                        <th>CTR</th>
                        <th>Опции</th> 
                    </tr> 
                </thead> 
                <tbody id="products_new"> 
                    {% for b in no_active[0] %}
                    <tr> 
                        <td>{{b.banner_id}}</td>
                        <td>
                            <a href="/admin/banner/edit-{{b.banner_id}}">{{b.company}} - {{b.name}}</a>
                        </td>
                        <td>{{b.percent}} %</td>
                        <td>{% if b.flag > 0%} Да {% else %} Нет {% endif %}</td>
                        <td>{{b.views|number_format(0, ',', ' ')}}</td>
                        <td>{{b.clicks}}</td>
                        <td>
                        	{% if b.clicks > 0 and b.views > 0%}
                            	{{((b.clicks / b.views) * 100)|number_format(3, ',', ' ')}}%
                            {%else%}
                            	0%
                            {% endif %}
                        </td>
                        <td class="ad-table-option">
                            <a href="/admin/banner/edit-{{b.banner_id}}"><i class="a-icon-pencil"></i></a>
                            <a class="delete-link" href="/admin/banner/delete-{{b.banner_id}}"><i class="a-icon-trash"></i></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody> 
            </table>
             {% else %}
             	<div class="a-mess-yellow">Нет контента для отображения</div>
             {% endif %}
        </div>
    </div>
{% endblock %}

{% block right %}
	<a href="/admin/banner/add" class="a-btn-green">Добавить баннер</a>
{% endblock %}