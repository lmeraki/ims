<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                              <div class="col-md-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                  <div class="inner">
                                    <h3>{{items.no}}</h3>

                                    <p>No. of Borrowed Items</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-bag"></i>
                                  </div>
                                  <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-md-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                  <div class="inner">
                                    <h3>{{items.pendings}}</h3>

                                    <p>No. of Pending Items</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                  </div>
                                  <router-link to="admin_pending" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></router-link>
                                </div>
                              </div>


                              <!-- ./col -->
                              <div class="col-md-4 col-4">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                  <div class="inner">
                                    <h3>{{items.stockin}}</h3>

                                    <p>No. of Items Needed for Stock In</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                  </div>
                                  <router-link to="admin_stockin" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></router-link>
                                </div>
                              </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">

                          <li class="nav-item">
                            <a class="nav-link active" id="b_items-tab" data-toggle="tab" href="#b_items" role="tab" aria-controls="b_items" aria-selected="true">Borrowed Items List</a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link" id="p_items-tab" data-toggle="tab" href="#p_items" role="tab" aria-controls="p_items" aria-selected="false">Items Logs</a>
                          </li>

                        </ul>

                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="b_items" role="tabpanel" aria-labelledby="b_items-tab">
                            <hr>
                            <div class="card">
                              <div class="card-header bg-warning">
                              </div>
                              <div class="card-body">
                                <table class="table table-bordered table-hover">
                                  <thead>
                                    <tr>
                                      <th>User</th>
                                      <th>Item</th>
                                      <th>Qty.</th>
                                      <th>Date</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="borrowedItem in borrowedItems.data" :key="borrowedItem.id">
                                      <td>{{ borrowedItem.user_name }}</td>
                                      <td>{{ borrowedItem.name }}</td>
                                      <td>{{ borrowedItem.quantity }}</td>
                                      <td>{{ borrowedItem.created_at | created_at_time }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <div class="card-footer">
                                <pagination show-disabled :limit="2" size="small" align="right" :data="borrowedItems" @pagination-change-page="getResults"></pagination>
                              </div>
                            </div>

                          </div>

                          <div class="tab-pane fade" id="p_items" role="tabpanel" aria-labelledby="p_items-tab">
                            <hr>
                            <div class="card">
                              <div class="card-header bg-warning">
                              </div>
                              <div class="card-body">
                                <table class="table table-bordered table-hover">
                                  <thead>
                                    <tr>
                                      <th>User</th>
                                      <th>Item</th>
                                      <th>Qty.</th>
                                      <th>Action Made</th>
                                      <th>Date</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="borrowedItem in allBorrowedItems.data" :key="borrowedItem.id">
                                      <td>{{ borrowedItem.user_name }}</td>
                                      <td>{{ borrowedItem.name }}</td>
                                      <td>{{ borrowedItem.quantity }}</td>
                                      <td>{{ borrowedItem.status }}</td>
                                      <td>{{ borrowedItem.created_at | created_at_time }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <div class="card-footer">
                                <pagination show-disabled :limit="2" size="small" align="right" :data="allBorrowedItems" @pagination-change-page="getResults2"></pagination>
                              </div>
                            </div>
                          </div>
                        </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
        data(){

          return{
            items: {
              no: '',
              pendings: '',
              stockin: ''
            },

            borrowedItems: {},
            allBorrowedItems: {},

            form: new Form({
              id: '',
              item_id: '',
            })
          }

        },
        methods: {
          readBorrowedItems(){
              this.$Progress.start();
              axios.get('borrowitem/admin')
              .then(({data}) => {
                this.borrowedItems = data;
                axios.get('borrowitem/admindetails')
                .then(({data}) => {
                    this.items.no = data.tb_items;
                    this.items.pendings = data.tp_items;
                    this.items.stockin = data.stock_in;
                    axios.get('borrowitems/admin')
                    .then(({data}) => {
                      this.allBorrowedItems = data;
                    });
                })
                .catch((data) => {
                  console.log(data);
                });
                this.$Progress.finish();
              })
              .catch((data) => {
                this.$Progress.fail();
              })
          },
          readBorrowedDetails(){
            
          },
          returnItem(item){
            this.form.fill(item);
            swal.fire({
                    title: 'Return this item?',
                    text: 'Confirm action',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#38c172',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                })
                .then((result) => {
                    if (result.value) {
                      this.form.fill(item);
                        this.$Progress.start();
                        this.form.put('borrowitem/admin/' + this.form.id)
                        .then(() => {
                            this.$Progress.finish();
                            toast.fire({
                                type: 'success',
                                title: 'Item returned successfully'
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
                axios.get('borrowitem/admin?page=' + page)
                .then(response => {
                    this.borrowedItems = response.data;
                    this.$Progress.finish();
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
          },
          getResults2(page = 1){
              this.$Progress.start();
              axios.get('borrowitems/admin?page=' + page)
              .then(response => {
                  this.allBorrowedItems = response.data;
                  this.$Progress.finish();
              })
              .catch((data) => {
                  this.$Progress.fail();
                  console.log(data);
              });
          }
        },
        mounted() {
            this.readBorrowedItems();
            this.$on('serverRequest', () => {
              this.readBorrowedItems();
            });
            console.log('Component mounted.')
        }
    }
</script>
