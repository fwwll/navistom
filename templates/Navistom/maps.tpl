{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block content %}

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item all-categories-list">
    {% endif %}
    <!--h1 class="n-form-title">
        <span>Список всех рубрик и разделов {{ label }}</span>
    </h1-->
    {% set icons = {'16':'article-menu-iconz','2':'stock-menu-iconz','3':'products-menu-iconz','4':'ads-menu-iconz','5':'activity-menu-iconz','6':'work-menu-iconz','7':'labs-menu-iconz','15':'vacancy-menu-iconz','8':'realty-menu-iconz','9':'service-menu-iconz','11':'demand-menu-iconz'} %}
    <div class="a-row a-offset-2">

        <ul class="categs-list">

            {% for sec in section %}
                <li>
                    <i class="{{ icons[sec.section_id] }}"></i>
                    <a href='{{ sec.link }}' class='map1'>{{ sec.name }}  - {{ contents_count[sec.section_id] }}</a>
                    {% if modelAll[ sec.section_id] %}
                        <ul class=" sub-categs-list map2">
                            {% for  cat in modelAll[ sec.section_id] %}

                                <li>
                                    <a href='{{ sec.link }}/categ-{{ cat.categ_id }}-{{ cat.name|translit }}'>{{ cat.name }} {% if sec.section_id ==4 %}бу {% endif %}</a>
                                    {% if modelAll[ sec.section_id].sub_categs %}
                                        <ul class=" list sub-categs-list map3">

                                            {% for  sub in modelAll[ sec.section_id].sub_categs[cat.categ_id] %}
                                                <li>
                                                    {% if sub.count == 0 %}
                                                        <span style='color:#999'> {{ sub.name }}  {% if sec.section_id ==4 %}бу {% endif %} {{ sub.count }}</span>
                                                    {% else %}
                                                        <a href='{{ sec.link }}/sub_categ-{{ sub.categ_id }}-{{ sub.name|translit }}'> {{ sub.name }}  {% if sec.section_id ==4 %}бу {% endif %}  {{ sub.count }}</a>
                                                    {% endif %}
                                                </li>
                                            {% endfor %}

                                        </ul>
                                    {% endif %}

                                </li>

                            {% endfor %}
                        </ul>
                    {% endif %}

                </li>
            {% endfor %}
        </ul>


    </div>
    </div>

{% endblock %}