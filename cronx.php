<?php

include_once "config/autoload.php";
include_once "common/User.php";




// Generate ROI
cron_daily_roi();

// Generate Matching
cron_daily_matching();

function cron_daily_roi()
{
    $w = date("w");
    if ($w == 0) { // Disabled for sunday
        return;
    }
    $userModel = new User();
    $db = db_connect();
    $list = $db->select('ai_userplans', ['status' => 1])->result();
    $dt = date("Y-m-d");
    $sl = 1;
    foreach ($list as $item) {
        $comments = "ROI OF $dt";
        $chkOb = $db->select('ai_transaction', ['user_id' => $item->user_id, 'comments' => $comments], 1)->row();
        if (is_object($chkOb)) continue;

        $txn_id = $item->user_id . "-roi-$dt";
        $ref_id = $userModel->credit($item->user_id, $item->daily_roi, INCOME_ROI, $item->user_id, $comments, $txn_id);

        // Send Roi Level
        $comments = "ROI LEVEL/CB{$item->user_id}";
        $userModel->sendROILabelIncome($item->user_id, $item->daily_roi, $ref_id, $comments);
        if ($item->end_dt <= $dt) {
            // End plan
            $db->update('ai_userplans', ['status' => 2], ['id' => $item->id], 1);
        }
    }
    echo "$sl ROI Generated";
}

function cron_daily_matching()
{
    $userModel = new User();
    $db = db_connect();
    $list = $db->select('ai_userplans', ['status' => 1])->result();
    $dt = date("Y-m-d", strtotime("-1 day"));
    $sl = 1;
    foreach ($list as $item) {
        $userModel->singlePersonMatching($item->user_id, $dt);
        $sl++;
    }
    echo "$sl Matching Generated";
}
