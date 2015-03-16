{% include "partials/header" with ['href': '#/user', 'title': 'Administration', 'smallTitle': 'user'] %}

<div class="row-fluid">
    <div class="col-xs-12">
        <tabset>
            <tab>
                <tab-heading><i class="fa fa-bars"></i> List</tab-heading>
                
                <table st-table="displayedUserList" st-safe-src="userList" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="tr-xxs text-center"  st-sort="id">#</th>
                            <th st-sort="name">Name</th>
                            <th st-sort="email">E-mail</th>
                            <th>Roles</th>
                            <th class="hidden-sm hidden-xs" st-sort="createdAt">Date</th>
                            <th class="tr-xs text-center" st-sort="active">Active</th>
                            <th class="tr-m"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="row in displayedUserList" ng-class="{danger: !row.active}">
                            <td class="text-center">{[{row.id}]}</td>
                            <td>
                                <span editable-text="row.name" e-name="name" e-form="userTableForm">
                                    {[{row.name}]}
                                </span>
                            </td>
                            <td>
                                <span editable-text="row.email" e-name="email" e-form="userTableForm">
                                    <a href="mailto: {[{row.email}]}">{[{row.email}]}</a>
                                </span>
                            </td>
                            <td ng-repeat="role in row.roles">
                                <div editable-select="role" ng-init="roles = [{% for role, info in security %}{text: '{{role}}', value: '{{role}}'},{% endfor %}]" e-ng-options="r.value as r.text for r in roles" e-name="role" e-form="userTableForm">
                                    {[{role}]}
                                </div>
                            </td>
                            <td class="hidden-sm hidden-xs">
                                {[{ (row.createdAt|date:'medium')}]}
                                <div ng-show="row.updatedAt">{[{ (row.updatedAt|date:'medium')}]}</div>
                            </td>
                            <td class="text-center">
                                <span editable-select="row.active" ng-init="statuses = [{text: 'yes', value: true}, {text: 'no', value: false}]" e-ng-options="s.value as s.text for s in statuses" e-name="active" e-form="userTableForm">
                                    <i class="fa fa-check" ng-show="row.active"></i>
                                    <i class="fa fa-ban" ng-show="!row.active"></i>
                                </span>
                            </td>
                            <td class="text-center">
                                <form editable-form name="userTableForm" onbeforesave="processUserTableForm($data, row.id)" ng-show="userTableForm.$visible" class="form-buttons form-inline bounce-in-right" shown="inserted == row">
                                    <button type="submit" ng-disabled="userTableForm.$waiting" class="btn btn-success">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button type="button" ng-disabled="userTableForm.$waiting" ng-click="userTableForm.$cancel()" class="btn btn-default">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                                <div class="buttons bounce-in-right" ng-show="!userTableForm.$visible">
                                    <button class="btn btn-primary" ng-click="userTableForm.$show()"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger" ng-click="delete(row.id)"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </tab>

            <!-- new -->
            <tab>
                <tab-heading><i class="fa fa-plus-circle"></i> New</tab-heading>
                <div class="well">
                        <form class="form-horizontal form-sm user-form" name="userForm" ng-submit="processUserForm(); userForm.$setPristine()" role="form" method="post" accept-charset="UTF-8">
                            <div class="form-group">
                                <label for="name" class="col-sm-1 control-label  hidden-xs">Name</label>
                                <div class="input-group col-sm-11">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" ng-model="newUser.name" placeholder="John Smith">
                                </div>
                            </div>
                            <div class="form-group" ng-class="{ 'has-error' : userForm.email.$invalid && userForm.email.$dirty}">
                                <label for="email" class="col-sm-1 control-label hidden-xs">Email <sup class="text-danger">*</sup></label>
                                <div class="input-group col-sm-11">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" required class="form-control" id="email" ng-model="newUser.email" name="email" placeholder="email@example.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roles" class="col-sm-1 control-label hidden-xs">Roles <sup class="text-danger">*</sup></label>
                                <div class="input-group col-sm-11" ng-class="{ 'has-error' : userForm.roles.$invalid && userForm.roles.$dirty}">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <select class="form-control" id="roles" name="roles" required ng-model="newUser.roles">
                                        <option></option>
                                        {% for role, info in security %}
                                        <option value="{{ role }}">{{ role }}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-11 col-sm-offset-1">
                                    <button type="submit" class="btn btn-success" ng-disabled="userForm.$invalid">
                                        <span ng-cloak ng-show="loading"><i class="fa fa-spinner fa-spin fa-lg"></i> </span>
                                        <i ng-show="!loading" class="fa fa-check"></i> Create user
                                    </button>
                                </div>
                            </div>
                        </form>
                </div>
            </tab>
        </tabset>
    </div>
</div>
