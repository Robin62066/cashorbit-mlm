<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'properties';
$items1 = []; //$db->select('ai_transaction', ['comments' => 'admin'], false, "id DESC")->result();
$items2 = $db->select('ai_fund', ['comments' => 'admin'], false, "id DESC")->result();
$menu = 'fundmanagement';
include "../common/header.php";



?>

<div class="main-content">

    <div class="row">

        <div class="col-sm-12">

        </div>

    </div>

    <div class="page-header">

        <h5>

            Credit/Debit Report

        </h5>

    </div>



    <div class="row">

        <div class="col-sm-6 d-none">

            <div class="card card-info p-3">

                <h5 class="card-title">Transcation Table</h5>

                <table class=" table data-table">

                    <thead>

                        <tr>

                            <th>#Id</th>

                            <th>User Id</th>

                            <th>Amount</th>

                            <th>Cr/Dr</th>

                            <th>created</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $sl = 1;

                        foreach ($items1 as $item) {

                        ?>

                            <tr>

                                <td><?= $sl++; ?></td>

                                <td><?= $item->user_id ?></td>

                                <td><?= $item->amount ?></td>

                                <td><?= $item->cr_dr ?></td>

                                <td><?= $item->created ?></td>

                                <td>

                                    <div class="">

                                        <a href="<?= admin_url('fundmanagement/credit_debit_report.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash"></i></a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>



                </table>

                <div class="card-footer">

                    <nav aria-label="Page navigation">

                        <ul class="pagination">





                        </ul>

                    </nav>

                </div>

            </div>

        </div>

        <div class="col-sm-10">

            <div class="card card-info p-3">

                <h5 class="card-title">Fund Table</h5>

                <table class=" table data-table">

                    <thead>

                        <tr>

                            <th>#Id</th>

                            <th>User Id</th>
                            <th>Name</th>
                            <th>Amount</th>

                            <th>Cr/Dr</th>

                            <th>created</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $sl = 1;

                        foreach ($items2 as $item) {
                            $us = $db->query("SELECT first_name, last_name FROM ai_users WHERE id = {$item->user_id} LIMIT 1")->row();
                        ?>

                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= id2userid($item->user_id) ?></td>
                                <td><?= $us->first_name . ' ' . $us->last_name ?></td>
                                <td><?= $item->amount ?></td>
                                <td><?= $item->cr_dr ?></td>
                                <td><?= $item->created ?></td>
                                <td>

                                    <div class="">

                                        <a href="<?= admin_url('fundmanagement/credit_debit_report.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash"></i></a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

                <div class="card-footer">

                    <nav aria-label="Page navigation">

                        <ul class="pagination">





                        </ul>

                    </nav>

                </div>

            </div>

        </div>



    </div>





    <?php

    include "../common/footer.php";
