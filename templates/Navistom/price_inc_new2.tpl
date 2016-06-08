{% set kk = {1:'1konc',2:'2konc',3:'3konc' } %}
<div class="a-row">
    <div class="all_form_success">
        <div id="success_form" data-price="{{ price_json }}">
            <div>
                <ul class="menu_folding" style="font: 12px/1.2 Arial,Helvetica ,sans-serif !important">
                    <li>
                        <div class="position_left">
                            <input type="checkbox" class="vip-checkbox" id="top_i" name="vip" value="3"
                                   nouniform="true"  {% if chekbox.top %} checked='checked' {% endif %} />
                            <label for="top_i">
                                <div></div>
                            </label>
                        </div>
                        <span class="icon_img b_727272"><img pagespeed_url_hash="1459379834"
                                                             src="http://navistom.com/templates/Navistom/images/vip/xtop.png.pagespeed.ic.wquak0JQiP.png"> </span>
                        <span class="l_m_f">ТОП на Главной + публикация в Журнале и Соцсетях</span>

                        <div class="position_right">
                            <label class="custom-select">
                                <select id="top_s">
                                    <option value="3" {% if chekbox.top=='3' %} selected="selected" {% endif %}>10 дней
                                        - {{ price[3] }} грн
                                    </option>
                                    <option value="2" {% if chekbox.top=='2' %} selected="selected" {% endif %}>20 дней
                                        - {{ price[2] }} грн
                                    </option>
                                    <option value="1"{% if chekbox.top=='1' %} selected="selected" {% endif %}>30 дней
                                        - {{ price[1] }} грн
                                    </option>

                                </select>
                            </label>
                            <span class="all_price">&nbsp;&nbsp;&nbsp;<span
                                        class="price color_000005"> {% if chekbox.top=='1' %}{{ price[1] }}{% elseif chekbox.top=='2' %}{{ price[2] }}{% elseif chekbox.top=='3' %}{{ price[3] }}  {% else %}  {{ price[3] }}  {% endif %} </span>грн</span>
                        </div>
                    </li>
                    <li>
                        <div class="position_left">
                            <input type="checkbox" class="vip-checkbox" id="konc_i" name="show_competitor"
                                   {% if chekbox.kon %}value="{{ kk[chekbox.kon] }}" {% else %} value="3konc" {% endif %}
                                   nouniform="true"  {% if chekbox.kon %} checked='checked' {% endif %} />
                            <label for="konc_i">
                                <div></div>
                            </label>
                        </div>
                        <span class="icon_img "><img pagespeed_url_hash="4229903117"
                                                     src="http://navistom.com/templates/Navistom/images/vip/xkonkurenti.png.pagespeed.ic._cGSSlMQIg.png"> </span>
                        <span class="l_m_f">Отобразить в объявлениях конкурентов</span>

                        <div class="position_right">
                            <label class="custom-select">
                                <select id="konc_s">
                                    <!--option value="1konc">30 дней - 224 грн </option>
                                    <option value="2konc">20 дней - 168 грн</option>
                                    <option value="3konc" selected="selected">10 дней - 112 грн</option-->

                                    <option value="3konc" {% if chekbox.kon=='3' %} selected="selected" {% endif %}>10
                                        дней - {{ price['3konc'] }} грн
                                    </option>
                                    <option value="2konc" {% if chekbox.kon=='2' %} selected="selected" {% endif %}>20
                                        дней - {{ price['2konc'] }} грн
                                    </option>
                                    <option value="1konc"{% if chekbox.kon=='1' %} selected="selected" {% endif %}>30
                                        дней - {{ price['1konc'] }} грн
                                    </option>

                                </select>
                            </label>
                            <span class="all_price">&nbsp;&nbsp;&nbsp;<span
                                        class="price color_000005"> {% if chekbox.kon=='1' %}{{ price['1konc'] }}{% elseif chekbox.kon=='2' %}{{ price['2konc'] }}{% elseif chekbox.kon=='3' %}{{ price['3konc'] }}  {% else %}  {{ price['3konc'] }}  {% endif %}  </span>грн</span>
                        </div>
                    </li>
                    <li>
                        <div class="position_left">
                            <input type="checkbox" class="vip-checkbox" name="color_yellow" id="yellow_i"
                                   value="{{ price['color_yellow'] }}"
                                   nouniform="true" {% if chekbox.color_yellow %} checked='checked' {% endif %} />
                            <label for="yellow_i">
                                <div></div>
                            </label>
                        </div>
                        <span class="icon_img "><img pagespeed_url_hash="3364648297"
                                                     src="http://navistom.com/templates/Navistom/images/vip/xyellow.png.pagespeed.ic.m_Hqy5Alo2.png"> </span>
                        <span class="l_m_f">Золотой фон на все время размещения + Разместить вверху списка</span>

                        <div class="position_right">
                            <span class="all_price">&nbsp;&nbsp;&nbsp;<span
                                        class="price color_000005"> {{ price['color_yellow'] }} </span>грн</span>
                        </div>
                    </li>
                    <li>
                        <div class="position_left">
                            <input type="checkbox" class="vip-checkbox" name="urgently" id="urgently"
                                   value="{{ price['urgently'] }}"
                                   nouniform="true" {% if chekbox.urgently %} checked='checked' {% endif %} />
                            <label for="urgently">
                                <div class="c_ck"></div>
                            </label>
                        </div>
                        <span class="icon_img"><span class="srochno">Cрочно!</span> </span>
                        <span class="l_m_f">Метка на все время размещения + Разместить вверху списка</span>

                        <div class="position_right">
                            <span class="all_price">&nbsp;&nbsp;&nbsp;<span
                                        class="price color_000005"> {{ price['urgently'] }} </span>грн</span>
                        </div>
                    </li>
                    <li>
                        <div class="position_left">
                            <input type="checkbox" class="vip-checkbox" id="update_date" name="update_date"
                                   value="{{ price['update_date'] }}"
                                   nouniform="true" {% if chekbox.update_date %} checked='checked' {% endif %} />
                            <label for="update_date">
                                <div class="c_ck"></div>
                            </label>
                        </div>
                        <span class="icon_img b_7961c7"><img pagespeed_url_hash="1679660146"
                                                             src="http://navistom.com/templates/Navistom/images/vip/xverx.png.pagespeed.ic.P_qzgkj1Eu.png"> </span>
                        <span class="l_m_f">Разместить вверху списка + Обновить дату</span>

                        <div class="position_right">
                            <span class="all_price">&nbsp;&nbsp;&nbsp;<span
                                        class="price color_000005">{{ price['update_date'] }} </span>грн</span>
                        </div>
                    </li>
                    <li>
                        <div class="position_left">
                            <input type="checkbox" class="vip-checkbox" id="jurnal_i" name="jurnal"
                                   value="{{ price['jurnal'] }}"
                                   nouniform="true" {% if chekbox.jurnal %} checked='checked' {% endif %} />
                            <label for="jurnal_i">
                                <div></div>
                            </label>
                        </div>
                        <span class="icon_img "><img pagespeed_url_hash="3119934796"
                                                     src="http://navistom.com/templates/Navistom/images/vip/xmagazine-new-icon.png.pagespeed.ic.-6xbhttAaQ.png"> </span>
