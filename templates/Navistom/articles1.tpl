{% extends "index.tpl" %}

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

    {% if user_info and turnSubscribe %}
        <div class="subscribe-options">
            {% if subscribe_status %}
                <a href="/subscribe/delete-16-{{ route.values.categ_id|default(0) }}-0">
                    <span class="s-bg-red subscribe-icon"></span> &nbsp;
                    Отключить подписку на новости раздела
                </a>
            {% else %}
                <a href="/subscribe/add-16-{{ route.values.categ_id|default(0) }}-0">
                    <span class="s-bg-green subscribe-icon"></span>
                    &nbsp;Включить подписку на новости раздела
                </a>
            {% endif %}
        </div>
    {% endif %}

    <div id="left">

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе СТАТЬИ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit">Искать</button>
        </form>

        <div id="pagination-container">
            {% if banner_listing.link %}
                <a class="listing-banner pagination-block" href="{{ banner_listing.link }}"
                   target="{{ banner_listing.target }}">
                    <img src="/uploads/banners/{{ banner_listing.image }}"/>
                </a>
            {% endif %}
            {% for a in articles %}
                <div class="item pagination-block {% if a.light_flag %}light{% endif %}">
                    <div class="a-row a-offset-0">
                        <div class="a-cols-2 a-font-small a-color-gray-2">
                            {% for key, value in a.categs %}
                                &nbsp;&nbsp;<a title="{{ value }}" href="/articles/categ-{{ key }}-{{ value|translit }}"
                                               class="a-color-gray">{{ value }}</a>
                            {% endfor %}
                        </div>
                        <div style="font-size:10px"
                             class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                            {{ a.date_public|timeago }}
                        </div>
                    </div>

                    <div class="article clear">
                        <h2>
                            <a title="{{ a.name }}" target="_blank"
                               href="/article/{{ a.article_id }}-{{ a.name|translit }}" class="modal-window-link"></a>
                            <a title="{{ a.name }}"
                               href="/article/{{ a.article_id }}-{{ a.name|translit }}">{{ a.name }}</a></h2>
                        <img title="{{ a.name }}" alt="{{ a.name }}"
                             src="/uploads/images/articles/100x150/{{ a.url_full }}">

                        <div class="article-descr">
                            {{ a.content_min|raw }}
                        </div>
                    </div>

                    <div class="a-row a-offset-0">
                        <div class="a-cols-4 a-font-small a-color-gray">
                            <i class="a-icon-eye-open a-icon-gray"></i> {{ a.views }}
                            <span class="comments-count"><i
                                        class="a-icon-comment a-icon-gray"></i> {{ a.comments }}</span>
                        </div>
                        <div class="a-cols-2 a-font-small a-color-gray a-align-right no-phones">
                            {% for key, value in a.tags %}
                                &nbsp;&nbsp;<a title="{{ value }}"
                                               href="/articles/tag-{{ key }}-{{ value|translit }}">{{ value }}</a>
                            {% endfor %}
                        </div>
                    </div>
                    {% if is_admin %}
                        <ul class="options clear">
                            <li>
                                <a class="ajax-link" href="/article/edit-{{ a.article_id }}"><i
                                            class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                            </li>
                            <li>
                                {% if a.flag == 1 %}
                                    <a href="/article/flag-{{ a.article_id }}-0"><i
                                                class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                                {% else %}
                                    <a href="/article/flag-{{ a.article_id }}-1"><i
                                                class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                                {% endif %}
                            </li>
                            <li>
                                {% if a.flag_moder > 0 %}
                                    <a href="/article/flag_moder-{{ a.article_id }}-0"><i
                                                class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                {% else %}
                                    <a href="/article/flag_moder-{{ a.article_id }}-1"><i
                                                class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                {% endif %}
                            </li>
                            <li>
                                <a class="delete-link" href="/article/delete-{{ a.article_id }}"><i
                                            class="a-icon-trash a-icon-gray"></i> Удалить</a>
                            </li>
                            <li class="satus">
                                {% if a.flag_moder == 0 or a.date_public == '0000-00-00 00:00:00' %}
                                    <span class="red">На модерации</span>
                                {% elseif a.flag == 0 %}
                                    <span class="gray">Скрыто</span>
                                {% else %}
                                    <span class="green">Опубликовано</span>
                                {% endif %}
                            </li>
                        </ul>
                    {% endif %}
                </div>
            {% endfor %}

            {% if banner_listing_2.link %}
                <a class="listing-banner pagination-block" href="{{ banner_listing_2.link }}"
                   target="{{ banner_listing_2.target }}">
                    <img src="/uploads/banners/{{ banner_listing_2.image }}"/>
                </a>
            {% endif %}

        </div>

        {% if pagination %}
            <ul class="a-pagination">
                <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                    {% if route.values.categ_id > 0 %}
                        <a href="/articles/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/articles/page-{{ pagination.first.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.date %}
                        <a href="/articles/page-{{ pagination.first.url }}/archive-{{ route.values.date }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/articles/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/articles/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                    {% endif %}
                </li>
                <li>
                    {% if pagination.prev_page > 1 %}
                        {% if route.values.categ_id > 0 %}
                            <a href="/articles/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                        {% elseif route.values.tag_id > 0 %}
                            <a href="/articles/page-{{ pagination.prev_page }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">«</a>
                        {% elseif route.values.date %}
                            <a href="/articles/page-{{ pagination.prev_page }}/archive-{{ route.values.date }}">«</a>
                        {% elseif route.values.search %}
                            <a href="/articles/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                        {% else %}
                            <a href="/articles/page-{{ pagination.prev_page }}">«</a>
                        {% endif %}
                    {% endif %}
                </li>
                {% for p in pagination.pages %}
                    <li {% if route.values.page == p.name or (route.values.page == 0 and p.name == 1) %} class="active" {% endif %}>
                        {% if route.values.categ_id > 0 %}
                            <a href="/articles/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% elseif route.values.tag_id > 0 %}
                            <a href="/articles/{{ p.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% elseif route.values.date %}
                            <a href="/articles/{{ p.url }}/archive-{{ route.values.date }}">{{ p.name }}</a>
                        {% elseif route.values.search %}
                            <a href="/articles/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                        {% else %}
                            <a href="/articles/{{ p.url }}">{{ p.name }}</a>
                        {% endif %}
                    </li>
                {% endfor %}
                <li class="next-posts">
                    {% if pagination.next_page > 0 %}
                        {% if route.values.categ_id > 0 %}
                            <a href="/articles/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                        {% elseif route.values.tag_id > 0 %}
                            <a href="/articles/page-{{ pagination.next_page }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">»</a>
                        {% elseif route.values.date %}
                            <a href="/articles/page-{{ pagination.next_page }}/archive-{{ route.values.date }}">»</a>
                        {% elseif route.values.search %}
                            <a href="/articles/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                        {% else %}
                            <a href="/articles/page-{{ pagination.next_page }}">»</a>
                        {% endif %}
                    {% endif %}
                </li>
                <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                    {% if route.values.categ_id > 0 %}
                        <a href="/articles/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/articles/page-{{ pagination.last.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.date %}
                        <a href="/articles/page-{{ pagination.last.url }}/archive-{{ route.values.date }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/articles/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/articles/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                    {% endif %}
                </li>
            </ul>

        {% endif %}

    </div>

    <div id="right" class="padding">
        <a title="Добавить статью" href="/article/add" class="add-btn ajax-link"><b><i
                        class="a-icon-plus a-icon-white"></i></b> Добавить статью</a>


        <ul class="idTabs a-clear">
            <li>
                <a href="#articles-categs">Рубрики</a>
            </li>
            <li>
                <a href="#articles-tags">Метки</a>
            </li>
            <li>
                <a href="#articles-archive">Архив</a>
            </li>
        </ul>

        <div class="tabs-parent">
            <div id="articles-categs">
                <ul class="list">
                    {% for c in categories %}
                        <li {% if c.categ_id == route.values.categ_id %} class="active" {% endif %}>
                            <a title="{{ c.name }}"
                               href="/articles/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}</a>
                        </li>
                    {% endfor %}
                </ul>
            </div>
            <div id="articles-tags">
                <ul class="tags clear">
                    {% for t in tags %}
                        <li>
                            <a title="{{ t.name }}"
                               href="/articles/tag-{{ t.tag_id }}-{{ t.name|translit }}">{{ t.name }}</a>
                        </li>
                    {% endfor %}
                </ul>
            </div>
            <div id="articles-archive">
                <ul class="list">
                    {% for a in archive %}
                        {% if a.year %}
                            <li>
                                <a title="{{ a.name }}"
                                   href="/articles/archive-{{ a.year }}-{{ a.month }}">{{ a.name }}</a>
                            </li>
                        {% endif %}
                    {% endfor %}
                </ul>
            </div>
        </div>

        <hr class="line">

        <div class="social-parent">
            <a title="Вконтакте" href="http://vk.com/id95980050" target="_blank" class="social-link vk"></a>
            <a title="Facebook" href="https://www.facebook.com/navistom" target="_blank"
               class="social-link facebook"></a>
            <a title="Twitter" href="https://twitter.com/navistom" class="social-link twitter" target="_blank"></a>
            <a title="Google+" href="https://plus.google.com/u/0/107274476270243980702/posts" target="_blank"
               class="social-link google"></a>
            <a title="Обратная связь" href="/feedback" class="social-link feedback ajax-link"></a>
        </div>

        <p>&nbsp;</p>

        {% if banner.link or banner.code %}
            <noindex>
                <div id="fixed-banner">
                    {% if banner.code %}
                        {{ banner.code|raw }}
                    {% else %}
                        <a href="{{ banner.link }}" target="{{ banner.target }}">
                            <img src="/uploads/banners/{{ banner.image }}"/>
                        </a>
                    {% endif %}
                </div>
            </noindex>
        {% endif %}
    </div>



{% endblock %}