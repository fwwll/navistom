{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}
{% block content %}


    <div id='liqpay'>
        <form method="POST" action="https://www.liqpay.com/api/checkout" accept-charset="utf-8">
            <input type="hidden" name="data" value="{{ data|trim }}"/>
            <input type='hidden' name='signature' value="{{ signature|trim }}"/>
            <input type="image" src="//static.liqpay.com/buttons/p1ru.radius.png" name="btn_text"/>
        </form>
    </div>

    <div class='portmone'>
        <form action="https://www.portmone.com.ua/gateway/" method="post">

            {% for name ,val in portmone %}
                <input type="hidden" name="{{ name }}" value="{{ val }}"/>
            {% endfor %}
            <input type="submit" value="Оплатить" class='input-submit-green'/>
        </form>
    </div>



    <!--div id="ajax-loader">Загрузка...</div-->
{% endblock %}