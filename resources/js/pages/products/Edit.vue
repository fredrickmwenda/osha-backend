<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Edit Product</h3>
            </div>
            <div class="panel-body">
                <product-form ref="formProduct"></product-form>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormProduct from './Form.vue'
    export default {
        name: 'EditProduct',
        methods: {
            //WHEN THE UPDATE BUTTON IS PRESSED
            submit() {
                //IT WILL THEN RUN THE SUBMIT FUNCTION THAT IS IN THE FORM FILE. VUE USES $REFS
                this.$refs.formProduct.submit()
            }
        },
        components: {
            'product-form': FormProduct
        },
    }
</script>
