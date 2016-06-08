{#% extends "index-ajax.tpl" %#}
{% extends "index-static.tpl" %}

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

    <div style="width:700px">

        <div id="global-statistic" class="global-statistic">
            <h1 class="n-form-title">
                <span>Статистика портала NaviStom.com</span>
            </h1>

            <div class="a-row gs-all-stats">
                <div class="a-cols-4">
                    <b>{{ globalAccounts|number_format(0, '', ' ')|default(0) }}</b>
                    Аккаунтов пользователей
                </div>
                <div class="a-cols-4">
                    <b>{{ globalAds|number_format(0, '', ' ')|default(0) }}</b>
                    Актуальных объявлений
                </div>
                <div class="a-cols-4">
                    <b>{{ globalArticles|number_format(0, '', ' ')|default(0) }}</b>
                    Статей на портале
                </div>
                <div class="a-cols-4">
                    <b>{{ globalUsers|number_format(0, '', ' ')|default(0) }}</b>
                    Пользователей за месяц
                </div>
            </div>

            <h2>Статистика посещений по дням (данные из Google Analytics):</h2>

            <div id="users-by-week" style="height:400px; margin-bottom:30px"></div>

            <h2>Популярные статьи:</h2>

            <table style="margin-bottom:30px;">
                {% for article in topArticles %}
                    <tr>
                        <td><a target="_blank"
                               href="/article/{{ article.article_id }}-{{ article.name|translit }}">{{ article.name|raw }}</a>
                        </td>
                    </tr>
                {% endfor %}
            </table>

            <h2>Популярные объявления:</h2>

            <table style="margin-bottom:30px;">
                {% for item in topMaterials7 %}
                    <tr>
                        <td><a target="_blank"
                               href="/{{ item.link }}/{{ item.id }}-{{ item.name|translit }}">{{ item.name|raw }} {% if item.link == 'ads' %}, Б/У{% endif %}</a>
                        </td>
                    </tr>
                {% endfor %}
            </table>

            <h2><a href="/maps" style='text-decoration:underline'>Все рубрики</a></h2>

            <h2><a href="/all" style='text-decoration:underline'>Все объявления</a></h2>
            <!--table style="margin-bottom:30px;">
            <tr>
                <td>
                 <a href="/all">все объявления</a>
                </td>
            <tr/>
            </table-->

            <script type="text/javascript">

                var initGraph = function () {
                    $('#users-by-week').highcharts({
                        title: {
                            text: '',
                        },
                        xAxis: {
                            categories: [
                                {% for u in usersByWeek %}
                                "{{u[0]|rusDate}}",
                                {% endfor %}
                            ]
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }
                        },
                        tooltip: {
                            enabled: false
                        },
                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: false
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                            name: '',
                            data: [
                                {% for u in usersByWeek %}
                                {{u[1]}},
                                {% endfor %}
                            ],
                            color: 'rgba(138,186,84,0.9)'
                        }]
                    });
                };

                if (!document.getElementById('highcharts-script')) {
                    var script = document.createElement('script');
                    script.src = "/assets/highcharts/highcharts.js";
                    script.type = "text/javascript";
                    script.id = "highcharts-script";

                    script.onload = function () {
                        try {
                            initGraph();
                        } catch (e) {
                            setTimeout(initGraph, 200);
                        }
                    };

                    document.body.appendChild(script);
                } else {
                    try {
                        initGraph();
                    } catch (e) {
                        setTimeout(initGraph, 200);
                    }
                }
            </script>

        </div>

    </div>
{% endblock %}