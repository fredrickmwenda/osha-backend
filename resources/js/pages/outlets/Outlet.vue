<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <router-link :to="{ name: 'outlets.add' }" class="btn btn-primary btn-sm btn-flat">Tambah</router-link>
                <div class="pull-right">
                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                </div>
            </div>
            <div class="panel-body">
                <b-table striped hover bordered :items="outlets.data" :fields="fields" show-empty>
                    <template v-slot:cell(status)="row">
                        <span class="label label-success" v-if="row.item.status == 1">Active</span>
                        <span class="label label-default" v-else>Inactive</span>
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link :to="{ name: 'outlets.edit', params: {id: row.item.code} }" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></router-link>
                        <button class="btn btn-danger btn-sm" @click="deleteOutlet(row.item.id)"><i class="fa fa-trash"></i></button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="outlets.data"><i class="fa fa-bars"></i> {{ outlets.data.length }} item dari {{ outlets.meta.total }} total data</p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                v-model="page"
                                :total-rows="outlets.meta.total"
                                :per-page="outlets.meta.per_page"
                                aria-controls="outlets"
                                v-if="outlets.data && outlets.data.length > 0"
                                ></b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState } from 'vuex'

export default {
    name: 'DataOutlet',
    created() {
        // BEFORE COMPONENT LOAD, REQUEST DATA FROM SERVER
        this.getOutlets()
    },
    data() {
        return {
            //FIELD FOR B-TABLE, MAKE SURE THE KEY IS COMPATIBLE WITH THE DATABASE FIELD
            //FOR AUTOMATIC RENDER
            fields: [
                { key: 'code', label: 'Outlet Code' },
                { key: 'name', label: 'Outlet Name' },
                { key: 'address', label: 'Address' },
                { key: 'phone', label: 'Telephone Number' },
                { key: 'status', label: 'Status' },
                { key: 'actions', label: 'Actions' }
            ],
            search: ''
        }
    },
    computed: {
        //GETTING OUTLETS DATA FROM STATE OUTLETS
        ...mapState('outlet', {
            outlets: state => state.outlets
        }),
        page: {
            get() {
                //GET VALUE PAGE FROM VUEX MODULE outlet
                return this.$store.state.outlet.page
            },
            set(val) {
                //WHEN THERE IS A CHANGE IN THE VALUE OF THE PAGE, THEN THE STATE OF THE PAGE
                //IN VUEX WILL ALSO BE CHANGED
                this.$store.commit('outlet/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            //WHEN THE VALUE OF THE PAGE CHANGES, IT WILL REQUEST DATA FROM THE SERVER
            this.getOutlets()
        },
        search() {
            //WHEN THE VALUE OF THE SEARCH CHANGES THEN IT WILL REQUEST DATA
            //ACCORDING TO THE DATA BEING SEARCHED
            this.getOutlets(this.search)
        }
    },
    methods: {
        //TAKE FUNCTION FROM VUEX MODULE outlet
        ...mapActions('outlet', ['getOutlets', 'removeOutlet']),
        //WHEN DELETE BUTTON IS CLICKED, THEN WILL RUN THIS METHOD
        deleteOutlet(id) {
            //WILL SHOW A CONFIRMATION WINDOW
            this.$swal({
                title: 'Are you sure?',
                text: "This action will permanently delete!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Continue!'
            }).then((result) => {
                //IF AGREED
                if (result.value) {
                    //THEN THE removeOutlet FUNCTION WILL BE RUN
                    this.removeOutlet(id)
                }
            })
        }
    }
}
</script>