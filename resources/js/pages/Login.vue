<template>
    <div class="container">
        <div class="login-box">
            <div class="login-logo">
                <router-link :to="{ name: 'home' }"><b>Osha</b></router-link>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <div class="form-group has-feedback" :class="{'has-error': errors.email}">
                    <input type="email" class="form-control" placeholder="Email" v-model="data.email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
                </div>
                <div class="form-group has-feedback" :class="{'has-error': errors.password}">
                    <input type="password" class="form-control" placeholder="Password" v-model="data.password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
                </div>
                <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="data.remember_me"> Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" @click.prevent="postLogin">Login</button>
                    </div>
                </div>

                <a href="#">I forgot my password</a><br>
            </div>
        </div>
    </div>
</template>

<!-- JAVASCRIPT SECTION -->
<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            data: {
                email: '',
                password: '',
                remember_me: false
            }
        }
    },
    // COMPONENT DI-RENDER
    created() {
        //WE CHECK IF IT IS LOGGED IN WHERE THE VALUE ISAuth IS TRUE
        if (this.isAuth) {
            //THEN DIRECTED TO ROUTE WITH NAME HOME
            this.$router.push({ name: 'home' })
        }
    },
    computed: {
        ...mapGetters(['isAuth']), //TAKE ISAUTH GETTERS FROM VUEX
      	...mapState(['errors'])
    },
    methods: {
        ...mapActions('auth', ['submit']), //FILL IN THE SUBMIT() FUNCTION OF VUEX SO THAT IT CAN BE USED IN RELATED COMPONENTS. SUBMIT() COMES FROM THE ACTION IN THE FOLDER STORES/auth.js
        ...mapActions('user', ['getUserLogin']), 
        ...mapMutations(['CLEAR_ERRORS']),

      	//WHEN THE LOGIN BUTTON IS PRESSED, IT TRIGGERS METHODS postLogin()
        postLogin() {
            //WHERE THIS BUTTON WILL RUN THE submit() FUNCTION BY SENDING THE REQUIRED DATA
            this.submit(this.data).then(() => {
                //THEN CHECK THE VALUE FROM isAuth
                //WHEN THE VALUE IS TRUE
                if (this.isAuth) {
                    this.CLEAR_ERRORS()
                    //THEN WE WILL BE DIRECTED TO THE ROUTE WITH THE NAME HOME
                    this.$router.push({ name: 'home' })
                }
            })
        }
    },
    destroyed() {
        this.getUserLogin()
    }
}
</script>