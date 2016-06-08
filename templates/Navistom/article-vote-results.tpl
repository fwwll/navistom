<div style="display:none" class="n-interview-result a-clear">
    {% for v in versions %}
        <div class="n-interview-desc">{{ v.name }}</div>
        <div class="n-inerview-bg">
            <div style="width:{{ '%.0f'|format( (v.count * 100) / sum ) }}%" class="n-inerview-res"></div>
            <span>{{ v.count }}</span>
        </div>
        <div class="n-interview-right">{{ '%.0f'|format( (v.count * 100) / sum ) }}%</div>
    {% endfor %}
</div>