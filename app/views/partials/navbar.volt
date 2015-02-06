<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-header">
            <a href="{{ url('#/') }}" class="navbar-brand">
                <img id="navbar-logo" src="img/favicon_24x24.png"> FamilyHelper
            </a>
            <button ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed;" class="navbar-toggle btn btn-navbar" type="button">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="navbar-collapse collapse navbar-responsive-collapse" id="navbar-main" collapse="navCollapsed">

            <ul class="nav navbar-nav">
                <li><a href="{{ url('#/finances') }}"><i class="fa fa-dollar"></i> Finances</a></li>
                {% if acl.hasRole('admin') %}
                <li class="dropdown" dropdown>
                    <a href="#" class="dropdown-toggle"  dropdown-toggle ng-cloak><i class="fa fa-cogs"></i> Administration</a>
                    <ul class="dropdown-menu" aria-labelledby="administration">
                        <li><a href="{{ url('#/user') }}"><i class="fa fa-user"></i> User</a></li>
                    </ul>
                </li>
                {% endif %}
            </ul>

            {% if auth.getUser() %}
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden-xs">
                        <a  target="_blank" href="http://gravatar.com/emails/" tooltip-placement="bottom" id="user-gravatar-link" tooltip="Change your avatar at gravatar.com">
                            <img src="{{ helper.gravatar(auth.getUser().email) }}" alt="{{ auth.getUser().email }} gravatar" class="img-rounded">
                        </a>
                    </li>
                    <li class="dropdown" dropdown id="user-menu">
                        <a href="#" class="dropdown-toggle"  dropdown-toggle ng-cloak>Hello, {[{ user.name || user.email }]} <span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="user">
                            <li><a href="{{ url('#/user/profile') }}"><i class="fa fa-user"></i> Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Sign out</a></li>
                        </ul>
                    </li>
                </ul>
            {% endif %}
        </div>
    </div>
</div>