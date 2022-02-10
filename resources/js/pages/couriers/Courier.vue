<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <router-link
                    :to="{ name: 'couriers.add' }"
                    class="btn btn-primary btn-sm btn-flat"
                    >Add New Courier</router-link
                >
                <div class="pull-right">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <div class="panel-body">
                <!-- THIS SECTION WILL DISPLAY THE COURIER LIST DATA IN THE FORM OF A TABLE -->
                <b-table
                    striped
                    hover
                    bordered
                    :items="couriers.data"
                    :fields="fields"
                    show-empty
                >
                    <!-- WE USE A CUSTOM TEMPLATE BECAUSE IT WILL DISPLAY IMAGES -->
                    <template v-slot:cell(photo)="row">
                        <img
                            :src="'/storage/couriers/' + row.item.photo"
                            :width="80"
                            :height="50"
                            :alt="row.item.name"
                        />
                    </template>
                    <template v-slot:cell(outlet_id)="row">
                        {{ row.item.outlet.name }}
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link
                            :to="{
                                name: 'couriers.edit',
                                params: { id: row.item.id }
                            }"
                            class="btn btn-warning btn-sm"
                            ><i class="fa fa-pencil"></i
                        ></router-link>
                        <button
                            class="btn btn-danger btn-sm"
                            @click="deleteCourier(row.item.id)"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </template>
                </b-table>

                <!-- CONTAINS DATA AMOUNT INFORMATION AND PAGINATION -->
                <div class="row">
                    <div class="col-md-6">
                        <p v-if="couriers.data">
                            <i class="fa fa-bars"></i>
                            {{ couriers.data.length }} item from
                            {{ couriers.meta.total }} total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                v-model="page"
                                :total-rows="couriers.meta.total"
                                :per-page="couriers.meta.per_page"
                                aria-controls="couriers"
                                v-if="couriers.data && couriers.data.length > 0"
                            ></b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";

export default {
    name: "DataCourier",
    created() {
        this.getCouriers(); //WHEN A PAGE IS LOADED, THE FUNCTION TO RETRIEVE DATA IS EXECUTED
    },
    data() {
        return {
            //FIELD TABLE TO BE DISPLAYED DATA
            fields: [
                { key: "photo", label: "#" },
                { key: "name", label: "Full Name" },
                { key: "email", label: "Email" },
                { key: "outlet_id", label: "Outlet" },
                { key: "actions", label: "Action" }
            ],
            search: ""
        };
    },
    computed: {
        ...mapState("courier", {
            couriers: state => state.couriers //STATE THAT STORES COURIER DATA
        }),
        //STATE PAGE TO RETRIEVE AND CHANGE STATE DATA
        page: {
            get() {
                return this.$store.state.courier.page;
            },
            set(val) {
                this.$store.commit("courier/SET_PAGE", val);
            }
        }
    },
    watch: {
        //WHEN THERE IS A CHANGE IN THE VALUE OF THE PAGE
        page() {
            this.getCouriers(); //THEN THIS FUNCTION IS EXECUTED
        },
        //WHEN THERE IS A CHANGE IN THE VALUE OF THE SEARCH BOX
        search() {
            this.getCouriers(this.search); //THIS FUNCTION IS EXECUTED
        }
    },
    methods: {
        ...mapActions("courier", ["getCouriers", "removeCourier"]), //CALL THE FUNCTION IN THE COURIER STORE

        deleteCourier(id) {
            this.$swal({
                title: "You're sure?",
                text: "This will permanently remove it.!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, continue.!"
            }).then(result => {
                if (result.value) {
                    this.removeCourier(id);
                }
            });
        }
    }
};
</script>
