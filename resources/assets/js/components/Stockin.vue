<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #38c172; color: #ffffff !important;">
                        Items List
                        <div class="card-tools">
                           
                        </div>
                    </div>

                    <div class="card-body">
                       <table id="itemTable" class="table table-bordered table-hover">
                           <thead>
                               <th width="10%">Serial Code</th>
                               <th width="20%">Name</th>
                               <th width="25%">Description</th>
                               <th width="20%">Qty. Left / Max Qty.</th>
                               <th width="15%">Action</th>
                           </thead>
                           <tbody>
                               <tr v-for="item in items.data" :key="item.id">
                                   <td v-text="item.serial"></td>
                                   <td>{{item.name}}</td>
                                   <td>{{item.description}}</td>
                                   <td>{{item.item_quantity}} / {{item.max_quantity}}</td>
                                   <td>
                                       <button @click="editItemModal(item.id)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
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
                        <form @submit.prevent="stockInItem">
                            <div class="modal-header" style="background-color: #38c172; color: #ffffff !important;">
                                <h5 class="modal-title" id="modal"><i class="fa fa-file"></i> Item Stock In</h5>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="quantity">Stock In Quantity</label>
                                        <input id="quantity" type="number" name="quantity" v-model="form.quantity" :class="{'is-invalid': form.errors.has('quantity')}" class="form-control form-control-sm" placeholder="Enter quantity" min="1" autocomplete="off">
                                        <has-error :form="form" field="quantity"></has-error>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <input :disabled="form.busy" type="submit" class="btn btn-primary" value="Stock In">
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
                form: new Form({
                    id: '',
                    quantity: ''
                })
            }
        },
        methods: {
            readItems(){
                this.$Progress.start();
                axios.get('stockin')
                .then(({data}) => {
                    this.items = data;
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
            editItemModal(id){
                axios.get('stockin/item/' + id)
                .then(({data}) => {
                    this.form.fill(data.data);
                    $('#itemModal').modal('show');
                })
                .catch((data) => {
                    console.log(data);
                });
            },
            stockInItem(){
                this.form.post('stockin')
                .then(() => {
                    $('#itemModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Item stock in successfully'
                    });
                    this.$emit('serverRequest');
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
            getResults(page = 1){
                this.$Progress.start();
                axios.get('stockin?page=' + page)
                .then(response => {
                    this.items = response.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
        },
        mounted() {
            this.readItems();
            this.$on('serverRequest', () => {
                this.readItems();
            });
            console.log('Stock in component mounted.')
        }
    }
</script>
