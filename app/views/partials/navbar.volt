<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <a href="{{ url('#/') }}" class="navbar-brand">
                <i class="fa fa-venus-mars"></i> FamilyHelper
            </a>
            <button ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed;" class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="navbar-collapse collapse" id="navbar-main" ng-class="!navCollapsed && 'in'">

            <ul class="nav navbar-nav">
                <li><a href="{{ url('#/finances') }}"><i class="fa fa-dollar"></i> Finances</a></li>
            </ul>

            {% if auth.getUser() %}
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="http://gravatar.com/emails/" id="user-gravatar-link" tooltip-placement="bottom" tooltip="Change your avatar at gravatar.com">
                        <img src="{{ helper.gravatar(auth.getUser().email) }}" alt="{{ auth.getUser().email }} gravatar" class="img-circle">
                    </a>
                </li>
                <li class="dropdown" dropdown>
                    <a href="#" class="dropdown-toggle"  dropdown-toggle>Hello, {{ auth.getUser().getUsername() }} <span class="caret"></span></a>
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