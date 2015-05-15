{% include "partials/header" with ['href': '#/finances', 'title': 'Finances', 'smallTitle': 'list'] %}

<div class="row-fluid">
    <div class="col-xs-12">
        <tabset>
            <tab select="alert(1)">
                <tab-heading><i class="fa fa-shopping-cart"></i> Expenses</tab-heading>
                {% include "finances/expenses" with [] %}
            </tab>

            <tab>
                <tab-heading><i class="fa fa-credit-card"></i> Incomes</tab-heading>
                {% include "finances/incomes" with [] %}
            </tab>

            <tab>
                <tab-heading><i class="fa fa-plus-circle"></i> New</tab-heading>
                {% include "finances/new" with [] %}
            </tab>


        </tabset>
    </div>
</div>