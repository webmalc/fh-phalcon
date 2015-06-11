<div class="well">
    <form class="form-horizontal form-sm transaction-form" name="transactionForm"
          ng-submit="processTransactionForm(); transactionForm.$setPristine()" role="form" method="post"
          accept-charset="UTF-8">

        <div class="form-group">
            <label for="name" class="col-sm-1 control-label  hidden-xs">Tags <sup class="text-danger">*</sup></label>

            <div class="input-group col-sm-11">
                <span class="input-group-addon" tooltip="Tags"><i class="fa fa-tags"></i></span>
                <tags-input ng-model="transaction.tags" min-tags="1" maxTags="3" placeholder="add...">
                    <auto-complete source="loadTags($query)" min-length="1" maxResultsToShow="30"></auto-complete>
                </tags-input>
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="col-sm-1 control-label  hidden-xs">Price <sup class="text-danger">*</sup></label>

            <div class="input-group col-sm-11"
                 ng-class="{ 'has-error' : transactionForm.price.$invalid && transactionForm.price.$dirty}">
                <span class="input-group-addon" tooltip="Price"><i class="fa fa-dollar"></i></span>
                <input type="number" required ngRequired ngMinlength="0" class="form-control" min="0" step="0.01"
                       id="price" name="price" ng-model="transaction.price"
                       placeholder="2300.50">
            </div>
        </div>

        <div class="form-group">
            <label for="incoming" class="col-sm-1 control-label  hidden-xs">Type <sup
                        class="text-danger">*</sup></label>

            <div class="input-group col-sm-11">
                <span class="input-group-addon" tooltip="Type"><i class="fa fa-question"></i></span>
                <select class="form-control" id="incoming" name="incoming" ng-model="transaction.incoming"
                        ng-options="entry.val as entry.title for entry in [{ title: 'expense', val: false }, { title: 'income', val: true }]">
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="createdAt" class="col-sm-1 control-label  hidden-xs">Date</label>

            <div class="input-group col-sm-11"
                 ng-class="{ 'has-error' : transactionForm.createdAt.$invalid && transactionForm.createdAt.$dirty}">
                <span class="input-group-addon" tooltip="Date"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="createdAt" name="createdAt" ng-model="transaction.createdAt"
                       datepicker-popup ng-click="createdAtOpened = true" init-date="{{ date('Y-m-d') }}"
                       is-open="createdAtOpened" placeholder="{{ date('Y-m-d') }}">
            </div>
        </div>

        {% if acl.hasRole('admin') %}
            <div class="form-group">
                <label for="incoming" class="col-sm-1 control-label  hidden-xs">User <sup
                            class="text-danger">*</sup></label>

                <div class="input-group col-sm-11">
                    <span class="input-group-addon" tooltip="User"><i class="fa fa-user"></i></span>
                    <select id="user" class="form-control" required name="user"
                            ng-options="user.username for user in users track by user.id"
                            ng-model="transaction.user">
                    </select>
                </div>
            </div>
        {% endif %}

        <div class="form-group">
            <div class="col-sm-11 col-sm-offset-1">
                <button type="submit" class="btn btn-success" ng-disabled="transactionForm.$invalid">
                    <span ng-cloak ng-show="loading"><i class="fa fa-spinner fa-spin fa-lg"></i> </span>
                    <i ng-show="!loading" class="fa fa-check"></i> Add record
                </button>
            </div>
        </div>

    </form>
</div>
