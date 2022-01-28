<template>
    <div class="row">
        <div class="col-md-6">
            <div
                class="form-group"
                :class="{ 'has-error': errors.customer_id }"
            >
                <label for=""
                    >Customer
                    <sup
                        ><a href="javascript:void(0)" @click="newCustomer"
                            >New Customer</a
                        ></sup
                    ></label
                >
                <!-- WE WILL USE V-SELECT WHERE THE DATA WILL BE LOADED WHEN THE SEARCH KEYWORD IS FOUND -->
                <v-select
                    :options="customers.data"
                    v-model="transactions.customer_id"
                    @search="onSearch"
                    label="name"
                    placeholder="Enter Keyword"
                    :filterable="false"
                >
                    <template slot="no-options">
                        Enter Keyword
                    </template>
                    <template slot="option" slot-scope="option">
                        {{ option.name }}
                    </template>
                </v-select>
                <p class="text-danger" v-if="errors.customer_id">
                    {{ errors.customer_id[0] }}
                </p>
            </div>
        </div>
        <!-- THIS SECTION WILL SHOW CUSTOMER DETAILS IF ISFORM = FALSE, IF TRUE, IN THE NEXT ARTICLE A FORM WILL BE CREATED TO ADD A NEW CUSTOMER -->
        <div
            class="col-md-6"
            v-if="transactions.customer_id != null && !isForm"
        >
            <table>
                <tr>
                    <th width="30%">NIK</th>
                    <td width="5%">:</td>
                    <td>{{ transactions.customer_id.nik }}</td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>:</td>
                    <td>{{ transactions.customer_id.phone }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>:</td>
                    <td>{{ transactions.customer_id.address }}</td>
                </tr>
                <tr>
                    <th>Deposit</th>
                    <td>:</td>
                    <td>Rp {{ transactions.customer_id.deposit }}</td>
                </tr>
                <tr>
                    <th>Point</th>
                    <td>:</td>
                    <td>{{ transactions.customer_id.point }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6" v-if="isForm">
            <h4>Add New Customer</h4>
            <form-customer />
            <button class="btn btn-primary btn-s" @click="addCustomer">
                Save
            </button>
        </div>
        <div class="col-md-12">
            <hr />
            <button
                v-if="filterProduct.length == 0"
                class="btn btn-warning btn-sm"
                style="margin-bottom: 10px"
                @click="addProduct"
            >
                Tambah
            </button>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="40%">Paket</th>
                            <th>Berat/Satuan</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!-- THIS TABLE IS USEFUL FOR ADDING TRANSACTION ITEMS -->
                    <tbody>
                        <tr
                            v-for="(row, index) in transactions.detail"
                            :key="index"
                        >
                            <td>
                                <v-select
                                    :options="products.data"
                                    v-model="row.laundry_price"
                                    @search="onSearchProduct"
                                    label="name"
                                    placeholder="Enter Keyword"
                                    :filterable="false"
                                >
                                    <template slot="no-options">
                                        Enter Keyword
                                    </template>
                                    <template slot="option" slot-scope="option">
                                        {{ option.name }}
                                    </template>
                                </v-select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        v-model="row.qty"
                                        class="form-control"
                                        @blur="calculate(index)"
                                    />
                                    <span class="input-group-addon">{{
                                        row.laundry_price != null &&
                                        row.laundry_price.unit_type ==
                                            "Kilogram"
                                            ? "gram"
                                            : "pcs"
                                    }}</span>
                                </div>
                            </td>
                            <td>Rp {{ row.price }}</td>
                            <td>Rp {{ row.subtotal }}</td>
                            <td>
                                <button
                                    class="btn btn-danger btn-flat"
                                    @click="removeProduct(index)"
                                >
                                    delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- WHEN THE TRANSACTION IS SUCCESSFUL, THE ALERT IS SHOWED -->
        <div class="col-md-12" v-if="isSuccess">
            <div class="alert alert-success">
                Transaksi Berhasil, Total Tagihan: Rp {{ total }}
                <strong
                    ><router-link
                        :to="{
                            name: 'transactions.view',
                            params: { id: transaction_id }
                        }"
                        >View Details</router-link
                    ></strong
                >
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
import FormCustomer from "../customers/Form.vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import _ from "lodash";

export default {
    name: "FormTransaction",
    data() {
        return {
            transaction_id: null,
            isForm: false,
            isSuccess: false,
            transactions: {
                customer_id: null,
                //WE SET THE DEFAULT DETAILS 1 EMPTY ITEMS
                detail: [{ laundry_price: null, qty: 1, price: 0, subtotal: 0 }]
            }
        };
    },
    computed: {
        ...mapState(["errors"]),
        ...mapState("transaction", {
            customers: state => state.customers, //GET STATE CUSTOMER DARI MODULE TRANSACTION
            products: state => state.products //GET STATE PRODUCT DARI MODULE TRANSACTION
        }),
        total() {
            //TOTAL SUBTOTAL
            return _.sumBy(this.transactions.detail, function(o) {
                return parseFloat(o.subtotal);
            });
        },
        filterProduct() {
            return _.filter(this.transactions.detail, function(item) {
                return item.laundry_price == null;
            });
        }
    },
    methods: {
        ...mapActions("transaction", [
            "getCustomers",
            "getProducts",
            "createTransaction"
        ]),
        ...mapActions("customer", ["submitCustomer"]),
        addCustomer() {
            this.submitCustomer().then(res => {
                his.transactions.customer_id = res.data;
                this.isForm = false;
            });
        },
        //THIS METHOD WILL WORK WHEN CUSTOMER DATA SEARCH ON V-SELECT ABOVE
        onSearch(search, loading) {
            //WE WILL REQUEST CUSTOMER DATA BASED ON THE REQUESTED KEYWORD
            this.getCustomers({
                search: search,
                loading: loading
            });
        },
        //THIS METHOD IS FOR SEARCHING PRODUCT DATA FOR LAUNDRY ITEMS
        onSearchProduct(search, loading) {
            //REQUEST DATA PRODUCT
            this.getProducts({
                search: search,
                loading: loading
            });
        },
        //WHEN ADD BUTTON IS pressed, THEN WILL ADD A NEW ITEM
        addProduct() {
            if (this.filterProduct.length == 0) {
                this.transactions.detail.push({
                    laundry_price: null,
                    qty: null,
                    price: 0,
                    subtotal: 0
                });
            }
            this.transactions.detail.push({
                laundry_price: null,
                qty: null,
                price: 0,
                subtotal: 0
            });
        },
        //WHEN THE DELETE BUTTON ON EACH ITEM IS PRESSED, THEN WILL DELETE BASED ON THE DATA INDEX
        removeProduct(index) {
            if (this.transactions.detail.length > 1) {
                this.transactions.detail.splice(index, 1);
            }
        },
        //WHEN INPTAN QTY / WEIGHT / UNIT UN-FOCUS, THEN THIS FUNCTION WILL BE EXECUTED
        calculate(index) {
            let data = this.transactions.detail[index];
            if (data.laundry_price != null) {
                //WHERE WE WILL FILL THE PRICE FOR EACH ITEM AND THE PRICE GETS FROM THE DATA PRODUCT LAUNDRY
                data.price = data.laundry_price.price;
                //THE SUBTOTAL WILL BE CALCULATED BASED ON THE TYPE
                if (data.laundry_price.unit_type == "Kilogram") {
                    //IF KILOGRAM, THEN THE WEIGHT OF THE GOODS * PRICE / 1000
                    data.subtotal = (
                        parseInt(data.laundry_price.price) *
                        (parseInt(data.qty) / parseInt(1000))
                    ).toFixed(2);
                } else {
                    //IF UNIT, THEN PRICE * QTY
                    data.subtotal =
                        parseInt(data.laundry_price.price) * parseInt(data.qty);
                }
            }
        },
        //WHEN CREATE TRANSACTION BUTTON IS PRESSED THEN THIS FUNCTION WILL BE EXECUTED
        submit() {
            this.isSuccess = false;
            let filter = _.filter(this.transactions.detail, function(item) {
                return item.laundry_price != null;
            });
            if (filter.length > 0) {
                this.createTransaction(this.transactions).then(res => {
                    this.transaction_id = res.data.id;
                    this.isSuccess = true;
                });
            }
            this.createTransaction(this.transactions).then(
                () => (this.isSuccess = true)
            );
        },
        newCustomer() {
            this.isForm = true; //CHANGING ISForm VALUE TO TRUE
        },
        resetForm() {
            this.transactions = {
                customer_id: null,
                detail: [{ laundry_price: null, qty: 1, price: 0, subtotal: 0 }]
            };
        }
    },
    components: {
        vSelect,
        "form-customer": FormCustomer
    }
};
</script>
