{% if user_info %}
    <div>
        <h1 class="n-form-title">
            <span>Вы можете добавить заявку в спрос</span>
        </h1>

        <form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/demand/add_ajax">
            <div class="a-row">
                <label><font class="a-red">*</font> Заголовок</label>
                <input maxlength="70" placeholder="Что Вы ищете?" class="validate[required]" type="text" name="name"
                       id="name"/>
            </div>

            <div class="a-row">
                <label>Описание Вашей заявки</label>
                <textarea class="autosize" maxlength="3000" name="content"></textarea>
            </div>
            <div class="a-row">
                <label>Фотографиия</label>

                <ul class="uploader" id="uploader">
                    <li></li>
                </ul>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Контактные телефоны</label>
                <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                       class="phones-input"/>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Добавить"/>
            </div>
        </form>
    </div>
{% endif %}