{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>Статистика портала</b>
        <span></span>
    </h1>
    
    <div class="section">
        <ul class="tabs a-clear">
            <li class="current">Общая статистика</li>
            <li class="">Статистика за период</li>
            <li class="">Статистика посетителей</li>
        </ul>
        <div class="box visible">
            <div class="a-row a-offset-2">
                <div class="a-cols-4">
                    <div class="stat-block stat-users">
                        <b>{{sessions_count}}</b>
                        <span>Посетителей за сегодня</span>
                    </div>
                </div>
                <div class="a-cols-4">
                    <div class="stat-block stat-content">
                        <b>{{content_count_by_day_sum}}</b>
                        <span>Материалов за сегодня</span>
                    </div>
                </div>
                <div class="a-cols-4">
                    <div class="stat-block stat-default">
                        <b>{{registration_count}}</b>
                        <span>Регистраций за сегодня</span>
                    </div>
                </div>
                <div class="a-cols-4">
                    <div class="stat-block stat-accounts">
                        <b>{{users_count}}</b>
                        <span>Всего аккаунтов</span>
                    </div>
                </div>
            </div>
            
            <p><br /></p>
            
            <h3 class="stat-title">График посещений сайта за последний месяц</h3>
            
            <div id="sections-views-by-month" style="height:400px;"></div>
            
            <h3 class="stat-title">Посетители разделов за весь период</h3>
            
            <div id="section-views" style="height:400px; margin-bottom:30px"></div>
            
        </div>
        <div class="box">
        	<h3 class="stat-title">Подробная статистика</h3>
            
        	<table class="a-table">
            	<thead>
                	<tr> 
                        <th>Показатель</th>
                        <th>Вчера</th> 
                        <th>За неделю</th>
                        <th>За месяц</th>
                    </tr> 
                </thead>
                <tbody>
                	<tr>
                    	<td><b>Добавлено материалов</b></td>
                        <td><b>{{statistic_by_date.contents.yesterday}}</b></td>
                        <td><b>{{statistic_by_date.contents.week}}</b></td>
                        <td><b>{{statistic_by_date.contents.month}}</b></td>
                    </tr>
                    <tr>
                    	<td><b>Новых регистраций</b></td>
                        <td><b>{{statistic_by_date.registrations.yesterday}}</b></td>
                        <td><b>{{statistic_by_date.registrations.week}}</b></td>
                        <td><b>{{statistic_by_date.registrations.month}}</b></td>
                    </tr>
                    <tr>
                    	<td><b>Посещений всего</b></td>
                        <td><b>{{statistic_by_date.sessions.yesterday|number_format(0, '', ' ')}}</b></td>
                        <td><b>{{statistic_by_date.sessions.week|number_format(0, '', ' ')}}</b></td>
                        <td><b>{{statistic_by_date.sessions.month|number_format(0, '', ' ')}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="box">
        	<h3 class="stat-title">Статистика браузеров</h3>
            <div class="a-clear"></div>
            <div id="user-browsers" style="height:400px;"></div>
            
            <h3 class="stat-title">Статистика операционных систем</h3>
            <div id="user-platforms" style="height:400px;"></div>
            
        </div>
    </div>
    
    <script>
    $(function () {
        $('#section-views').highcharts({
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
					{% for s in section_stat %}
					'{{s.name}}',
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
					{% for s in section_stat %}
					{{s.views_section}},
					{% endfor %}
				],
				color: 'rgba(235,185,77,0.6)',
				borderColor: "#ebb94d",
				borderWidth: 1
            }]
        });
		
		$('#sections-views-by-month').highcharts({
			title: {
                text: '',
            },
            xAxis: {
                categories: [
				{% for s in sections_views_by_month %}
				"{{s.date|rusDate}}",
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
					{% for s in sections_views_by_month %}
					{{s.views}},
					{% endfor %}
				],
				color: 'rgba(172,208,47,0.9)'
            }]
		});
		
		$('#user-browsers').highcharts({
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
					{% for b in browsers_statistic %}
					'{{b.browser_name}}',
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
					{% for b in browsers_statistic %}
					{{b.count}},
					{% endfor %}
				],
				color: 'rgba(235,185,77,0.6)',
				borderColor: "#ebb94d",
				borderWidth: 1
            }]
        });
		
		$('#user-platforms').highcharts({
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
					{% for p in platform_statistic %}
					'{{p.os_name}}',
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
					{% for p in platform_statistic %}
					{{p.count}},
					{% endfor %}
				],
				color: 'rgba(172,208,47,0.5)',
				borderColor: "#acd02f",
				borderWidth: 1
            }]
        });
    });
    </script>
    
    <!--<script>
		
		var data = [ 
			{% for s in section_stat %}
			["{{s.name}}", {{s.views_section}}],
			{% endfor %}
		];
		
		var sections_views_by_month = [ 
			{% for s in sections_views_by_month %}
			["{{s.date}}", {{s.views}}],
			{% endfor %}
		];
		
		var browsers = [ 
			{% for b in browsers_statistic %}
			["{{b.browser_name}}", {{b.count}}],
			{% endfor %}
		];
		
		var platforms = [ 
			{% for p in platform_statistic %}
			["{{p.os_name}}", {{p.count}}],
			{% endfor %}
		];

		$.plot("#section-views", [data], {
			series: {
				bars: {
					show: true,
					barWidth: 0.8,
					align: 'center',
					lineWidth: 1
				}
			},
			bars : {
				show : true,
				showNumbers: true,
				numbers : {
					yAlign: function(y) { return y + 25; }
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
			grid: {
				hoverable: true,
				clickable: false,
				borderWidth: 0
			}
		});
		
		$.plot("#user-browsers", [browsers], {
			series: {
				bars: {
					show: true,
					barWidth: 0.8,
					align: 'center',
					lineWidth: 1
				}
			},
			bars : {
				show : true,
				showNumbers: true,
				numbers : {
					yAlign: function(y) { return y + 25; }
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
			grid: {
				hoverable: true,
				clickable: false,
				borderWidth: 0
			}
		});
		
		$.plot("#user-platforms", [platforms], {
			series: {
				bars: {
					show: true,
					barWidth: 0.8,
					align: 'center',
					lineWidth: 1
				},
				color: "#acd02f"
			},
			bars : {
				show : true,
				showNumbers: true,
				numbers : {
					yAlign: function(y) { return y + 25; }
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
			grid: {
				hoverable: true,
				clickable: false,
				borderWidth: 0
			}
		});
		
		$.plot("#sections-views-by-month", [sections_views_by_month], {
			series: {
				lines: { 
					show: true
				},
				points: { show: true },
				color: "#acd02f"
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
			grid: {
				hoverable: true,
				clickable: false,
				borderWidth: 0,
			}
		});
		
	</script>-->
    
{% endblock %}

{% block right %}
	
{% endblock %}