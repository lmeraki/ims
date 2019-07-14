<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #38c172; color: #ffffff !important;">
                        Users List
                        <div class="card-tools">
                            <button @click="addUserModal" type="button" class="btn btn-secondary btn-sm"><i class="fa fa-user-plus"></i> Add User</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Created at</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td v-text="user.id"></td>
                                    <td v-text="user.name"></td>
                                    <td v-text="user.email"></td>
                                    <td v-text="user.user_type"></td>
                                    <td>{{user.created_at | created_at}}</td>
                                    <td>
                                        <button type="button" @click="editUserModal(user)" class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </button> 
                                        <button type="button" @click="deleteUser(user.id)" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination size="small" align="right" :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="modal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="editMode ? updateUser() : createUser()" enctype="">
                            <div class="modal-header" style="background-color: #38c172; color: #ffffff !important;">
                                <h5 v-show="!editMode" class="modal-title" id="modal"><i class="fa fa-user-plus"></i> Add User</h5>
                                <h5 v-show="editMode" class="modal-title" id="modal"><i class="fa fa-user-edit"></i> Edit User</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" v-model="form.name" type="text" name="name" placeholder="Enter name" :class="{'is-invalid': form.errors.has('name')}" class="form-control form-control-sm" autocomplete="off">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_type">User Type</label>
                                            <select id="user_type" v-model="form.user_type" name="user_type" :class="{'is-invalid': form.errors.has('user_type')}" class="form-control form-control-sm">
                                                <option value="" hidden>Select user type</option>
                                                <option value="admin">Admin</option>
                                                <option value="user">User</option>
                                            </select>
                                            <has-error :form="form" field="user_type_id"></has-error>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input id="email" v-model="form.email" type="email" name="email" placeholder="Enter email address" :class="{'is-invalid': form.errors.has('email')}" class="form-control form-control-sm" autocomplete="off">
                                    <has-error :form="form" field="email"></has-error>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input id="password" v-model="form.password" type="password" name="password" placeholder="Enter password" :class="{'is-invalid': form.errors.has('password')}" class="form-control form-control-sm">
                                            <has-error :form="form" field="password"></has-error>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input id="password_confirmation" v-model="form.password_confirmation" type="password" name="password_confirmation" placeholder="Enter confirm password" :class="{'is-invalid': form.errors.has('password_confirmation')}" class="form-control form-control-sm">
                                            <has-error :form="form" field="password_confirmation"></has-error>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <input v-show="!editMode" :disabled="form.busy" type="submit" class="btn btn-primary" value="Add User">
                                <input v-show="editMode" :disabled="form.busy" type="submit" class="btn btn-success" value="Save Changes">
                                <input type="button" data-dismiss="modal" class="btn btn-secondary" value="Cancel">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: {},
                editMode: false,
                form: new Form({
                    id: '',
                    name: '',
                    user_type: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                })
            }
        },
        methods: {
            readUsers(){
                this.$Progress.start();
                axios.get('api/user')
                .then(({data}) => {
                    this.users = data;
                    this.$Progress.finish();
                })
                .catch(({data}) => {
                    console.log(data);
                    this.$Progress.fail();
                });
            },
            addUserModal(){
                this.editMode = false;
                this.form.reset();
                this.form.clear();
                $('#userModal').modal('show');
            },
            editUserModal(user){
                this.editMode = true;
                this.form.reset();
                this.form.clear();
                this.form.fill(user);
                $('#userModal').modal('show');
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                    this.$Progress.finish();
                    this.form.reset();
                    $('#userModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'User added successfully'
                    });
                    this.$emit('serverRequest');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/' + this.form.id)
                .then(() => {
                    this.$Progress.finish();
                    this.form.reset();
                    $('#userModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'User updated successfully'
                    });
                    this.$emit('serverRequest');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            deleteUser(user_id){
                swal.fire({
                    title: 'Are you sure you want to delete this?',
                    text: 'This cannot be undone!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                })
                .then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        this.form.delete('api/user/' + user_id)
                        .then(() => {
                            this.$Progress.finish();
                            toast.fire({
                                type: 'success',
                                title: 'User deleted successfully'
                            });
                            this.$emit('serverRequest');
                        })
                        .catch(() => {
                            this.$Progress.fail();
                            toast.fire({
                                type: 'error',
                                title: 'Something went wrong'
                            });
                        });
                    }
                });
            },
            getResults(page = 1){
                this.$Progress.start();
                axios.get('api/user?page=' + page)
                .then(response => {
                    this.users = response.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            }
        },
        mounted() {
            this.readUsers();
            this.$on('serverRequest', () => {
                this.readUsers();
            });
            console.log('Users component mounted.')
        }
    }
</script>
