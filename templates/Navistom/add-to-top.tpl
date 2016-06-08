{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

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
    <h1 class="n-form-title">
        <span>{{ title }}</span>
        <!--div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div-->
    </h1>
    <div style="width:700px">
        {% if is_admin %}
            <form id="activity-add-form" class="n-add-form a-clear" method="post"
                  action="/index.ajax.php?route={{ action }}-{{ section_id }}-{{ resource_id }}"
                  data-time="{{ curr_time }}">
                <div class="a-row">
                    <label><font class="a-red"></font> Период действия ТОП</label>

                    <input value="{{ data.date_start }}" placeholder="Дата начала" type="text" name="date_start"
                           id="date_start" class="datepicker-start validate[required]]"/>
                    <i class="a-icon-calendar"></i>
                    <input value="{{ data.date_end }}" placeholder="Дата окончания" type="text" name="date_end"
                           id="date_end" class="datepicker-end validate[required]"/>
                    <i class="a-icon-calendar"></i>


                    <div class="a-row-l">
                        <input type='checkbox' class='admin_add_top' name='on-off' id='on-off' nouniform="true"
                               value='1'  {% if data.date_end > curr_time %}  checked="checked" {% endif %}/>
                        <label for='on-off'><span></span></label>
                    </div>
                </div>
                <div class='clear'></div>


                <input type="hidden" name="sort_id" id="sort_id"
                       value="{% if data.sort_id %}{{ data.sort_id }}{% else %}999{% endif %}"/>

                <div class="a-row">
                    <label>
                        Период отображения в конкурентах
                        <font class="a-red"></font> </label>

                    <input value="" placeholder="Дата начала отображения " type="text" name="start_competitor"
                           id="start" class="datepicker-start "/>

                    <input value="" placeholder="Дата окончания отображения" type="text" name="end_competitor" id="end"
                           class="datepicker-end"/>


                    <div class="a-row-l">
                        <input type='checkbox' name='show_competitor' class='admin_add_top' id='show_competitor'
                               nouniform="true" value='150'>
                        <label for='show_competitor'><span></span></label>
                    </div>

                </div>

                <div class='clear'></div>
                <div class="a-row">
                    <div class='item_supp'>


                        <div class='description yellow'>
                            Желтый фон объявления<br/>
                            на все время размещения
                        </div>
                        <input type='checkbox' name='color_yellow' class='admin_add_top' id='color_yellow'
                               nouniform="true" value='20'/>
                        <label for='color_yellow'><span></span></label>
                    </div>
                    <div class='item_supp'>


                        <div class='description'>
                            Метка <span class='srochno'>Cрочно!</span><br>
                            на все время размещения
                        </div>
                        <input type='checkbox' name='urgently' id='urgently' class='admin_add_top' nouniform="true"
                               value='20'/>
                        <label for='urgently'><span></span></label>
                    </div>
                    <div class='item_supp'>


                        <div class='description '>
                            Метка<br/>
                            Оплачено
                        </div>
                        <input type='checkbox' name='pay' class='admin_add_top' id='pay' nouniform="true" value='1'/>
                        <label for='pay'><span></span></label>
                    </div>


                    <!--div class='item_supp'>
                         <span> <span class='show_competitor'></span></span>
                         <input type='checkbox' name='show_competitor'  class='admin_add_top'id='show_competitor'  nouniform="true" value='150'>
                             <label for='show_competitor'><span></span></label>
                         <div class='description orange'>
                             <span class='show_top'>Отобразить ТОП-объявление <br/>
                             в объявлениях конкурентов</span>
                          </div>
                    </div-->


                    <script>
                        jQuery(document).ready(function ($) {
                            var t = $('#activity-add-form').data('time');
                            var str = location.pathname;
                            var url = '/color';
                            str = str.split('-');
                            str = str.slice(-2);

                            $.post(url, {'update': 0, 'section_id': str[0], 'resource_id': str[1]}, function (d) {
                                d = JSON.parse(d);

                                if (d.success) {
                                    for (var k in d) {
                                        if (d[k] > 0) {
                                            $('#activity-add-form input[name="' + k + '"]').prop("checked", true).parent().addClass('checked');
                                        }
                                    }

                                    if ($('#show_competitor').is(":checked") && (d['end_competitor']) < t) {
                                        $('#show_competitor').removeAttr('checked');
                                    }

                                    if (d['end_competitor']) {
                                        $("input[name='end_competitor']").val(d['end_competitor']);
                                    }

                                    if (d['start_competitor']) {
                                        $("input[name='start_competitor']").val(d['start_competitor']);
                                    }

                                }

                                $('#activity-add-form input[type="checkbox"]').not('#on-off').click(function (e) {
                                    var obj = $('#activity-add-form').serialize();
                                    obj += '&resource_id=' + (str[1]) + '&section_id=' + (str[0]) + '&update=1';
                                    $.post(url, obj, function (d) {
                                    })

                                });


                            });

                            $('#input[type="text"]').click(function (e) {
                                e.preventDefault();
                            })

                            $('#on-off').click(function (e) {

                                if ($('#date_start').val() == '' || $('#date_end').val() == '') {
                                    e.preventDefault();
                                    return 0;
                                }

                                if ($(this).is(":checked")) {
                                    var send = '/add-to-top-main-' + (str[0]) + '-' + (str[1]);
                                    var ob = $('#activity-add-form').serialize();
                                    $.post(send, ob, function (d) {
                                    });
                                } else {
                                    var send = '/add-to-top-main-delete-' + (str[0]) + '-' + (str[1]);
                                    $.get(send, {}, function (d) {
                                    });
                                }

                            })
                        })
                    </script>

                </div>
                <div class="a-row">
                    <label>&nbsp;</label>
                    <!--input class="a-btn-green" type="submit" value="Сохранить"  /-->

                    {#% if data.date_start %#}
                    <!--div class="a-float-right">
        	<a id="remove-link" class="a-btn" href="{{ action }}-delete-{{ section_id }}-{{ resource_id }}">Отменить топ</a>
        </div-->
                    {#% endif %#}
                </div>
            </form>
        {% else %}
            <div style="width:700px" class="a-mess-yellow">
                У Вас нет доступа к этой опции.
            </div>
        {% endif %}
    </div>
{% endblock %}