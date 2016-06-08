{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

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

        <h1 class="n-form-title">
            <span>Отправить резюме работодателю</span>

            <div class="n-title-desc">&nbsp;</div>
        </h1>

        <form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/work/vacancy/send-my-resume-{{ vacancy.vacancy_id }}">
            <div class="a-row">
                <label>Вакансия</label>

                <div style="float:left">
                    <h1>Требуется {{ vacancy.categs|join(', ')|lower }}, г. {{ vacancy.city_name }}</h1>
                    <span class="resume-user-descr">{{ vacancy.company_name|raw }}</span>
                </div>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font>Отправить резюме:</label>

                <select name="work_id" id="work_id">
                    {% for r in resume %}
                        <option value="{{ r.work_id }}">{{ r.name }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label>Сопроводительный текст</label>
                <textarea name="message" id="message"></textarea>
                <input type="hidden" name="vacancy_id" value="{{ vacancy.vacancy_id }}"/>
                <input type="hidden" name="user_id" value="{{ vacancy.user_id }}"/>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Отправить"/>
            </div>
        </form>
    </div>
{% endblock %}