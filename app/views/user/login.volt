<!DOCTYPE html>
<html lang="ru" ng-app="fh">
<head>
    {% include 'partials/meta.volt' %}
</head>
<body>

<div id="main-container" class="container main-container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sign in to FamilyHelper </h3>
                </div>
                <div class="panel-body">
                    <!-- login form -->
                    <div ng-controller="LoginController">

                        <div class="alert alert-danger bounce-in" ng-cloak ng-show="error">
                            {[{error}]}
                        </div>
                        <form ng-init="form.password = false" ng-show="!form.password" name="loginForm"
                              class="form login-form flip-in" ng-submit="processLoginForm()" role="form" method="post"
                              accept-charset="UTF-8">
                            <div class="alert alert-success bounce-in" ng-cloak ng-show="success">
                                <button type="button" class="close" aria-hidden="true"
                                        ng-click="success = ''">&times;</button>
                                {[{success}]}
                            </div>
                            <div class="form-group">
                                <div class="input-group"
                                     ng-class="{ 'has-error' : loginForm.email.$invalid && loginForm.email.$dirty && !loginForm.email.$focused }">
                                    <span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
                                    <input autofocus type="email" name="email" ng-focus ng-model="login.email"
                                           class="form-control" placeholder="e-mail" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
                                    <input type="password" name="password" ng-focus ng-model="login.password"
                                           class="form-control" placeholder="password" required>
                                </div>
                            </div>
                            <div class="checkbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label><input type="checkbox" ng-init="login.remember = true"
                                                      name="_remember_me" ng-model="login.remember" value="on" checked>
                                            remember me?</label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href ng-click="form.password = true; error = ''">forgot password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="loginForm.$invalid" name="_submit"
                                        class="btn btn-success btn-block">
                                    <span ng-cloak ng-init="loading.login = false" ng-show="loading.login"><i
                                                class="fa fa-spinner fa-spin fa-lg"></i> </span>
                                    Login
                                </button>
                            </div>
                        </form>
                        <!-- reset password -->
                        <form ng-show="form.password" ng-cloak name="resetForm" class="form reset-form flip-in"
                              ng-submit="processResetForm()">
                            <div class="input-group"
                                 ng-class="{ 'has-error' : resetForm.email.$invalid && resetForm.email.$dirty && !resetForm.email.$focused }">
                                <span class="input-group-addon"><i class="fa fa-envelope fa-lg"></i></span>
                                <input type="email" name="email" ng-focus ng-model="remind.email" class="form-control"
                                       placeholder="e-mail" required value="">
                            </div>
                            <div class="checkbox">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <a href ng-click="form.password = false; error = ''">cancel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="resetForm.$invalid" name="_submit"
                                        class="btn btn-success btn-block">
                                    <span ng-cloak ng-init="loading.password = false" ng-show="loading.password"><i
                                                class="fa fa-spinner fa-spin fa-lg"></i> </span>
                                    Send password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{% include 'partials/scripts.volt' %}
</body>
</html>