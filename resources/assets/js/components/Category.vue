<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #38c172; color: #ffffff !important;">
                        Category List
                        <div class="card-tools">
                            <button @click="addCategoryModal" type="button" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i>  Add Category</button>
                        </div>
                    </div>

                    <div class="card-body">
                       <table id="itemTable" class="table table-bordered table-hover">
                           <thead>
                               <th width="80%">Name</th>
                               <th width="20%">Action</th>
                           </thead>
                           <tbody>
                               <tr v-for="item in categories.data" :key="item.id">
                                   <td v-text="item.name"></td>
                                   <td>
                                       <button @click="editCategoryModal(item)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button> 
                                       <button @click="deleteCategory(item.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                   </td>
                               </tr>
                           </tbody>
                       </table>
                    </div>
                    <div class="card-footer">
                        <pagination show-disabled :limit="2" size="small" align="right" :data="categories" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="modal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="editMode ? updateCategory() : createCategory()">
                            <div class="modal-header" style="background-color: #38c172; color: #ffffff !important;">
                                <h5 v-show="!editMode" class="modal-title" id="modal"><i class="fa fa-plus"></i> Add Category</h5>
                                <h5 v-show="editMode" class="modal-title" id="modal"><i class="fa fa-file"></i> Edit Category</h5>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Category name</label>
                                        <input id="name" type="text" name="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}" class="form-control form-control-sm" placeholder="Enter category name" autocomplete="off">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <input v-show="!editMode" :disabled="form.busy" type="submit" class="btn btn-primary" value="Add Category">
                                <input v-show="editMode" :disabled="form.busy" type="submit" class="btn btn-success" value="Save Changes">
                                <input @click="clearForm" type="button" data-dismiss="modal" class="btn btn-secondary" value="Cancel">
                            </div>
                        </form>
                    </div>
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
                categories: {},
                editMode: false,
                form: new Form({
                    id: '',
                    name: '',
                })
            }
        },
        methods: {
            readCategories(){
                this.$Progress.start();
                axios.get('category')
                .then(({data}) => {
                    this.categories = data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Something went wrong'
                    });
                });
            },
            addCategoryModal(){
                this.clearForm();
                this.editMode = false;
                $('#categoryModal').modal('show');
            },
            editCategoryModal(category){
                this.editMode = true;
                this.form.fill(category);
                $('#categoryModal').modal('show');
            },
            createCategory(){
                this.$Progress.start();
                this.form.post('category')
                .then(() => {
                    $('#categoryModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Category added successfully'
                    });
                    this.$emit('serverRequest');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('category/' + this.form.id)
                .then(() => {
                    $('#categoryModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Category updated successfully'
                    });
                    this.$emit('serverRequest');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            deleteCategory(item_id){
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
                        this.form.delete('category/' + item_id)
                        .then(({data}) => {
                            if (data.response === 1) {
                                this.$Progress.fail();
                                toast.fire({
                                    type: 'error',
                                    title: 'This category is being used on some items.'
                                });
                            } else {
                                this.$Progress.finish();
                                toast.fire({
                                    type: 'success',
                                    title: 'Category deleted successfully'
                                });
                                this.$emit('serverRequest');
                            }
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
                axios.get('category?page=' + page)
                .then(response => {
                    this.categories = response.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            clearForm(){
                this.form.reset();
                this.form.clear();
            }
        },
        mounted() {
            this.readCategories();
            this.$on('serverRequest', () => {
                this.readCategories();
            });
            console.log('Categories component mounted.')
        }
    }
</script>
