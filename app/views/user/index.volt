{% include "partials/header" with ['href': '#/user', 'title': 'Administration', 'smallTitle': 'user'] %}

<div class="row-fluid">
    <div class="col-xs-12">
        <tabset>
            <tab>
                <tab-heading><i class="fa fa-bars"></i> List</tab-heading>

                {% include "user/list" with ['security': security] %}
            </tab>

            <!-- new -->
            <tab>
                <tab-heading><i class="fa fa-plus-circle"></i> New</tab-heading>
                {% include "user/new" with ['security': security] %}
            </tab>
        </tabset>
    </div>
</div>
