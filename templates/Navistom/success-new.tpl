{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}
{% block content %}
    {% set kk = {1:'1konc',2:'2konc',3:'3konc' } %}
    <noindex>
    <div class='m_success'>
        <div class='label_m'>{{ label|raw }}</div>
    </div>
    {% if pay<1 %}
        <div class='m_success' style='background:#f00'>
            <div class='label_m'>{{ label2|raw }}</div>
        </div>
    {% endif %}

    <h1 class='m_h1'>Сделайте это объявление заметным и красивым!</h1>
    {% if section==4 %}
        <div class="count_vip" style="color:#B2B2B2">
            <p>Мы не берем оплату за размещение объявления, но публикуем после оплаты продвижения. <br/>
                Продвижение это: ТОП, В конкурентах, Золотой, Срочно!, Поднять вверх, Опубликовать в журнале.</p>
            Минимальная оплата 28 грн, максимальная - на Ваше усмотрение.</p><p>
                Объявление после любой оплаты будет отображаться на сайте 50 дней.</p></div>
    {% endif %}


    <div class='clear'></div>
    <div class='all_form_success'>
    <form id='success_form' method='post' data-price="{{ price_json }}">
    <div>
    <ul class='menu_folding' style='font: 12px/1.2 Arial,Helvetica ,sans-serif !important'>
    {% if count_tpl > 0 %}
        <li>

            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='top_i' name='vip'
                        {% if fields['price'] == price['1'] %} value='1'{% endif %}
                        {% if fields['price'] ==price['2'] %} value='2'{% endif %}
                        {% if fields['price'] ==price['3'] %} value='3'{% endif %}
                       noUniform='true' {% if fields['price'] >0 %}
                    checked="checked" {% endif %} />

                <label for='top_i'>
                    <div></div>
                </label>
            </div>
            <span class='icon_img b_727272'><img src='/templates/Navistom/images/vip/top.png'/> </span>
            <span class='l_m_f'>ТОП-размещение на Главной NaviStom</span>

            <div class='position_right'>
                <label class="custom-select">

                    <select id='top_s'>
                        <option value='3'{% if fields['price'] ==price['3'] %}   selected="selected"{% endif %} >
                            10 дней - {{ price['3'] }}  грн
                        </option>
                        <option value='2' {% if fields['price'] ==price['2'] %}   selected="selected"{% endif %}>
                            20 дней - {{ price['2'] }}  грн
                        </option>
                        <option value='1' {% if fields['price'] ==price['1'] %}   selected="selected"{% endif %} >
                            30 дней - {{ price['1'] }} грн
                        </option>


                    </select>
                </label>
                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'>  {% if fields['price'] >0 %}{{ fields['price'] }} {% else %} {{ price['3'] }}   {% endif %}</span>грн</span>
            </div>
        </li>
        <li>
            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='konc_i' name='show_competitor' value='3'
                       noUniform='true'  {% if fields['show_competitor'] >0 %}
                    checked="checked" {% endif %} />
                <label for='konc_i'>
                    <div></div>
                </label>
            </div>
            <span class='icon_img '><img src='/templates/Navistom/images/vip/konkurenti.png'/> </span>
            <span class='l_m_f'>Отобразить в объявлениях конкурентов</span>

            <div class='position_right'>
                <label class="custom-select">

                    <select id='konc_s'>
                        <option value='3konc' {% if fields['show_competitor'] == price['3konc'] %}   selected="selected" {% endif %}   >
                            10 дней - {{ price['3konc'] }} грн
                        </option>

                        <option value='2konc' {% if fields['show_competitor'] == price['2konc'] %}  selected="selected" {% endif %}  >
                            20 дней - {{ price['2konc'] }}грн
                        </option>

                        <option value='1konc'  {% if fields['show_competitor'] == price['1konc'] %}  selected="selected" {% endif %}   >
                            30 дней - {{ price['1konc'] }} грн
                        </option>
                    </select>
                </label>
                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'> {% if fields['show_competitor'] %} {{ fields['show_competitor'] }} {% else %} {{ price['3konc'] }} {% endif %}</span>грн</span>
            </div>
        </li>
        <li>
            <div class='position_left'>

                <input type='checkbox' class='vip-checkbox' name='color_yellow' id='yellow_i'
                       value="{{ price['color_yellow'] }}" noUniform='true' {% if fields['color_yellow'] >0 %}
                    checked="checked" {% endif %}/>
                <label for='yellow_i'>
                    <div></div>
                </label>

            </div>
            <span class='icon_img '><img src='/templates/Navistom/images/vip/yellow.png'/> </span>

            <span class='l_m_f'>Золотой фон на все время размещения</span>

            <div class='position_right'>

                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'>  {{ price['color_yellow'] }} </span>грн</span>
            </div>


        </li>
        <li>
            <div class='position_left'>

                <input type='checkbox' class='vip-checkbox' name='urgently' id='urgently'
                       value="{{ price['urgently'] }}" noUniform='true' {% if fields['urgently'] >0 %}
                    checked="checked" {% endif %} />
                <label for='urgently'>
                    <div class='c_ck'></div>
                </label>


            </div>
            <span class='icon_img'><span class="srochno">Cрочно!</span> </span>


            <span class='l_m_f'>Метка на все время размещения + Поднять вверх списка  </span>

            <div class='position_right'>
                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'> {{ price['urgently'] }}</span>грн</span>
            </div>

        </li>
        <li>

            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='update_date' name='update_date'
                       value="{{ price['update_date'] }}" noUniform='true'
                        {% if fields['update_date'] >0 %} checked="checked" {% endif %} />
                <label for='update_date'>
                    <div class='c_ckk'></div>
                </label>
            </div>
            <span class='icon_img b_7961c7'><img src='/templates/Navistom/images/vip/verx.png'/> </span>
            <span class='l_m_f'>Поднять вверх списка + Обновить дату</span>

            <div class='position_right'>

                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'>  {{ price['update_date'] }}</span>грн</span>
            </div>

        </li>

    {% else %}

        <li>
            {% if select['date_end']==false %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='top_i'
                           name='vip'  {% if checkbox.top %} value="{{ checkbox.top }}" {% else %}value="1" {% endif %}
                           noUniform='true' {% if checkbox.top %} checked="checked"{% endif %}   />
                    <label for='top_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img b_727272'><img src='/templates/Navistom/images/vip/top.png'/> </span>
                <span class='l_m_f'>ТОП-размещение на Главной NaviStom</span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='top_s'>
                            <option value='3' {% if checkbox.top=='3' %} selected="selected" {% endif %} >10 дней
                                - {{ price['3'] }}грн
                            </option>
                            <option value='2'{% if checkbox.top=='2' %} selected="selected" {% endif %}>20 дней
                                - {{ price['2'] }} грн
                            </option>
                            <option value='1'{% if checkbox.top=='1' %} selected="selected" {% endif %}>30 дней
                                - {{ price['1'] }} грн
                            </option>

                        </select>
                    </label>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                                class='price color_000005'> {% if checkbox.top %} {{ price[checkbox.top ] }}  {% else %} {{ price['3'] }} {% endif %} </span>грн</span>
                </div>
            {% else %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='top_i'
                           name='vip' {% if checkbox.top %} value="{{ checkbox.top }}" {% else %}value="1" {% endif %}
                           noUniform='true' checked="checked"/>
                    <label for='top_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img b_727272'><img src='/templates/Navistom/images/vip/top.png'/> </span>
                <span class='l_m_f'>В ТОП на Главной NaviStom  до {{ select['date_t'] }} </span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='top_s'>
                            <option value='3' {% if checkbox.top=='3' %} selected="selected" {% endif %} >10 дней
                                - {{ price['3'] }}грн
                            </option>
                            <option value='2'{% if checkbox.top=='2' %} selected="selected" {% endif %}>20 дней
                                - {{ price['2'] }} грн
                            </option>
                            <option value='1'{% if checkbox.top=='1' %} selected="selected" {% endif %}>30 дней
                                - {{ price['1'] }} грн
                            </option>


                        </select>
                    </label>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                                class='price color_000005'>   {% if checkbox.top %} {{ price[checkbox.top ] }}  {% else %} {{ price['3'] }} {% endif %}  </span>грн</span>
                </div>
            {% endif %}
        </li>


        <li>
            {% if select['show_competitor']==false %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='konc_i'
                           name='show_competitor' {% if checkbox.kon %} value="{{ checkbox.kon }}" {% else %}value="1" {% endif %}
                           noUniform='true'  {% if checkbox.kon %} checked="checked"{% endif %}  />
                    <label for='konc_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img '><img src='/templates/Navistom/images/vip/konkurenti.png'/> </span>
                <span class='l_m_f'>Отобразить в объявлениях конкурентов</span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='konc_s'>
                            <option value='3konc' {% if checkbox.kon=='3' %} selected="selected" {% endif %} >10
                                дней- {{ price['3konc'] }} грн
                            </option>
                            <option value='2konc' {% if checkbox.kon=='2' %} selected="selected" {% endif %}>20 дней
                                - {{ price['2konc'] }} грн
                            </option>
                            <option value='1konc' {% if checkbox.kon=='1' %} selected="selected" {% endif %}>30 дней
                                -  {{ price['1konc'] }} грн
                            </option>


                        </select>
                    </label>
				
				<span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  {% if checkbox.kon %}

                            {{ price[ kk[checkbox.kon] ] }}  {% else %} {{ price['3konc'] }} {% endif %} </span>грн  </span>
                </div>
            {% else %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='konc_i' name='show_competitor' value='3'
                           noUniform='true' checked="checked"/>
                    <label for='konc_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img '><img src='/templates/Navistom/images/vip/konkurenti.png'/> </span>
                <span class='l_m_f'>В конкурентах до {{ select['show_comp'] }}</span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='konc_s'>
                            <option value='3konc' {% if select['show_comp'] == price['3konc'] %}   selected="selected" {% endif %}   >
                                10 дней - {{ price['3konc'] }} грн
                            </option>
                            <option value='2konc' {% if select['show_comp'] == price['2konc'] %}  selected="selected" {% endif %}  >
                                20 дней - {{ price['2konc'] }}грн
                            </option>
                            <option value='1konc'  {% if select['show_comp'] == price['1konc'] %}  selected="selected" {% endif %}   >
                                30 дней - {{ price['1konc'] }} грн
                            </option>

                        </select>
                    </label>
				<span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  {% if checkbox.kon %}

                            {{ price[ kk[checkbox.kon] ] }}  {% else %} {{ price['3konc'] }} {% endif %}</span>грн</span>
                </div>
            {% endif %}
        </li>


        <li>
            <div class='position_left'>
                {% if select['color_yellow']==false %}
                    <input type='checkbox' class='vip-checkbox' name='color_yellow' id='yellow_i'
                           value="{{ price.color_yellow }}"
                           noUniform='true'  {% if checkbox.color_yellow %}  checked="checked" {% endif %}/>
                    <label for='yellow_i'>
                        <div></div>
                    </label>
                {% endif %}
            </div>
            <span class='icon_img '><img src='/templates/Navistom/images/vip/yellow.png'/> </span>
            {% if select['color_yellow']==false %}
                <span class='l_m_f'>Золотой фон на все время размещения + Поднять вверх списка</span>
                <div class='position_right'>

                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                                class='price color_000005'>  {{ price.color_yellow }} </span>грн</span>
                </div>
            {% else %}
                <span class='label_center'> Золотой фон на все время размещения + Поднять вверх списка</span>
                <img src='/templates/Navistom/images/vip/galochka-check_24x24.png'/>
            {% endif %}

        </li>


        <li>
            <div class='position_left'>
                {% if select['urgently']==false %}
                    <input type='checkbox' class='vip-checkbox' name='urgently'
                           id='urgently' {% if  checkbox.urgently %} checked='checked' {% endif %}
                           value="{{ price.urgently }}" noUniform='true'/>
                    <label for='urgently'>
                        <div class='c_ck'></div>
                    </label>

                {% endif %}
            </div>
            <span class='icon_img'><span class="srochno">Cрочно!</span> </span>
            {% if select['urgently']==false %}

                <span class='l_m_f'>Метка на все время размещения + Поднять вверх списка </span>
                <div class='position_right'>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                                class='price color_000005'>  {{ price.urgently }} </span>грн</span>
                </div>
            {% else %}
                <span class='label_center'>Метка на все время размещения + Поднять вверх списка</span>
                <img src='/templates/Navistom/images/vip/galochka-check_24x24.png'/>
            {% endif %}
        </li>
        <li>

            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='update_date' name='update_date'
                       value="{{ price.update_date }}" noUniform='true'
                       {% if fields['update_date'] >0 or checkbox.update_date %}checked="checked" {% endif %}  />
                <label for='update_date'>
                    <div class='c_ck'></div>
                </label>
            </div>
            <span class='icon_img b_7961c7'><img src='/templates/Navistom/images/vip/verx.png'/> </span>
            <span class='l_m_f'>Поднять вверх списка + Обновить дату</span>

            <div class='position_right'>

                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'>  {{ price.update_date }} </span>грн</span>
            </div>

        </li>


    {% endif %}


    <li>
        <div class='position_left'>
            <input type='checkbox' class='vip-checkbox' id='jurnal_i' name='jurnal' value='1'
                   noUniform='true' {% if fields['jurnal_cat'] >0  or ( checkbox.jurnal and count_tpl=='0') %}  checked="checked" {% endif %} />
            <label for='jurnal_i'>
                <div></div>
            </label>
        </div>
        <span class='icon_img '><img src='/templates/Navistom/images/vip/magazine-new-icon.png'/> </span>
			  <span class='l_m_f'><a target='_blank' href='/journals' class='' style='display:inline-block'>
                      Опубликовать объявление в нашем журнале <br/>
			  <span style='font-size: 13px;color: #999;position: relative;bottom: 5px;'>
			  Разработка рекламного макета входит в стоимость</span></a>
			  </span>

        <div class='position_right'>

            <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                        class='price color_000005'> {{ price.jurnal }}</span>грн</span>
        </div>

    </li>


    </ul>
    <div class='label_sum'>
        <span class='c_t'>Сейчас на NaviStom <strong class='red'>{{ count|number_format(0, '', ' ') }}</strong> объявлений</span>

        Всего <span class='suma_price'>0</span> грн
    </div>
    </div>
    <div{% if user_info.info.group_id != 10 %} id='show' {% endif %}>
        <div id='all_payments'>
            <div id='port' class='payments active'>
                <div>
                    <img src='/templates/Navistom/images/vip/port.png'/>
                </div>
                <div class='i_put'>
                    <input type="radio" noUniform='true' id='p1' class='plat-radio' name="plat" value="port" checked/>
                    <label for='p1'>
                    </label>
                    <span>Банковская карта</span>
                </div>
            </div>

            <div id='liq' class='payments'>
                <div>
                    <img src='/templates/Navistom/images/vip/priv.png'/>
                </div>
                <div class='i_put'>
                    <input type="radio" noUniform='true' id='p2' class='plat-radio' name="plat" value="liq"/>
                    <label for='p2'>
                    </label>
					<span>
					  Приват24 
					</span>
                </div>
            </div>
            <div class='clear'></div>
        </div>
        <div class='clear'></div>
        <div class='vip_center'>
            <div class="a-row">
                <!--a href='/' class=" input-submit-green" target='_black'> ОПЛАТИТЬ И ОПУБЛИКОВАТЬ</a-->

                <div style='margin:0 auto; text-align: center;'>
                    <input type='hidden' name='resource_id' value="{{ add['resource_id'] }}"/>
                    <input type='hidden' name='section_id' value="{{ add['section_id'] }}"/>
                    <button type="submit" class=" input-submit-green2" id='sub_success'> Оплатить</button>
                </div>
            </div>

            <div class='liqpay'>

            </div>
        </div>
    </div>
    <ul class='form_bottom_menu'>
        <li>
            <a href="{{ add['link'] }}/{{ add['resource_id'] }}-{{ add['product_name']|translit }}"> {{ add['product_name'] }}  {{ add['name'] }}</a>
        </li>
        <li>
            <a href="/">Перейти на Главную </a>
        </li>

        <li>
            <a href="/cabinet"> Перейти в Личный кабинет</a>
        </li>
    </ul>
    <p class='pn-pt'>По вопросам рекламы обращайтесь: +38-044-573-97-73, 067-460-86-78 пн-пт с 10-00 до 17-00</p>
    </form>
    </div>
    <style>
        #adunit, #reklama, .title {
            display: none;
        }

    </style>


    </noindex>
    <!--div id="ajax-loader">Загрузка...</div-->
{% endblock %}

