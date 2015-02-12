<div class="row-fluid">
    <div class="col-xs-12">
        <ul class="breadcrumb" id="main-breadcrumb">
            <li><a href="{{ url('#/') }}" ><i class="fa fa-home"></i></a></li>
            <li><a href="{{ url(href) }}" >{{ title }}</a></li>
            <li class="active">{{ smallTitle }}</li>
        </ul>

        <alert ng-init="alert = null" class="bounce-in" ng-show="alert" type="{[{alert.type}]}" close="alert = null">
            <i class="{[{alert.icon || 'fa fa-exclamation-circle'}]}"></i>&nbsp;{[{alert.msg}]}
        </alert>
    </div>
</div>
