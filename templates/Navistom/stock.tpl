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

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}

    <h1 class="n-form-title">
        <span>Как разместить акцию</span>

    </h1>
    <p>&nbsp;</p>
    <div class="n-content">
        <p>
            ВНИМАНИЕ! Акция привязана к товару! Перед тем как объявить акцию, убедитесь в наличии ваших товаров среди
            предложений раздела ПРОДАМ НОВОЕ.
            Если товара нет, сперва <a href="/products#/product/add">добавьте</a> его в раздел, а после объявите акцию,
            используя кнопку ДОБАВИТЬ АКЦИЮ
        </p>

        <p>
            <img src="/templates/Navistom/images/add-stock-image.png"/>
        </p>

        <a style="background:#F39130; padding:7px 10px; color:#fff; border-radius:2px"
           href="/products/user-{{ user_info.info.user_id }}-{{ user_info.info.name|translit }}">Перейти к моим
            объявлениям</a>
    </div>
    </div>
    <br/>
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
    <div class='clear'></div>
    <b class="pn-pt" style='font-size:17px; position:relative; top:10px;text-align:center;'>По вопросам рекламы
        обращайтесь: +38-044-573-97-73, 067-460-86-78 пн-пт с 10-00 до 17-00</b>
    <br/><br/>
{% endblock %}