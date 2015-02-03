{% include "partials/header" with ['href': '#/user/profile', 'title': 'User', 'smallTitle': 'profile'] %}

<div class="row">

    <!-- User avatar -->
    <div class="col-md-2 without-padding-left bottom-buffer">
        <a target="_blank" href="http://gravatar.com/emails/" id="user-gravatar-link">
            <img src="{{ helper.gravatar(auth.getUser().email, 170) }}" tooltip-placement="bottom" tooltip="Change your avatar at gravatar.com" alt="{{ auth.getUser().email }} gravatar" class="img-rounded">
        </a>
    </div>

    <!-- User info -->
    <div class="col-md-10">
        <table class="table table-striped table-hover">
            <tbody>
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