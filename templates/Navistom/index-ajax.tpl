<div class="a-modal-bg"></div>
<div class="a-modal-parent">

    <div class="a-modal">
        {% if route.action != 'add' and route.action != 'resumeAdd' and route.action != 'vacancyAdd'  and route.action != 'resumeEdit' and route.action != 'vacancyEdit' and route.action != 'edit' and route.action != 'registration' and route.action != 'feedback' and route.action != 'sendUserError' %}
            <a class="a-modal-closer" href="#"></a>
        {% endif %}
        <div class="a-modal-table">
            <div class="a-modal-content">
                <a class="a-modal-close" href="#"><i class="a-icon-remove"></i></a>
                {% block content %}{% endblock %}
            </div>
        </div>
    </div>
</div>

<script>


    /*jQuery(document).ready(function($){
     if(jQuery('link[href*="all.min.css"]').length)
     {
     jQuery('link[href*="all.min.css"]').attr('href','/templates/complete/all_new2.min.css');
     }
     if(!jQuery('link[href*="all_new.min.css"]').length)
     {
     $('head').append('<link rel="stylesheet" href="/templates/complete/all_new2.min.css"/>');
     }
     jQuery('.css').remove();
     }) */

</script>