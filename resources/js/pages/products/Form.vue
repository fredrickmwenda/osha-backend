<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">Name of Item</label>
            <input
                type="text"
                class="form-control"
                v-model="product.name"
                placeholder="Kemeja"
            />
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.unit_type }">
            <label for="">Type</label>
            <select v-model="product.unit_type" class="form-control">
                <option value="">Choose</option>
                <option value="Kilogram">Kilogram</option>
                <option value="Cut">Cut</option>
            </select>
            <p class="text-danger" v-if="errors.unit_type">
                {{ errors.unit_type[0] }}
            </p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- WHEN THE ADD NEW BUTTON IS PRESSED-->
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.laundry_type }"
                >
                    <label for=""
                        >Type of Service
                        <sup
                            ><a
                                @click="showForm = true"
                                href="javascript:void(0)"
                                v-if="!showForm"
                                >Add New</a
                            ></sup
                        ></label
                    >
                    <select v-model="product.laundry_type" class="form-control">
                        <option value="">Choose</option>
                        <option
                            v-for="(row, index) in laundry_types"
                            :key="index"
                            :value="row.id"
                            >{{ row.name }}</option
                        >
                    </select>
                    <p class="text-danger" v-if="errors.laundry_type">
                        {{ errors.laundry_type[0] }}
                    </p>
                </div>
            </div>

            <!-- THEN THE FORM TO ADD LAUNDRY TYPE WILL BE DISPLAYED -->
            <div class="col-md-6" v-if="showForm">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.name_laundry_type }"
                >
                    <label for="">&nbsp;</label>
                    <div class="input-group">
                        <input
                            type="text"
                            placeholder="Dry Washing + Iron"
                            v-model="laundry_type"
                            class="form-control"
                        />
                        <a
                            href="javascript:void(0)"
                            class="input-group-addon btn btn-warning btn-sm"
                            id="basic-addon2"
                            @click="addNewLaundryType"
                            >Save</a
                        >
                    </div>
                    <p class="text-danger" v-if="errors.name_laundry_type">
                        {{ errors.name_laundry_type[0] }}
                    </p>
                </div>
            </div>
            <!-- END FORM ADD KIND LAUNDRY -->
        </div>

        <div class="form-group" :class="{ 'has-error': errors.price }">
            <label for="">Harga</label>
            <input type="number" class="form-control" v-model="product.price" />
            <p class="text-danger" v-if="errors.price">{{ errors.price[0] }}</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.service }"
                >
                    <label for="">Long Workmanship</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="product.service"
                    />
                    <p class="text-danger" v-if="errors.service">
                        {{ errors.service[0] }}
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors.service_type }"
                >
                    <label for="">Unit</label>
                    <select class="form-control" v-model="product.service_type">
                        <option value="">Choose</option>
                        <option value="Day">Day</option>
                        <option value="Hour">Hour</option>
                    </select>
                    <p class="text-danger" v-if="errors.service_type">
                        {{ errors.service_type[0] }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    name: "FormCourier",
    created() {
        this.getLaundryType();
        if (this.$route.name == "products.edit") {
            this.editProduct(this.$route.params.id).then(res => {
                this.product = {
                    name: res.data.name,
                    unit_type: res.data.unit_type,
                    price: res.data.price,
                    laundry_type: res.data.laundry_type_id,
                    service: res.data.service,
                    service_type: res.data.service_type
                };
            });
        }
    },
    data() {
        return {
            product: {
                name: "",
                unit_type: "",
                price: "",
                laundry_type: "",
                service: "",
                service_type: ""
            },
            laundry_type: "",
            showForm: false
        };
    },
    computed: {
        ...mapState(["errors"]),
        ...mapState("product", {
            laundry_types: state => state.laundry_types
        })
    },
    methods: {
        ...mapActions("product", [
            "getLaundryType",
            "addLaundryType",
            "addProductLaundry",
            "editProduct",
            "updateCourier"
        ]),
        addNewLaundryType() {
            this.addLaundryType({ name_laundry_type: this.laundry_type }).then(
                () => {
                    this.getLaundryType().then(() => {
                        this.showForm = false;
                        this.laundry_type = "";
                    });
                }
            );
        },
        clearForm() {
            this.product = {
                name: "",
                unit_type: "",
                price: "",
                laundry_type: "",
                service: "",
                service_type: ""
            };
        },
        submit() {
            if (this.$route.name == "products.add") {
                this.addProductLaundry(this.product).then(() => {
                    this.clearForm();
                    this.$router.push({ name: "products.data" });
                });
            } else if (this.$route.name == "products.edit") {
                Object.assign(this.product, { id: this.$route.params.id });
                this.updateCourier(this.product).then(() => {
                    this.clearForm();
                    this.$router.push({ name: "products.data" });
                });
            }
        }
    }
};
</script>
