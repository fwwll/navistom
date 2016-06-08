{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>ПРОДВИЖЕНИЕ ОБЪЯВЛЕНИЙ</b>
        <span></span>
    </h1>
    <form id='filter'>
	<div class='all_jurnal'>
	<div class='jurnal'>
		<input type='checkbox' name='aktiv' value='1'/>АКТИВНЫЕ<br/>
		<input type='checkbox' name='archive' value='1' />АРХИВ<br/>
		<input type='checkbox' name='liqpay' value='1'/>LIQPAY<br/>
		<input type='checkbox' name ='portmone' value='1'/>PORTMONE<br/>
		<input type='checkbox' name='admin' value='1'/>ADMIN<br/>
		<input type='checkbox' name='top_3' value='1'/>3 end TOP
	</div>
	<div class='jurnal'>
		<input type='checkbox' name='jurnal_cat1' value='1'/>Объявление с фото<br/>
		<input type='checkbox' name='jurnal_cat2' value='2'/>1/8 страницы А5<br/>
		<input type='checkbox' name='jurnal_cat3' value='3'/>1/4 страницы А5<br/>
		<input type='checkbox' name='jurnal_cat4' value='4'/>1/2 страницы А5<br/>
		<input type='checkbox' name='jurnal_cat5' value='5'/>Страница А5
	</div>
	</div>
	<div class="" style='margin-top:10px;'>
        

        <input placeholder="Дата начала" type="text" name="date_start" id="date_start"  />
        <i class="a-icon-calendar"></i><br/>
        <input value="" placeholder="Дата окончания" type="text" name="date_end" id="date_end" />
        <i class="a-icon-calendar"></i>
		<div class='reset' style='width: 21px; display: inline-block;margin-top: -12px;position: absolute;cursor: pointer;'>
		  <img src='/templates/Navistom/images/reset.jpg'  width='100%' onClick="jQuery('input[type=text]').val('');"/>
		</div>
    </div>
	
	
<div id='sum'  >{{ sum[0].all_sum}}.грн  </div>		`
   </form>
         <div id='loads'>
        <table class="a-table tablesorter" id='tab'>
	  <thead>
            <tr>
            <th>Объявлене</th>
			<th>Вид продвижения</th>
			<th>Способ оплаты</th>
			<th> КОЛИЧЕСТВО ДНЕЙ ДО ОКОНЧАНИЯ ТОП</th>
            </tr>
			
      </thead>
            <tbody id="section">
		{% set TOP ={ '1':'30','2':'20','3':'10','4':'Admin','150':'30','100':'20','50':'10','196':'30','154':'20','98':'10'} %}
		
		{% set KON ={ '1':'30','2':'20','3':'10','4':'Admin','224':'30','168':'20','112':'10','150':'30','100':'20','50':'10'} %}
		{% set JURNAL ={
						1:'Объявление с фото - 98',
						2:'1/8 страницы А5  - 800',
						3:'1/4 страницы А5 - 1350',
						4:'1/2 страницы А5 - 2250',
						5:'Страница А5 - 3500'
						} %}
		{%for val in all %}
		    
			{% set x = val.order_id|slice(-1) %}
			 
			{% set i_m_s= val.date_start|split('-')%}
			{%  set i_m_e= val.date_end|split('-') %}
			{% set times_start= month[i_m_s[1]] %}
			{% set times_end= month[i_m_e[1]] %}
			<tr {% if val.d <4 and val.d > 0%} style='background:#FFE8E8' {%endif%}   {% if (val.d < 1 and val.price >0 )  %} style='background:#cecece' {%endif%}      >
			    <td>
				 {{val.add_data}}<br/>
				{{val.section}}<br/>
				 id :{{val.resource_id}}<br/>
				 <a href='{{val.link}}/{{val.resource_id}}-{{val.product_name|translit}}' target='_blank'> {{val.product_name|slice(0,100)}}  </a></br>
				{%if flag %}
				  <a href="/admin/payment/"> {{val.name}}</a></br>
				{%else%}
				  <a href="/admin/payment/&user={{val.user_id}}"> {{val.name}}</a></br>
			    {%endif%}
				  {{val.contact_phones}} <br/>
				  
				 
				   
				</td>
			
				<td>
				     {% set all_sum =0%} 
				     {%if  val.price > 0%}
					 {%set all_sum= all_sum+val.price %}
					<span class='t_p'>топ-{{TOP[ val.list_price[ val.price]] }}  </span><br/>
					{%endif%}
					{%if val.show_competitor > 0 %}
					  {%set all_sum= all_sum+ val.show_competitor %}
					<span class='t_p'> +в конкурентах - {{KON[val.list_price[val.show_competitor]]}}</span><br/>
					{%endif%}
					
					
					{%if val.color_yellow > 0 %}
					  {%set all_sum= all_sum + val.color_yellow %}
					<span class='t_p'> +золотой </span><br/>
					{%endif%}
					
					{%if val.urgently > 0 %}
					  {%set all_sum= all_sum + val.urgently %}
					<span class='t_p'> +срочно </span><br/>
					{%endif%}
					{%if val.update_date > 0 %}
					 {%set all_sum= all_sum + val.update_date %}
					<span class='t_p'> +поднять вверх -{{val.update_date }}</span><br/>
					{%endif%}
					
					{%if val.jurnal %}
					 {%set all_sum= all_sum + val.jurnal %}
					<span class='t_p'> {{JURNAL[val.jurnal_cat]}}</span><br/>
					{%endif%}
				
				  
				<td>
				{%if val.service_payment=='admin'%}
				     0.грн <span class='l_p_a'>{{val.service_payment }}</span>
					{%else%}
					  {{all_sum }}.грн <span class='l_p_a'>{{val.service_payment }}</span>
					{%endif%}
				</td>
				
				<td >
				
				{%if  val.price >0  or  val.service_payment == 'admin' %}
					<!--{{i_m_s[2]}} {{times_start}} {{i_m_s[0]}} г.<br>
					до {{i_m_e[2]}} {{times_end}} {{i_m_e[0]}} г.-->
						 TOP: {{val.d}} <br/>
				{%endif%}
				
				 {%if val.co >0 %}
                    В кон: {{ val.co}}
				 {%endif%}    				
				</td>
				
				
			</tr>
		{% endfor %}
			</tbody>
	 </table>
      
	  
	  
	<div>
	
    {% if pagination.pages %}
        <ul class="a-pagination">
            <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                {% if route.values.user_id > 0 %}
                    <a href="/admin/payment/&page={{pagination.first.url}}{{ url }}">{{pagination.first.name}}</a>
                {% elseif route.values.categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.first.url }}/categ-{{route.values.categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.first.url }}/categ-{{route.values.categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/admin/payment/&page={{ pagination.first.url }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                    {% endif %}
                {% elseif route.values.sub_categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.first.url }}/sub_categ-{{route.values.sub_categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.first.url }}/sub_categ-{{route.values.sub_categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/admin/payment/&page={{ pagination.first.url }}/sub_categ-{{route.values.sub_categ_id}}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                    {% endif %}
                {% elseif route.values.user_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.first.url }}/user-{{route.values.user_id}}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                {% elseif route.values.producer_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.first.url }}/firm-{{route.values.producer_id}}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                {% elseif route.values.product_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.first.url }}/product-{{route.values.product_id}}-{{route.values.translit}}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/admin/payment/&page={{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.user_id > 0 %}
                        <a href="/admin/payment/&page={{pagination.prev_page}}{{ url }}">«</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.prev_page }}/categ-{{route.values.categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">«</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.prev_page }}/categ-{{route.values.categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">«</a>
                        {% else %}
                            <a href="/admin/payment/&page={{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.prev_page }}/sub_categ-{{route.values.sub_categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">«</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.prev_page }}/sub_categ-{{route.values.sub_categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">«</a>
                        {% else %}
                            <a href="/admin/payment/&page={{ pagination.prev_page }}/sub_categ-{{route.values.sub_categ_id}}-{{route.values.translit}}">«</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.prev_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.prev_page }}/firm-{{route.values.producer_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.prev_page }}/product-{{route.values.product_id}}-{{route.values.translit}}">«</a>
                    {% else %}
                        <a href="/admin/payment/&page={{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
				
                <li {% if page == p.name  %} class="active" {% endif %}>
                        <a href="/admin/payment/&page={{p.name}}">{{p.name}}</a>
                   
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.user_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.next_page }}{{ url }}">»</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.next_page }}/categ-{{route.values.categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">»</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.next_page }}/categ-{{route.values.categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">»</a>
                        {% else %}
                            <a href="/admin/payment/&page={{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.next_page }}/sub_categ-{{route.values.sub_categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">»</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/admin/payment/&page={{ pagination.next_page }}/sub_categ-{{route.values.sub_categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">»</a>
                        {% else %}
                            <a href="/admin/payment/&page={{ pagination.next_page }}/sub_categ-{{route.values.sub_categ_id}}-{{route.values.translit}}">»</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.next_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.next_page }}/firm-{{route.values.producer_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.next_page }}/product-{{route.values.product_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.search %}
                        <a href="/admin/payment/&page={{ pagination.next_page }}/search-{{route.values.search}}">»</a>
                    {% else %}
                        <a href="/admin/payment/&page={{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.user_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.last.url }}{{ url }}">{{ pagination.last.name }}</a>
                {% elseif route.values.categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.last.url }}/categ-{{route.values.categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.last.url }}/categ-{{route.values.categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/admin/payment/&page={{ pagination.last.url }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                    {% endif %}
                {% elseif route.values.sub_categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.last.url }}/sub_categ-{{route.values.sub_categ_id}}/firm-{{ route.values.producer_id }}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/admin/payment/&page={{ pagination.last.url }}/sub_categ-{{route.values.sub_categ_id}}/product-{{ route.values.product_id }}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/admin/payment/&page={{ pagination.last.url }}/sub_categ-{{route.values.sub_categ_id}}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                    {% endif %}
                {% elseif route.values.user_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.last.url }}/user-{{route.values.user_id}}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                {% elseif route.values.producer_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.last.url }}/firm-{{route.values.producer_id}}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                {% elseif route.values.product_id > 0 %}
                    <a href="/admin/payment/&page={{ pagination.last.url }}/product-{{route.values.product_id}}-{{route.values.translit}}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/admin/payment/&page={{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>

    {% endif %}

    </div> 
</div>	
   <script>
   jQuery(document).ready(function(){
	 
	   var filter_fun=function(url){ 	   
		if(typeof url=='undefined'){
			url= $(location).attr('pathname');	  
		}
			//var  url= $(location).attr('pathname');
				var d=$('#filter').serialize();
				$.post( url, d ,function(s){
							$('#loads').replaceWith($(s).find('#loads'))
							$('#sum').replaceWith($(s).find('#sum'))
							
							//$('.a-pagination').replaceWith($(s).find('.a-pagination'));
							  /* alert($(s).find('.a-pagination').find('a').langth );
							  if($(s).find('.a-pagination').find('a').langth ){
								      $('.a-pagination').show(); 
							          $('.a-pagination').replaceWith($(s).find('.a-pagination'));
									 // alert('1');
							  }else{
								 $('.a-pagination').hide(); 
								 //alert('2');
							  }   */
							   window.history.pushState(null, null, url);
							})  
			
        }
	   
	   
	    $('#filter').on('click',function(){
				
			 filter_fun();
	    }) 
      

		 $("#date_end ,#date_start").datepicker({
			 onSelect:function(){filter_fun();},
		     dateFormat:'yy-mm-dd'
		 });
		 
		 /* $('body').on('click','.a-pagination li', function(e){
			e.preventDefault();
			e.stopPropagation();
			var u =$('a', this).attr('href');
			filter_fun(u);
		})   */
		 
   })
   </script> 
   

{% endblock %}

{% block right %}

{% endblock %}