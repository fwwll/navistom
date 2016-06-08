{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}
{% block content %}


    <div class='m_success'>
        <div class='label_m'>{{ label|raw }}</div>
    </div>


    <h1 class='m_h1'>Сделайте это объявление заметным и красивым!</h1>
    <span class='c_t'>Сейчас на NaviStom <strong
                class='red'>{{ count|number_format(0, '', ' ') }}</strong> объявлений</span>
    <div class='clear'></div>
    <div class='all_form_success'>
    <form id='success_form' method='post'>
    <div>
    <ul class='menu_folding'>
    {% if count_tpl > 0 %}
        <li>

            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='top_i' name='vip'
                        {% if fields['price'] ==150 %} value='1'{% endif %}
                        {% if fields['price'] ==100 %} value='2'{% endif %}
                        {% if fields['price'] ==50 %} value='3'{% endif %}
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
                        <option value='1' {% if fields['price'] ==150 %} selected="selected"{% endif %} >30 дней
                        </option>
                        <option value='2' {% if fields['price'] ==100 %}  selected="selected"{% endif %}>20 дней
                        </option>
                        <option value='3'{% if fields['price'] ==50 %}  selected="selected"{% endif %} >10 дней</option>

                    </select>
                </label>
                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span
                            class='price color_000005'>  {% if fields['price'] >0 %}{{ fields['price'] }} {% else %} 150 {% endif %}</span>грн</span>
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
                        <option value='1' selected="selected">30 дней</option>
                        <option value='2'>20 дней</option>
                        <option value='3'>10 дней</option>

                    </select>
                </label>
                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'> 150 </span>грн</span>
            </div>
        </li>
        <li>
            <div class='position_left'>

                <input type='checkbox' class='vip-checkbox' name='color_yellow' id='yellow_i' value='20'
                       noUniform='true' {% if fields['color_yellow'] >0 %}
                    checked="checked" {% endif %}/>
                <label for='yellow_i'>
                    <div></div>
                </label>

            </div>
            <span class='icon_img '><img src='/templates/Navistom/images/vip/yellow.png'/> </span>

            <span class='l_m_f'>Золотой фон на все время размещения</span>

            <div class='position_right'>

                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  20 </span>грн</span>
            </div>


        </li>
        <li>
            <div class='position_left'>

                <input type='checkbox' class='vip-checkbox' name='urgently' id='urgently' value='20'
                       noUniform='true' {% if fields['urgently'] >0 %}
                    checked="checked" {% endif %} />
                <label for='urgently'>
                    <div class='c_ck'></div>
                </label>


            </div>
            <span class='icon_img'><span class="srochno">Cрочно!</span> </span>


            <span class='l_m_f'>Метка на все время размещения</span>

            <div class='position_right'>
                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  20 </span>грн</span>
            </div>

        </li>
        <li>

            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='update_date' name='update_date' value='10'
                       noUniform='true'/>
                <label for='update_date'>
                    <div class='c_ckk'></div>
                </label>
            </div>
            <span class='icon_img b_7961c7'><img src='/templates/Navistom/images/vip/verx.png'/> </span>
            <span class='l_m_f'>Поднять вверх списка</span>

            <div class='position_right'>

                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  10 </span>грн</span>
            </div>

        </li>

    {% else %}

        <li>
            {% if select['date_end']==false %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='top_i' name='vip' value='3' noUniform='true'
                           checked="checked"/>
                    <label for='top_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img b_727272'><img src='/templates/Navistom/images/vip/top.png'/> </span>
                <span class='l_m_f'>ТОП-размещение на Главной NaviStom</span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='top_s'>
                            <option value='1'>30 дней</option>
                            <option value='2'>20 дней</option>
                            <option value='3' selected="selected">10 дней</option>

                        </select>
                    </label>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  50 </span>грн</span>
                </div>
            {% else %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='top_i' name='vip' value='3' noUniform='true'
                           checked="checked"/>
                    <label for='top_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img b_727272'><img src='/templates/Navistom/images/vip/top.png'/> </span>
                <span class='l_m_f'>В ТОП на Главной NaviStom  до {{ select['date_t'] }} </span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='top_s'>
                            <option value='1'>30 дней</option>
                            <option value='2'>20 дней</option>
                            <option value='3' selected="selected">10 дней</option>

                        </select>
                    </label>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  50 </span>грн</span>
                </div>
            {% endif %}
        </li>


        <li>
            {% if select['show_competitor']==false %}
                <div class='position_left'>
                    <input type='checkbox' class='vip-checkbox' id='konc_i' name='show_competitor' value='3'
                           noUniform='true' checked="checked"/>
                    <label for='konc_i'>
                        <div></div>
                    </label>
                </div>
                <span class='icon_img '><img src='/templates/Navistom/images/vip/konkurenti.png'/> </span>
                <span class='l_m_f'>Отобразить в объявлениях конкурентов</span>
                <div class='position_right'>
                    <label class="custom-select">

                        <select id='konc_s'>
                            <option value='1'>30 дней</option>
                            <option value='2'>20 дней</option>
                            <option value='3' selected="selected">10 дней</option>

                        </select>
                    </label>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'> 50 </span>грн</span>
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
                            <option value='1'>30 дней</option>
                            <option value='2'>20 дней</option>
                            <option value='3' selected="selected">10 дней</option>

                        </select>
                    </label>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'> 50 </span>грн</span>
                </div>
            {% endif %}
        </li>


        <li>
            <div class='position_left'>
                {% if select['color_yellow']==false %}
                    <input type='checkbox' class='vip-checkbox' name='color_yellow' id='yellow_i' value='20'
                           noUniform='true' checked="checked"/>
                    <label for='yellow_i'>
                        <div></div>
                    </label>
                {% endif %}
            </div>
            <span class='icon_img '><img src='/templates/Navistom/images/vip/yellow.png'/> </span>
            {% if select['color_yellow']==false %}
                <span class='l_m_f'>Золотой фон на все время размещения</span>
                <div class='position_right'>

                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  20 </span>грн</span>
                </div>
            {% else %}
                <span class='label_center'> Золотой фон на все время размещения</span>
                <img src='/templates/Navistom/images/vip/galochka-check_24x24.png'/>
            {% endif %}

        </li>


        <li>
            <div class='position_left'>
                {% if select['urgently']==false %}
                    <input type='checkbox' class='vip-checkbox' name='urgently' id='urgently' value='20'
                           noUniform='true' checked="checked"/>
                    <label for='urgently'>
                        <div class='c_ck'></div>
                    </label>

                {% endif %}
            </div>
            <span class='icon_img'><span class="srochno">Cрочно!</span> </span>
            {% if select['urgently']==false %}

                <span class='l_m_f'>Метка на все время размещения</span>
                <div class='position_right'>
                    <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  20 </span>грн</span>
                </div>
            {% else %}
                <span class='label_center'>Метка на все время размещения</span>
                <img src='/templates/Navistom/images/vip/galochka-check_24x24.png'/>
            {% endif %}
        </li>
        <li>

            <div class='position_left'>
                <input type='checkbox' class='vip-checkbox' id='update_date' name='update_date' value='10'
                       noUniform='true'/>
                <label for='update_date'>
                    <div class='c_ck'></div>
                </label>
            </div>
            <span class='icon_img b_7961c7'><img src='/templates/Navistom/images/vip/verx.png'/> </span>
            <span class='l_m_f'>Поднять вверх списка</span>

            <div class='position_right'>

                <span class='all_price'>&nbsp;&nbsp;&nbsp;<span class='price color_000005'>  10 </span>грн</span>
            </div>

        </li>
    {% endif %}
    </ul>
    <div class='label_sum'>
        Всего <span class='suma_price'>0</span> грн
    </div>
    </div>
    <div id='show'>
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
    </form>
    </div>
    <style>
        #adunit, #reklama, .title {
            display: none;
        }

    </style>


    <!--div id="ajax-loader">Загрузка...</div-->
{% endblock %}

