<div class="debug-load-times">
    <dl class="a-list a-horizontal">
        <dt>Connect:</dt>
        <dd>0</dd>
        <dt>Backend:</dt>
        <dd>0</dd>
        <dt>DOM:</dt>
        <dd>0</dd>
        <dt>SQL Queries:</dt>
        <dd>{{ logs|length }}</dd>
        <dt>SQL Time:</dt>
        <dd>{{ SQLTime }}s</dd>
    </dl>
</div>

<div class="debug">

    <ul class="debug-query-list">
        {% for log in logs %}

            <li>
                <span class="query-time">{{ log.time }}s</span>

                <pre><code class="sql">{{ log.query }}</code></pre>
            </li>

        {% endfor %}
    </ul>
</div>

<script type="text/javascript" src="/{{ tpl_dir }}/debug/assets/highlight/highlight.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/{{ tpl_dir }}/debug/assets/highlight/styles/tomorrow-night-bright.css"/>

<script type="text/javascript" src="/{{ tpl_dir }}/debug/assets/snippet/jquery.snippet.min.js"></script>
<link rel="stylesheet" type="text/css" href="/{{ tpl_dir }}/debug/assets/snippet/jquery.snippet.min.css"/>

<script type="text/javascript">
    try {
        window.addEventListener('load', function () {
            hljs.configure({
                tabReplace: '  '
            });

            hljs.initHighlighting();

            var timing = window.performance.timing,
                    time = {
                        connect: timing.connectEnd - timing.connectStart,
                        backend: timing.responseStart - timing.requestStart,
                        dom: timing.domComplete - timing.domLoading
                    };
            var elems = document.querySelectorAll('.debug-load-times dd');
            elems[0].innerText = time.connect / 1000 + 's';
            elems[1].innerText = time.backend / 1000 + 's';
            elems[2].innerText = time.dom / 1000 + 's';
        }, false);
    } catch (e) {
    }
</script>

