{% include "partials/header" with ['href': '#/user', 'title': 'Administration', 'smallTitle': 'user'] %}

<div class="row-fluid">
    <div class="col-xs-12">
        <tabset>
            <tab>
                <tab-heading><i class="fa fa-bars"></i> List</tab-heading>
                <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua,
                    retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.
                    Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry
                    richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american
                    apparel, butcher voluptate nisi qui.</p>
            </tab>

            <!-- new -->
            <tab>
                <tab-heading><i class="fa fa-plus-circle"></i> New</tab-heading>
                <div class="well">
                        <form class="form-horizontal form-sm">
                            <div class="form-group">
                                <label for="name" class="col-sm-1 control-label  hidden-xs">Name</label>
                                <div class="input-group col-sm-11">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="John Smith">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-1 control-label hidden-xs">Email</label>
                                <div class="input-group col-sm-11">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" required class="form-control" id="email" name="email" placeholder="email@example.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roles" class="col-sm-1 control-label hidden-xs">Roles</label>
                                <div class="input-group col-sm-11">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <select class="form-control" id="roles" name="roles" required>
                                        <option></option>
                                        {% for role, info in security %}
                                        <option value="{{ role }}">{{ role|capitalize }}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-11 col-sm-offset-1">
                                    <button type="submit" class="btn btn-success">Create user
                                    </button>
                                </div>
                            </div>
                        </form>
                </div>
            </tab>
        </tabset>
    </div>
</div>
