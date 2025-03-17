<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'settings';

if (isset($_POST['submit'])) {
    $fields = $_POST['fields'];
    $arr_fields = explode(',', $fields);
    if (is_array($arr_fields) and count($arr_fields) > 0) {
        foreach ($arr_fields as $fname) {
            $fname             = trim($fname);
            $s                 = [];
            $s['option_name']  = $fname;
            $s['option_value'] = $_POST[$fname];

            $rest = $db->select('ai_options', ['option_name' => $fname], 1)->row();
            if (is_object($rest)) {
                $db->update('ai_options', $s, ['option_name' => $fname]);
            } else {
                $db->insert('ai_options', $s);
            }
        }
        session()->set_flashdata('success', 'Settings updated successfully');
    }
    redirect(admin_url("setting/general_setting.php"));
}

$arr_default['logo'] = '';
$arr_default['message'] = '';
$arr_default['pdf_link'] = '';

$arr_default['zoom_link'] = '';
$arr_default['zoom_info'] = '';

$options = [];
$rest = $db->select('ai_options')->result();
if (is_array($rest) and count($rest) > 0) {
    foreach ($rest as $row) {
        $options[$row->option_name] = $row->option_value;
    }
}

$_GET['options'] = $options;
$_GET['default'] = $arr_default;



function get_option($fname)
{
    $arr_options = $_GET['options'];
    $arr_default = $_GET['default'];
    if (isset($arr_options[$fname])) {
        return $arr_options[$fname];
    } else {
        if (isset($arr_default[$fname])) {
            return $arr_default[$fname];
        } else {
            return NULL;
        }
    }
}

include "../common/header.php";

?>
<div class="box p-3 shadow-sm">
    <div class="page-header">
        <h5>Global Settings</h5>
    </div>
    <form enctype="multipart/form-data" action="" method="post">
        <div class="box-p">
            <div class="form-group row">
                <label class="col-sm-2 control-label">Dashboard Logo</label>
                <div class="col-sm-8">
                    <input type="text" name="logo" value="<?= get_option('logo'); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Dashboard Message </label>
                <div class="col-sm-8">
                    <textarea cols="20" class="form-control" rows="5" name="message"><?= get_option('message'); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">PDF Download</label>
                <div class="col-sm-8">
                    <input type="text" name="pdf_link" value="<?= get_option('pdf_link'); ?>" placeholder="PDF Plan link" class="form-control input-sm" />
                </div>
            </div>

            <fieldset>
                <legend>Zoom Meeting Details</legend>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Zoom Meeting Link </label>
                    <div class="col-sm-3">
                        <input type="text" name="zoom_link" value="<?= get_option('zoom_link'); ?>" placeholder="Zoom Meeting Link" class="form-control input-sm" />
                    </div>
                    <label class="col-sm-2 control-label">Zoom Meeting Info </label>
                    <div class="col-sm-3">

                        <input type="text" name="zoom_info" value="<?= get_option('zoom_info'); ?>" placeholder="Zoom Meeting Info" class="form-control input-sm" />
                    </div>
                </div>
            </fieldset>

            <div class="form-group row">
                <label class="col-sm-2">&nbsp;</label>
                <div class="col-sm-5">
                    <button type="submit" name="submit" value="Save Settings" class="btn btn-primary"><i class="fa fa-save"></i> Save </button>
                    <a href="<?= site_url('settings/restore'); ?>" class="btn btn-secondary reset"><i class="fa fa-close"></i> Restore Default</a>
                </div>
            </div>
        </div>
        <?php
        $str = '';
        if (is_array($arr_default) && count($arr_default) > 0) {
            foreach ($arr_default as $key => $val) {
                $str .= $key . ',';
            }
        }
        $str = rtrim($str, ',');
        ?>
        <input type="hidden" name="fields" value="<?= $str; ?>" />
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.reset').click(function() {
            if (!confirm('It will RESET all values. Are you sure to proceed?'))
                return false;
        });
    });
</script>


<?php

include "../common/footer.php";
