<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #38c172; color: #ffffff !important;">
                        Items List
                        <div class="card-tools">
                            <button @click="addItemModal" type="button" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i>  Add Item</button>
                        </div>
                    </div>

                    <div class="card-body">
                       <table id="itemTable" class="table table-bordered table-hover">
                           <thead>
                               <th>Category</th>
                               <th>Serial Code</th>
                               <th>Name</th>
                               <th>Description</th>
                               <th>Max Qty.</th>
                               <th>Date added</th>
                               <th>Availability</th>
                               <th>Action</th>
                           </thead>
                           <tbody>
                               <tr v-for="item in items.data" :key="item.id">
                                   <td v-text="item.category_name"></td>
                                   <td v-text="item.serial"></td>
                                   <td v-text="item.name"></td>
                                   <td v-text="item.description"></td>
                                   <td>{{ item.max_quantity }}</td>
                                   <td>{{ item.created_at | created_at}}</td>
                                   <td>
                                        <span v-if="item.availability === 1" class="badge badge-success">Available</span>
                                        <span v-else class="badge badge-danger">Unavailable</span>
                                    </td>
                                   <td>
                                       <button @click="editItemModal(item)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button> 
                                       <button @click="deleteItem(item.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                   </td>
                               </tr>
                           </tbody>
                       </table>
                    </div>
                    <div class="card-footer">
                        <pagination show-disabled :limit="2" size="small" align="right" :data="items" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="modal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="editMode ? updateItem() : createItem()">
                            <div class="modal-header" style="background-color: #38c172; color: #ffffff !important;">
                                <h5 v-show="!editMode" class="modal-title" id="modal"><i class="fa fa-plus"></i> Add Item</h5>
                                <h5 v-show="editMode" class="modal-title" id="modal"><i class="fa fa-file"></i> Edit Item</h5>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div v-show="!editMode">
                                            <label for="serial">Serial Code</label>
                                            <input id="serial" type="text" name="serial" v-model="form.serial" :class="{'is-invalid': form.errors.has('serial')}" class="form-control form-control-sm" placeholder="Enter item serial">
                                                <has-error :form="form" field="serial"></has-error>
                                        </div>
                                        <div v-show="editMode">
                                            <label for="serial">Serial Code</label>
                                            <input id="serial" type="text" name="serial" readonly v-model="form.serial" :class="{'is-invalid': form.errors.has('serial')}" class="form-control form-control-sm" placeholder="Enter item serial">
                                            <has-error :form="form" field="serial"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select @change="form.errors.clear('category')" id="category" type="text" name="category" v-model="form.category" :class="{'is-invalid': form.errors.has('category')}" class="form-control form-control-sm">
                                            <option value="" hidden>Select Category</option>
                                            <option v-for="category in categories.data" :value="category.id">{{category.name}}</option>
                                        </select>
                                        <has-error :form="form" field="category"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Item name</label>
                                        <input @input="form.errors.clear('name')" id="name" type="text" name="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}" class="form-control form-control-sm" placeholder="Enter item name" autocomplete="off">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Item description</label>
                                        <input @input="form.errors.clear('description')" id="description" type="text" name="description" v-model="form.description" :class="{'is-invalid': form.errors.has('description')}" class="form-control form-control-sm" placeholder="Enter item description" autocomplete="off">
                                        <has-error :form="form" field="description"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_quantity">Max Quantity</label>
                                        <input @input="form.errors.clear('max_quantity')" id="max_quantity" type="number" name="description" v-model="form.max_quantity" :class="{'is-invalid': form.errors.has('max_quantity')}" class="form-control form-control-sm" placeholder="Enter item max quantity" min="1" autocomplete="off">
                                        <has-error :form="form" field="max_quantity"></has-error>
                                    </div>
                                    <div v-show="editMode" class="form-group">
                                        <label for="availability">Availability</label>
                                        <select @change="form.errors.clear('availability')" id="availability" type="number" name="availability" v-model="form.availability" :class="{'is-invalid': form.errors.has('availability')}" class="form-control form-control-sm">
                                            <option value="1">Available</option>
                                            <option value="0">Unavailable</option>
                                        </select>
                                        <has-error :form="form" field="availability"></has-error>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <input v-show="!editMode" :disabled="form.busy" type="submit" class="btn btn-primary" value="Add Item">
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
                items: {},
                categories: {},
                editMode: false,
                form: new Form({
                    id: '',
                    serial: '',
                    category: '',
                    name: '',
                    description: '',
                    max_quantity: '',
                    availability: ''
                })
            }
        },
        methods: {
            readItems(){
                this.$Progress.start();
                axios.get('item')
                .then(({data}) => {
                    this.items = data;
                    axios.get('category')
                    .then(({data}) => {
                        this.categories = data;
                    });
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
            addItemModal(){
                this.clearForm();
                // this.generateSerial();
                this.editMode = false;
                $('#itemModal').modal('show');
            },
            editItemModal(item){
                this.editMode = true;
                this.form.fill(item);
                $('#itemModal').modal('show');
            },
            createItem(){
                this.$Progress.start();
                this.form.post('item')
                .then(() => {
                    $('#itemModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Item added successfully'
                    });
                    this.$emit('serverRequest');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            updateItem(){
                this.form.put('item/' + this.form.id)
                .then(() => {
                    $('#itemModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Item updated successfully'
                    });
                    this.$emit('serverRequest');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            deleteItem(item_id){
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
                        this.form.delete('item/' + item_id)
                        .then(({data}) => {
                            if (data.response === 1) {
                                this.$Progress.fail();
                                toast.fire({
                                    type: 'error',
                                    title: 'This item is already used in some transaction and cannot be deleted.'
                                });
                            } else {
                                this.$Progress.finish();
                                toast.fire({
                                    type: 'success',
                                    title: 'Item deleted successfully'
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
                axios.get('item?page=' + page)
                .then(response => {
                    this.items = response.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            clearForm(){
                this.form.reset();
                this.form.clear();
            },
            generateSerial(){
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

                for (var i = 0; i < 10; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
                this.form.serial = text;
            }
        },
        mounted() {
            this.readItems();
            this.$on('serverRequest', () => {
                this.readItems();
            });
            console.log('Items component mounted.')
        }
    }
</script>
