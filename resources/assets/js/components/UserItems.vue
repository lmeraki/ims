<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #556080; color: white !important;">
                        Items List
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body">
                            <form @submit.prevent="editMode ? updateTempItem() : createTempItem()">
                                <!-- ADD ITEM -->
                                <div class="card">
                                    <div v-show="!editMode" class="card-header bg-primary"><h6>Borrow Item</h6></div>
                                    <div v-show="editMode" class="card-header bg-success"><h6>Edit Item</h6></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="item">Select Item</label>
                                                    <select :disabled="editMode" @input="tempForm.errors.clear('item')" id="item" name="item" v-model="tempForm.item" :class="{'is-invalid': tempForm.errors.has('item')}" class="form-control form-control-sm">
                                                        <optgroup v-for="itemToBuy in itemsToBuy" label="==============================================================">
                                                            <option v-for="item in itemToBuy" :value="item.id">
                                                                [{{item.category_name}}] - Item: {{item.name}} - Quantity Left: [{{item.item_quantity}}]
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <has-error :form="tempForm" field="item"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Quantity</label>
                                                            <input @input="tempForm.errors.clear('quantity')" id="quantity" placeholder="Enter quantity" type="number" min="1" name="quantity" v-model="tempForm.quantity" :class="{'is-invalid': tempForm.errors.has('quantity')}" class="form-control form-control-sm">
                                                            <has-error :form="tempForm" field="quantity"></has-error>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button :disabled="tempForm.busy" v-show="!editMode" type="submit" class="btn btn-primary btn-sm btn-block">Add to Table</button>
                                        <div v-show="editMode" class="row">
                                            <div class="col-md-6">
                                                <button :disabled="tempForm.busy" type="submit" class="btn btn-success btn-sm btn-block">Save Changes</button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" @click="addMode" class="btn btn-secondary btn-sm btn-block">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                       <div class="card">
                           <div class="card-header">
                               <h3>Selected Items</h3>
                               <div class="card-tools">
                                   <form @submit.prevent="createBorrowItems">
                                        <button v-show="showButton" type="submit" class="btn btn-success">Send for Approval</button>
                                   </form>
                               </div>
                           </div>
                           <div class="card-body">
                               <table id="itemTable" class="table table-bordered table-hover">
                                   <thead>
                                       <th width="40%%">Name</th>
                                       <th width="10%">Quantity</th>
                                       <th width="10%">Action</th>
                                   </thead>
                                   <tbody>
                                       <tr v-for="itemFromTemp in itemsFromTemp.data" :key="itemFromTemp.id">
                                           <td>{{itemFromTemp.name}} - {{itemFromTemp.description}}</td>
                                           <td>{{itemFromTemp.quantity}}</td>
                                           <td>
                                               <button @click="editTempItem(itemFromTemp)" type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button> 
                                               <button @click="deleteTempItem(itemFromTemp.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                           </td>
                                       </tr>
                                   </tbody>
                               </table>
                           </div>
                           <div class="card-footer">
                               
                           </div>
                       </div>
                    </div>
                    <div class="card-footer">
                        <pagination show-disabled :limit="2" size="small" align="right" :data="itemsFromTemp" @pagination-change-page="getResults"></pagination>
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
                itemsToBuy: {},
                categories: {},
                itemsFromTemp: {},
                editMode: false,
                showButton: false,
                date: {
                    min: '',
                    max: ''
                },

                tempForm: new Form({
                    id: '',
                    item: '',
                    quantity: '',
                }),
            }
        },
        methods: {
            addMode(){
                this.editMode = false;
                this.tempForm.clear();
                this.tempForm.reset();
            },
            editTempItem(items){
                this.editMode = true;
                this.tempForm.clear();
                this.tempForm.fill(items);
            },
            readItemsToBuy(){
                axios.get('item/tobuy')
                .then(({data}) => {
                    this.itemsToBuy = data.items;
                    this.categories = data.categories;
                })
                .catch((data) => {
                    toast.fire({
                        type: 'error',
                        title: 'Something went wrong'
                    });
                    console.log(data);
                });
            },
            readTempItems(){
                this.$Progress.start();
                axios.get('tempitem')
                .then(({data}) => {
                    this.itemsFromTemp = data;
                    if (this.itemsFromTemp.data.length != 0) {
                        this.showButton = true;
                    } else {
                        this.showButton = false;
                    }
                    this.$Progress.finish();
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
            },
            createTempItem(){
                this.$Progress.start();
                this.tempForm.post('tempitem')
                .then(() => {
                    this.$Progress.finish();
                    this.$emit('serverRequest');
                    this.tempForm.reset();
                    toast.fire({
                        type: 'success',
                        title: 'Added successfully'
                    });
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
            },
            updateTempItem(){
                this.$Progress.start();
                this.tempForm.put('tempitem/' + this.tempForm.id)
                .then(() => {
                    this.$Progress.finish();
                    this.$emit('serverRequest');
                    this.editMode = false;
                    this.tempForm.clear();
                    this.tempForm.reset();
                    toast.fire({
                        type: 'success',
                        title: 'Updated successfully'
                    });
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            deleteTempItem(id){
                this.$Progress.start();
                this.tempForm.delete('tempitem/' + id)
                .then((data) => {
                    this.$Progress.finish();
                    this.$emit('serverRequest');
                    toast.fire({
                        type: 'success',
                        title: 'Removed successfully'
                    });
                    this.editMode = false;
                    this.clearForm();
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
            },
            createBorrowItems(){
                this.$Progress.start();
                axios.post('borrowitem')
                .then((data) => {
                    this.$Progress.finish();
                    this.$emit('serverRequest');
                    toast.fire({
                        type: 'success',
                        title: 'Sent successfully'
                    });
                })
                .catch((data) => {
                    this.$Progress.fail();
                });
            },
            getResults(page = 1){
                this.$Progress.start();
                axios.get('tempitem?page=' + page)
                .then(response => {
                    this.items = response.data;
                    this.$Progress.finish();
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
            },
            clearForm(){
                this.tempForm.reset();
                this.tempForm.clear();
            }
        },
        mounted() {
            this.readItemsToBuy();
            this.readTempItems();
            this.$on('serverRequest', () => {
                this.readItemsToBuy();
                this.readTempItems();
            });

            // var today = new Date();
            // var sevendays = new Date();

            // var dd = String(today.getDate()).padStart(2, '0');
            // var dd7 = String(today.getDate() + 7).padStart(2, '0');
            // var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            // var yyyy = today.getFullYear();

            // today = yyyy + '-' + mm + '-' + dd;
            // this.date.min = today;
            // sevendays = yyyy + '-' + mm + '-' + dd7;
            // this.date.max = sevendays;

            console.log('Items component mounted.')
        }
    }
</script>