<span class='l_m_f'><a target='_blank' href='/journals' class='' style='display:inline-block'> Опубликовать объявление в
        нашем Журнале </a>
			  </span>

                        <div class='position_right clear'>
                            <!--label class="custom-select">
                            <select id="jurnal_s">
                            <option value="jurnal1" selected="selected">Объявление с фото - 98 грн</option>
                            <option value="jurnal2">1/8 страницы А5 - 800 грн</option>
                            <option value="jurnal3">1/4 страницы А5 - 1350 грн</option>
                            <option value="jurnal4">1/2 страницы А5 - 2250 грн</option>
                            <option value="jurnal5">Страница А5 - 3500 грн</option>
                            </select>
                            </label-->
                            <span class="all_price">&nbsp;&nbsp;&nbsp;<span
                                        class="price color_000005"> {{ price['jurnal'] }} </span>грн</span>
                        </div>
                    </li>
                </ul>
                <div class="label_sum">
                    Всего <span class="suma_price">252</span> грн
                </div>
            </div>
            <div {% if user_info.info.group_id != 10 %} id="show" {% endif %}  style="display: block;">
                <div id="all_payments">
                    <div id="port" class="payments active">
                        <div>
                            <img pagespeed_url_hash="1618891110"
                                 src="http://navistom.com/templates/Navistom/images/vip/xport.png.pagespeed.ic.E460XaTDjA.png">
                        </div>
                        <div class="i_put">
                            <input type="radio" nouniform="true" id="p1" class="plat-radio" name="plat" value="port"
                                   checked="">
                            <label for="p1">
                            </label>
                            <span>Банковская карта</span>
                        </div>
                    </div>
                    <div id="liq" class="payments">
                        <div>
                            <img pagespeed_url_hash="3389713032"
                                 src="http://navistom.com/templates/Navistom/images/vip/xpriv.png.pagespeed.ic.vUNEmva4Uf.png">
                        </div>
                        <div class="i_put">
                            <input type="radio" nouniform="true" id="p2" class="plat-radio" name="plat" value="liq">
                            <label for="p2">
                            </label>
<span>
Приват24
</span>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div class="">
                    <div class="a-row">
                        <!--a href='/' class=" input-submit-green" target='_black'> ОПЛАТИТЬ И ОПУБЛИКОВАТЬ</a-->
                        <div style="margin:0 auto; text-align: center;">
                            <input type="hidden" name="resource_id" value="239">
                            <input type="hidden" name="section_id" value="7">

                            <div style='margin:0 auto; text-align: center;'>
                                <button type="submit" class="input-submit input-submit-green ttt" name='vip' value='1'>
                                    Оплатить &nbsp; <span class='slesh'>|</span> &nbsp; Добавить
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class='liqpay_lab'>
            </div>

            <div class='im_b'>
                <img src='/templates/Navistom/images/anketa/iconAll.png'/>

                <div class='all_center'>
                    <b style='color#888;  margin-bottom:10px;font-size:20px;'> Что дает ТОП-размещение:</b>
                    <ul>
                        <!--li><span class='p_ka'> </span>Больше  просмотров</li>
                        <li><span class='p_ka'> </span>Больше откликов</li>
                        <li><span class='p_ka'></span>Объявление заметнее</li--->
                        <li><span class='p_ka'></span>Объявление заметнее, больше просмотров, больше откликов</li>
                        <li><span class='p_ka'></span>Отправим на emeil всем нашим подписчикам</li>
                        <li><span class='p_ka'></span>Опубликуем в Facebook, VK, Twitter, Google+</li>
                        <li><span class='p_ka'></span>После периода ТОП-размещения объявление останется на сайте.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class='clear'></div>
    <b class="pn-pt" style='font-size:17px; position:relative; top:10px;text-align:center;'>По вопросам рекламы
        обращайтесь: +38-044-573-97-73, 067-460-86-78 пн-пт с 10-00 до 17-00</b>
</div>