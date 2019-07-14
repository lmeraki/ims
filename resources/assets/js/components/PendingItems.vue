<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #38c172; color: #ffffff !important;">
                        Pending Items List
                        <div class="card-tools">
                           
                        </div>
                    </div>

                    <div class="card-body">
                       <table id="itemTable" class="table table-bordered table-hover">
                           <thead>
                               <th width="15%">Borrower</th>
                               <th width="10%">Serial Code</th>
                               <th width="15%">Item Name</th>
                               <th width="15%">Description</th>
                               <th width="8%">Quantity</th>
                               <th width="15%">Requested at</th>
                               <th width="35%">Action</th>
                           </thead>
                           <tbody>
                               <tr v-for="item in items.data" :key="item.id">
                                   <td>{{item.user_name}}</td>
                                   <td>{{item.serial}}</td>
                                   <td>{{item.name}}</td>
                                   <td>{{item.description}}</td>
                                   <td>{{item.quantity}}</td>
                                   <td>{{item.created_at | created_at_time}}</td>
                                   <td>
                                       <button @click="approveItem(item.id)" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Approve</button>
                                       <button @click="declineItem(item.id)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Decline</button>
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
                axios.get('pendings')
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
            approveItem(id){
                this.$Progress.start();
                axios.put('approveitem/' + id)
                .then(({data}) => {
                    this.$Progress.finish();
                    this.$emit('serverRequest');
                    toast.fire({
                        type: 'success',
                        title: 'Request approved'
                    });
                })
                .catch((data) => {
                    console.log(data);
                    this.$Progress.fail();
                });
            },
            declineItem(id){
                this.$Progress.start();
                axios.put('declineitem/' + id)
                .then(({data}) => {
                    this.$Progress.finish();
                    this.$emit('serverRequest');
                    toast.fire({
                        type: 'success',
                        title: 'Request declined'
                    });
                })
                .catch((data) => {
                    console.log(data);
                    this.$Progress.fail();
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
            console.log('Pending items component mounted.')
        }
    }
</script>
