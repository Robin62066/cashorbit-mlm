<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');



$menu = 'supports';

if (isset($_GET['act']) && $_GET['act'] == 'del') {

    $id = $_GET['id'];

    $db->delete('ai_categories', ['id' => $id]);

    set_flashdata("success", "Property list deleted");

}

$items = $db->select('ai_categories', [], false, "id DESC")->result();

include "../common/header.php";



?>

<div class="main-content">

    <div class="row">

        <div class="col-sm-12">

        </div>

    </div>

    <div class="page-header">

        <h5>Supports Enquiry</h5>

        <div>

            <a href="https://admin.inspirelife.in/supports/?order=desc" class="btn btn-sm btn-primary">New Tickets</a>

            <a href="https://admin.inspirelife.in/supports/?status=0" class="btn btn-sm btn-dark">Closed Tickets</a>

            <a href="https://admin.inspirelife.in/supports" class="btn btn-sm btn-info">All Tickets</a>

        </div>

    </div>



    <div class="card card-info p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>#Id</th>

                    <th>Subject</th>

                    <th>User Information</th>

                    <th>Mobile</th>

                    <th>Last updated</th>

                    <th>Status</th>

                    <th></th>

                </tr>

            </thead>

            <tbody>

            </tbody>

        </table>

        <div class="card-footer">

            <nav aria-label="Page navigation">

                <ul class="pagination">





                </ul>

            </nav>

        </div>

    </div>

    <?php

    include "../common/footer.php";

