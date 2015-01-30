<h3 id="main-header">
    <a href="{{ url(href) }}" >{{ title }}</a>
    <small>
        <i class="fa fa-angle-double-right"></i> {{ smallTitle }}
    </small>
</h3>

<alert ng-init="alert = null" class="bounce-in" ng-show="alert" type="{[{alert.type}]}" close="alert = null">
    <i class="{[{alert.icon || 'fa fa-exclamation-circle'}]}"></i>&nbsp;{[{alert.msg}]}
</alert>

