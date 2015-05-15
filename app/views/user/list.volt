<table st-table="displayedUserList" st-safe-src="userList" class="table table-striped table-hover">
    <thead>
    <tr>
        <th class="tr-xxs text-center" st-sort="id">#</th>
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
            <div editable-select="role"
                 ng-init="roles = [{% for role, info in security %}{text: '{{ role }}', value: '{{ role }}'},{% endfor %}]"
                 e-ng-options="r.value as r.text for r in roles" e-name="role" e-form="userTableForm">
                {[{role}]}
            </div>
        </td>
        <td class="hidden-sm hidden-xs">
            {[{ (row.createdAt|date:'medium')}]}
            <div ng-show="row.updatedAt">{[{ (row.updatedAt|date:'medium')}]}</div>
        </td>
        <td class="text-center">
                                <span editable-select="row.active"
                                      ng-init="statuses = [{text: 'yes', value: true}, {text: 'no', value: false}]"
                                      e-ng-options="s.value as s.text for s in statuses" e-name="active"
                                      e-form="userTableForm">
                                    <i class="fa fa-check" ng-show="row.active"></i>
                                    <i class="fa fa-ban" ng-show="!row.active"></i>
                                </span>
        </td>
        <td class="text-center">
            <form editable-form name="userTableForm" onbeforesave="processUserTableForm($data, row.id)"
                  ng-show="userTableForm.$visible" class="form-buttons form-inline bounce-in-right"
                  shown="inserted == row">
                <button type="submit" ng-disabled="userTableForm.$waiting" class="btn btn-success">
                    <i class="fa fa-check"></i>
                </button>
                <button type="button" ng-disabled="userTableForm.$waiting" ng-click="userTableForm.$cancel()"
                        class="btn btn-default">
                    <i class="fa fa-times"></i>
                </button>
            </form>
            <div class="buttons bounce-in-right" ng-show="!userTableForm.$visible">
                <button class="btn btn-primary" ng-click="userTableForm.$show()"><i class="fa fa-edit"></i></button>
                <button class="btn btn-danger" ng-click="confirm(delete, row.id)"><i class="fa fa-trash"></i></button>
            </div>
        </td>
    </tr>
    </tbody>
</table>