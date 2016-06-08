{% extends "index_new2.tpl" %}

{% block title %}
    {{ meta.meta_title }}
{% endblock %}

{% block content %}
    <noindex>
        {{ divs|raw }}
        <style>
            .bott_bred, #reklama {
                display: none !important;
            }

            .title {
                text-align: center;
            }
        </style>
    </noindex>
{% endblock %}