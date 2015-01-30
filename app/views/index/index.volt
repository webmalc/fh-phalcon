{% extends "layouts/page.volt" %}

{% block content %}
    <div id="loading-wrapper" ng-show="layout.loading">
        <div id="loading-message" class="text-center">
            <h3><i class="fa fa-spin fa-spinner fa-lg"></i> Loading...</h3>
        </div>
    </div>
    <div id="content" ng-view></div>
{% endblock %}