{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>Статистика подписки на рассылку</b>
        <span></span>
    </h1>
    
    <div class="a-row a-offset-2">
        <div class="a-cols-2">
            <div class="stat-block stat-users">
                <b>{{all_count}}</b>
                <span>Всего подписчиков</span>
            </div>
        </div>
        <div class="a-cols-2">
            <div class="stat-block stat-users">
                <b>{{active_count}}</b>
                <span>Активных подписчиков</span>
            </div>
        </div>
    </div>
    <p><br /></p>
    <h3 class="stat-title">Статистика подписчиков по разделам</h3>
    
    <div id="subscribe-sections" style="height:400px; margin-bottom:30px"></div>
    
    <script>
    $(function () {
        $('#subscribe-sections').highcharts({
            chart: {
                type: 'column'
            },
			credits: {
				enabled: false
			},
            title: {
                text: ''
            },
            xAxis: {
                categories: [
					{% for c in count_by_sections %}
					'{{c.name}}',
					{% endfor %}
				]
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'none',
                        color: '#333333'
                    }
                }
            },
            legend: {
				enabled: false
            },
            tooltip: {
                enabled: false
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
					pointPadding: 0,
                    dataLabels: {
                        enabled: false
                    }
                }
            },
            series: [{
                data: [
					{% for c in count_by_sections %}
					{{c.users_count}},
					{% endfor %}
				],
				color: 'rgba(235,185,77,0.6)',
				borderColor: "#ebb94d",
				borderWidth: 1
            }]
        });
	});
		</script>
{% endblock %}

{% block right %}
	
{% endblock %}