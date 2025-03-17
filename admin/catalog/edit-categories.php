<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'catalog';

$items = $db->select('ai_categories', [], false, "id DESC")->result();

include "../common/header.php";

?>


<div class="main-content">
    <div class="row">
        <div class="col-sm-12">
        </div>
    </div>
    <div class="page-header">
        <h5>Category Form</h5>
    </div>
    <form action="" enctype="multipart/form-data" method="post">
        <div class="box p-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="tab-pane active" id="description_tab">
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-4">
                                <input type="text" name="cat[name]" value="Fashion &amp; Kids" class="form-control" />
                            </div>
                            <input type="hidden" name="cat[parent_id]" value="0" />
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-3">
                                <select name="cat[status]" class="form-control input-sm">
                                    <option value="1" selected="selected">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <button name="button" value="Save" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
</div>
<?php

include "../common/footer.php";
