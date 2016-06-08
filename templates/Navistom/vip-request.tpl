{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

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

        <div class="vip-request" style="width:800px; margin:-50px">
    {% else %}
        <div class="vip-request no-color">
    {% endif %}
    <div class="vip-request-header">
        <b>Если Вы желаете выделить свои объявления, <br> воспользуйтесь платной услугой «VIP-размещение».</b> <br><br>
        В этом случае ваши объявления получат максимальную видимость<br> и будут оставаться вверху списка на срок 30
        дней.
    </div>
    <form class=" a-clear all_f " id="product-add-form" action='/update_top' method='post' target="_blank">


        <div class="a-row">
            <div style="text-align:center">
                <b class="vip_h1">
                    <p>Заявка на ТОР - рзмещение объявления </p>
                    <a class="vip-ad-link" target="_blank" href="{{ params.link }}"><b>{{ params.name }}</b></a>
                </b></br>

            </div>
            <div class="a-row">
                <div class="a-cols-3">
                    <div class="add-form-vip-box">
                        <div class="vip-item-icon">
                            <div class='padding-top_16px'>
                                <span class='l_vip'>30<span> ДНЕЙ</span></span>
                            </div>
                        </div>


                        <div class="vip-box-price">
                            <b><span>{{ curr_prace[1] }} </span>грн.</b>
                        </div>
                        <input type="radio" noUniform='true' id='v1' class='vip-radio' checked name="vip" value="1"/>
                        <label for="v1"><span></span></label>

                    </div>
                </div>

                <div class="a-cols-3">
                    <div class="add-form-vip-box">
                        <div class="vip-item-icon">
                            <div class='padding-top_16px'>
                                <span class='l_vip'>20<span> ДНЕЙ</span></span>
                            </div>
                        </div>

                        <div class="vip-box-price">
                            <b><span>{{ curr_prace[2] }}</span> грн.</b>
                        </div>

                        <input type="radio" noUniform='true' id='v2' class='vip-radio' name="vip" value="2"/>
                        <label for="v2"><span></span></label>

                    </div>
                </div>
                <div class="a-cols-3">
                    <div class="add-form-vip-box">
                        <div class="vip-item-icon">
                            <div class='padding-top_16px'>
                                <span class='l_vip'>10<span> ДНЕЙ</span></span>
                            </div>
                        </div>


                        <div class="vip-box-price">
                            <b><span>{{ curr_prace[3] }}</span> грн.</b>
                        </div>
                        <input type="radio" noUniform='true' id='v3' class='vip-radio' name="vip" value="3"/>
                        <label for="v3"><span></span></label>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class='supplement'>
        <div class='item_supp'>
            <span> +20</span> грн
            <input type='checkbox' name='color_yellow' value='20'>

            <div class='description yellow'>
                Желтый фон объявления<br/>
                на все время размещения
            </div>
        </div>
        <div class='item_supp'>
            <span> +20</span> грн
            <input type='checkbox' name='urgently' value='20'>

            <div class='description'>
                Метка <span class='srochno'>Cрочно!</span><br>
                на все время размещения
            </div>
        </div>
        <div class='item_supp'>
            <span> +150</span> грн
            <input type='checkbox' name='show_competitor' value='150'>

            <div class='description orange'>
				<span class='show_top'>Отобразить ТОП-объявление <br/>
				в объявлениях конкурентов</span>
            </div>
        </div>
        <div class='clear'></div>
        <div class='l_sum'> Итого:<span class='all_sum'>150</span>.грн</div>
    </div>
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




    <div class='vip_center'>
        <div class="a-row">
            <!--a class="input-submit input-submit-green"> ОПЛАТИТЬ И ОПУБЛИКОВАТЬ</a-->

            <div style='margin:0 auto; text-align: center;'>
                <!--a href='/' type="submit" class="input-submit input-submit-green" name='vip' value='1'> Разместить в ТОП</a-->
                <input type='hidden' value='{{ section_id }}' name='section_id'/>
                <input type='hidden' value='{{ resource_id }}' name='resource_id'/>
                <input type='hidden' value='{{ params.link }}' name='link'/>
                <input type='hidden' value='1' name='up'/>
                <input type='submit' class=" input-submit-green" value='Разместить в ТОП'/>
            </div>
        </div>

        <div class='liqpay'>

        </div>
    </div>

    <div style='margin :0 auto; text-align:center; font-size:16px;padding-bottom:20px;'>ТОП-объявление отображается
        вверху на Главной, в Разделе, Рубрике, Подрубрике.
    </div>
    <div class='vip_center'>
        <div class="count_vip">
            Сейчас на NaviStom <strong>{{ count|number_format(0, '', ' ') }}</strong> объявлений
        </div>
        <b class='b_vip'>Что дает ТОП-размещение:</b>
        <ul>
            <li><span class='p_ka'> </span>Больше просмотров</li>
            <li><span class='p_ka'> </span>Больше откликов</li>
            <li><span class='p_ka'></span>Объявления заметнее</li>
        </ul>

        <div class="a-row">

            <div class="">

            </div>

        </div>
    </div>





    </form>
    </div>
{% endblock %}