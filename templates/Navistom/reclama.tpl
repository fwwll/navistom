{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}
{% block title %}
    {{ meta.meta_title }}
{% endblock %}

{% block content %}

    <div class="item" style="padding:0px 100px">
        <h1 class="article-title">{{ title }}</h1>

        <div id="article-content">
            <div class='text_rec'>
                {{ content|raw }}
            </div>
            <div class='img_rec'>
                <img src='/file/KP_NAVISTOM_10-2015.png' width='600px;'/>
            </div>
            <div class='href_file'>
                <span class='pdf'> </span> <a href="/file/{{ file }}">{{ file_title }}</a>
            </div>


        </div>
    </div>
{% endblock %}

{#% block right %#}

{#% endblock %#}