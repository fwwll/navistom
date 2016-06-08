<div class="a-row">
    <label><font class="a-red">*</font> Название компании</label>
    <input type="text" name="company_name" id="company_name" />
</div>
<div class="a-row">
    <label>Веб-сайт</label>
    <input type="text" name="company_site" id="company_site" />
</div>
<div class="a-row">
    <label>Логотип компании</label>
    <input type="file" name="image" id="image" />
</div>
<div class="a-row">
    <label><font class="a-red">*</font> Описание компании</label>
    <textarea class="autosize" maxlength="1000" name="company_description"></textarea>
</div>
<div class="a-row">
    <label><font class="a-red">*</font> ФИО контактного лица</label>
    <input type="text" value="{{user_name}}" name="user_name" id="user_name" />
</div>
<div class="a-row">
    <label>
        <font class="a-red">*</font>
        Телефон контактного лица
        <span class="a-form-descr">можете указать несколько телефонов через запятую</span>
    </label>
    <input type="text" value="{{contact_phones.0}}" name="contact_phones" id="contact_phones" />
</div>