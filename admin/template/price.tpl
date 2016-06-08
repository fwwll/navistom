{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
<style>
.update-price-admin{
	text-align:center;
}
.update-price-admin img{
	width:30px;
}
</style>
    <h1 class="ad-title">
        <b>Цены на услуги </b>
        <span></span>
    </h1>

         <div id='loads'>
        <table class="a-table tablesorter" id='tab'>
	  <thead>
            <tr>
            <th>раздел</th>
			<th>Вид продвижения | По умолчанию</th>
			<th>Цена</th>
			<th>Сохранить</th>
            </tr>
			
      </thead>
            <tbody id="section">
		{%for val in price %}
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Топ на 30 дней
				    <input type="radio"   name="top_{{val.section_id}}" value='1'  {% if val.top_c=='1' %} checked="checked"   {%endif%} />
				</td>  
				<td>
				  <input type='text' name='1' value='{{val[1]}}' section='{{val.section_id}}'  />грн
				  
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Топ на 20 дней
					<input type="radio"   name="top_{{val.section_id}}" value='2' {% if val.top_c=='2' %} checked="checked"   {%endif%} />
				</td>  
				<td>
				  <input type='text' name='2' value='{{val[2]}}' section='{{val.section_id}}' />грн
				
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Топ на 10 дней
				  <input type="radio"   name="top_{{val.section_id}}" value='3' {% if val.top_c=='3' %} checked="checked"   {%endif%} />
				</td>
				
				<td>
				  <input type='text' name='3' value='{{val[3]}}' section='{{val.section_id}}' />грн
				 
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>В конкурентах 30 дней
					<input type="radio"   name="konk_{{val.section_id}}" value='1'  {% if val.kon_c =='1' %} checked="checked"   {%endif%} /> 
				</td>  
				<td>
				  <input type='text'  name='1konc' value='{{val["1konc"]}}' section='{{val.section_id}}' />грн
				</td>
				<td>
				  <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>В конкурентах 20 дней
				   <input type="radio"   name="konk_{{val.section_id}}" value='2' {% if val.kon_c=='2' %} checked="checked"   {%endif%} /> 
				</td>
				<td>
				  <input type='text' name='2konc'  value='{{val["2konc"]}}' section='{{val.section_id}}' />грн
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>В конкурентах 10 дней
				   <input type="radio"   name="konk_{{val.section_id}}" value='3' {% if val.kon_c=='3' %} checked="checked"   {%endif%} />
				</td>
					
				<td>
				  <input type='text' name='3konc' value='{{val["3konc"]}}' section='{{val.section_id}}' />грн 
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Покрасить в золотой
				 <input type="checkbox"  name='check_color_yellow'  value='1'   {% if val.color_yellow_c==1 %} checked="checked"   {%endif%} />
				</td>  
				<td>
				  <input type='text' name='color_yellow' value='{{val.color_yellow}}' section='{{val.section_id}}' />грн
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Метка срочно
				 <input type="checkbox"  name='check_urgently'  value='1' {% if val.urgently_c==1 %} checked="checked"   {%endif%} />
				</td>  
				<td>
				  <input type='text' name='urgently' value='{{val.urgently}}' section='{{val.section_id}}' />грн
				
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>	
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Опубликовать в журнале
				 <input type="checkbox"  name='check_jurnal'  value='1' {% if val.jurnal_c==1 %} checked="checked"   {%endif%} />
				</td>  
				<td>
				  <input type='text' name='jurnal' value='{{val.jurnal}}' section='{{val.section_id}}' />грн
				  
				</td>
				<td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>				
			</tr>
			<tr>
			    <td class='name'>{{val.name}}</td>
				<td class='top'>Обновить дату
				  <input type="checkbox"  name='check_update_date'  value='1' {% if val.update_date_c==1 %} checked="checked"   {%endif%}  />
				</td>
                 			
				<td>
				  <input type='text' name='update_date' value='{{val.update_date}}' section='{{val.section_id}}' />грн
				</td>
                 <td>
				   <span class='update-price-admin'><img src='/admin/template/images/ok.png' /></span>
				 </td>
			</tr>
			
		{% endfor %}
			</tbody>
	 </table>
      

</div>	
  
   

{% endblock %}

{% block right %}

{% endblock %}