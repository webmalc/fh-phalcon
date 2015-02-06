{% include "partials/header" with ['href': '#/user/profile', 'title': 'User', 'smallTitle': 'profile'] %}

<div class="row-fluid">

    <!-- User avatar -->
    <div class="col-sm-2 col-xs-2 hidden-xs">
        <a target="_blank" href="http://gravatar.com/emails/">
            <img src="{{ helper.gravatar(auth.getUser().email, 300) }}" tooltip-placement="bottom" tooltip="Change your avatar at gravatar.com" alt="{{ auth.getUser().email }} gravatar" class="img-rounded img-responsive">
        </a>
    </div>

    <!-- User info -->
    <div class="col-sm-10 col-xs-12">
        <table class="table table-striped table-hover">
            <tbody>
                <tr class="visible-xs">
                    <td class="tr-sm vertical-middle">Avatar:</td>
                    <td>
                        <a href="http://gravatar.com/emails/" target="_blank" href="http://gravatar.com/emails/">
                            Change your avatar at gravatar.com
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="tr-sm vertical-middle">Name:</td>
                    <td>
                        <a href="#" editable-text="user.name" onbeforesave="updateUserProperty($data, 'name')">
                            {[{ user.name || 'empty' }]}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="tr-sm vertical-middle">Email:</td>
                    <td>
                        <a href="#" editable-email="user.email" e-required onbeforesave="updateUserProperty($data, 'email')">
                            {[{ user.email || 'empty' }]}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="tr-sm vertical-middle">Password:</td>
                    <td>
                        <a href="#" editable-text="user.password" e-type="password" e-pattern=".{6,}" e-title="6 characters minimum" e-required onbeforesave="updateUserProperty($data, 'password')">
                            {[{ user.password || 'new password' }]}
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>