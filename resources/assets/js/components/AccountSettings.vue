<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <form @submit.prevent="saveSettings">
                        <div class="card-header">
                            <h6>Account Settings ({{form.user_type}})</h6>
                            <div class="card-tools">
                                <button type="submit" class="btn btn-success btn-sm">Save changes</button>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input id="name" type="text" name="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}" class="form-control form-control-sm" placeholder="Enter name" autocomplete="off">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input id="name" type="email" name="email" v-model="form.email" :class="{'is-invalid': form.errors.has('email')}" class="form-control form-control-sm" placeholder="Enter email" autocomplete="off">
                                        <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input id="password" type="password" name="password" v-model="form.password" :class="{'is-invalid': form.errors.has('password')}" class="form-control form-control-sm" placeholder="Enter password">
                                        <has-error :form="form" field="password"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" v-model="form.password_confirmation" :class="{'is-invalid': form.errors.has('password_confirmation')}" class="form-control form-control-sm" placeholder="Enter password confirmation">
                                        <has-error :form="form" field="password_confirmation"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    user_type: ''
                })
            }
        },
        methods:
         {
            readUserSettings() {
                this.$Progress.start();
                axios.get('accountsettings')
                .then(({data}) => {
                    this.form.fill(data);
                    this.$Progress.finish();
                })
                .catch((data) => {
                    this.$Progress.fail();
                });
            },
            saveSettings() {
                this.$Progress.start();
                this.form.put('accountsettings/' + this.form.id)
                .then((data) => {
                    toast.fire({
                        type: 'success',
                        title: 'Account settings successfully changed.'
                    });
                    this.$Progress.finish();
                    location.reload(true);
                    // this.$emit('serverRequest');
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
            }
        },
        mounted() {
            this.readUserSettings();
            // this.$on('serverRequest', () => {
            //     this.readUserSettings();
            // });
            console.log('Component mounted.')
        }
    }
</script>
