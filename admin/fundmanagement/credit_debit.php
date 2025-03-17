<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'fundmanagement';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $comments = $_POST['comments'];
    $wallet = $_POST['wallet'];
    $form = $_POST['form'];
    $users = $db->select('ai_users', ['username' => $username])->row();

    $form['comments'] = $comments;
    $form['user_id'] = $users->id;
    $form['notes'] = FUND_TRANSFER;
    $form['created'] = date("Y-m-d H:i:s");
    $form['ref_id'] = 1;

    if (is_object($users)) {

        if ($wallet == 'main') {

            $db->insert('ai_transaction', $form);

            session()->set_flashdata('success', 'successfully transfer');
        } else if ($wallet == 'fund') {

            $db->insert('ai_fund', $form);

            session()->set_flashdata('success', 'successfully transfer');
        }
    } else {

        session()->set_flashdata('danger', 'User not found');
    }
}

$menu = 'fundmanagement';

include "../common/header.php";



?>

<script>
    export default {
        data() {
            return {
                items: [], // Yahan par hum fetched data store karenge
                userId: null // Yahan par hum user ID store karenge
            };
        },
        mounted() {
            this.userId = 1; // Yahan par aap apni desired user ID set kar sakte hain
            this.fetchData(this.userId); // Component mount hone par data fetch karna
        },
        methods: {
            async fetchData(id) {
                try {
                    const response = await fetch(`https://admin.cashorbit.net/api.php?action=user-details&id=${id}`); // ID ko URL me daalna
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const data = await response.json(); // JSON format me data ko parse karna
                    this.items = data; // Fetched data ko Vue instance ke data me assign karna
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                }
            }
        }
    };
</script>
<template>
    <div>
        <h1>Fetched Data</h1>
        <ul>
            <li v-for="item in items" :key="item.id">{{ item.name }}</li>
        </ul>
    </div>
</template>

<div id="origin">

    <div class="main-content">

        <div class="row">

            <div class="col-sm-12">

            </div>

        </div>

        <h5>Debit/Credit Balance</h5>

        <hr>

        <form action="" method="post">

            <div id="app" class="row">

                <div class="col-sm-6">

                    <div class="box box-p">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" v-model="username" @blur="getUser" class="form-control text-uppercase" />
                            <div>
                                <span v-if="found" class="badge bg-success">{{ userinfo }}</span>
                                <span v-if="!found" class="badge bg-danger">{{ userinfo }}</span>
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-sm-6">

                                <label>Wallet</label>

                                <select v-model="wallet" class="form-control" name="wallet">

                                    <!-- <option value="main">Main Wallet</option> -->

                                    <option value="fund">Fund Wallet</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <div class="col-sm-6">

                                <label>Debit/Credit</label>

                                <select class="form-control" name="form[cr_dr]">

                                    <option value="dr">Debit</option>

                                    <option value="cr">Credit</option>

                                </select>

                            </div>

                            <div class="col-sm-6">

                                <label>Amount</label>

                                <input type="text" name="form[amount]" class="form-control" />

                            </div>

                        </div>

                        <div class="form-group">

                            <label>Comments</label>

                            <input type="text" name="admin" value="admin" disabled placeholder="admin" class="form-control">

                            <input type="hidden" name="comments" value="admin" class="form-control">

                            <!-- <input name="form[comments]" value="admin" disabled placeholder="admin" type="text" class="form-control" /> -->

                        </div>

                        <div class="row">

                            <div class="col-sm-6">

                                <input type="hidden" name="submit" value="Submit">

                                <button class="btn btn-primary btn-submit">Save</button>

                                <a href="credit_debit.php" class="btn btn-dark">Cancel</a>

                            </div>

                        </div>

                        <!-- <button v-on:click="saveData" class="btn btn-primary">{{ button }}</button> -->

                        <!-- <button class="btn btn-primary">Save</button> -->

                    </div>

                </div>

            </div>

        </form>

        <script>
            var vm = new Vue({

                el: '#app',

                data: {
                    username: null,
                    userinfo: null,
                    message: null,
                    dr_cr: 'cr',
                    amount: 100,
                    button: 'Save',
                    errors: [],
                    message: null,
                    comment: '',
                    msgcls: '',
                    found: false,
                    wallet: 'main'
                },

                methods: {
                    getUser: function() {
                        let url = 'https://admin.cashorbit.net/api.php?action=search'
                        this.userinfo = "Checking...";
                        axios.post(url, {
                            username: this.username
                        }).then(result => {
                            let resp = result.data;
                            if (resp.success) {
                                this.msgcls = 'alert-success';
                                this.userinfo = resp.data.first_name + ' ' + resp.data.last_name + ' (' + resp.data.username + ')';
                                this.found = true;
                            } else {
                                this.msgcls = 'alert-danger';
                                this.userinfo = "Opps!! Invalid Username";
                            }
                        })
                    },
                }
            });
        </script>

    </div>

</div>

</div>



<?php

include "../common/footer.php";
