<ul class="a-menu a-menu-ver">
    <li>
        <a href="/cabinet/profile">
            <b><i class="a-icon-user"></i></b>
            Мой профиль
        </a>
    </li>
    {% for m in menu %}
        <li>
            <a href="{{ m.link }}">
                {% if m.link == '/cabinet/messages' %}
                    <b><span class="a-count {% if m.count > 0 %}a-count-red{% endif %}">{{ m.count }}</span> </b>
                {% else %}
                    <b><span class="a-count-green">{{ m.count }}</span> </b>
                {% endif %}

                {{ m.name }}
            </a>
        </li>
    {% endfor %}
    <li>
        <a href="/cabinet/faq">
            <b><i class="a-icon-question-sign"></i></b>
            Помощь
        </a>
    </li>
</ul>