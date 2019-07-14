<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                              <div class="col-md-6 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                  <div class="inner">
                                    <h3>{{items.no}}</h3>

                                    <p>No. of Borrowed Items</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-bag"></i>
                                  </div>
                                </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-md-6 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                  <div class="inner">
                                    <h3>{{items.pendings}}</h3>

                                    <p>No. of Pending Items</p>
                                  </div>
                                  <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                  </div>
                                </div>
                              </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">

                          <li class="nav-item">
                            <a class="nav-link active" id="b_items-tab" data-toggle="tab" href="#b_items" role="tab" aria-controls="b_items" aria-selected="true">My Borrowed Items</a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link" id="p_items-tab" data-toggle="tab" href="#p_items" role="tab" aria-controls="p_items" aria-selected="false">My Pending Items</a>
                          </li>

                        </ul>

                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="b_items" role="tabpanel" aria-labelledby="b_items-tab">
                            <hr>
                            <div class="card">
                              <div class="card-header" style="background-color: #556080; color: white !important;">
                              </div>

                              <div class="card-body">
                                 <table id="itemTable" class="table table-bordered table-hover">
                                     <thead>
                                         <th width="">Serial Code</th>
                                         <th width="">Name</th>
                                         <th width="">Description</th>
                                         <th width="">Borrowed Date</th>
                                         <!-- <th width="">Return Date | Days Left</th> -->
                                         <th width="">Qty</th>
                                         <!-- <th width="">Fee</th> -->
                                         <!-- <th width="">Fines/Day</th> -->
                                         <!-- <th width="">Payment</th> -->
                                         <th width="5%">Action</th>
                                     </thead>
                                     <tbody>
                                         <tr v-for="item in borrowedItems.data" :key="item.id">
                                             <td v-text="item.serial"></td>
                                             <td v-text="item.name"></td>
                                             <td v-text="item.description"></td>
                                             <td>{{item.created_at | created_at_time}}</td>
                                             <!-- <td>{{item.return_date | created_at}} | {{item.return_date | days_left}}</td> -->
                                             <td v-text="item.quantity"></td>
                                             <!-- <td>&#8369; {{ item.fee }}</td> -->
                                             <!-- <td>&#8369; {{ item.fine }}</td> -->
                                             <!-- <td>&#8369; {{ item.fee }}</td> -->
                                             <td>
                                                 <button @click="returnItem(item)" class="btn btn-success btn-sm"><i class="fa fa-redo"></i></button>
                                             </td>
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
                              <div class="card-header" style="background-color: #556080; color: white !important;">
                              </div>

                              <div class="card-body">
                                 <table id="itemTable" class="table table-bordered table-hover">
                                     <thead>
                                         <th width="">Serial Code</th>
                                         <th width="">Name</th>
                                         <th width="">Description</th>
                                         <th width="">Borrowed Date</th>
                                         <!-- <th width="">Return Date | Days Left</th> -->
                                         <th width="">Qty</th>
                                         <!-- <th width="">Fee</th> -->
                                         <!-- <th width="">Fines/Day</th> -->
                                         <!-- <th width="">Payment</th> -->
                                     </thead>
                                     <tbody>
                                         <tr v-for="item in pendingItems.data" :key="item.id">
                                             <td v-text="item.serial"></td>
                                             <td v-text="item.name"></td>
                                             <td v-text="item.description"></td>
                                             <td>{{item.created_at | created_at_time}}</td>
                                             <!-- <td>{{item.return_date | created_at}} | {{item.return_date | days_left}}</td> -->
                                             <td v-text="item.quantity"></td>
                                             <!-- <td>&#8369; {{ item.fee }}</td> -->
                                             <!-- <td>&#8369; {{ item.fine }}</td> -->
                                             <!-- <td>&#8369; {{ item.fee }}</td> -->
                            
                                         </tr>
                                     </tbody>
                                 </table>
                              </div>
                              <div class="card-footer">
                                  <pagination show-disabled :limit="2" size="small" align="right" :data="pendingItems" @pagination-change-page="getResults2"></pagination>
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
            },

            borrowedItems: {},
            pendingItems: {},

            form: new Form({
              id: '',
              item_id: '',
            })
          }

        },
        methods: {
          readBorrowedItems(){
              this.$Progress.start();
              axios.get('borrowitem')
              .then(({data}) => {
                this.borrowedItems = data;
                axios.get('pendingitems/user')
                .then(({data}) => {
                  this.pendingItems = data;
                  axios.get('borrowitem/userdetails')
                  .then(({data}) => {
                    this.items.no = data.tb_items;
                    this.items.pendings = data.tp_items;
                  });
                });
                this.$Progress.finish();
              })
              .catch((data) => {
                this.$Progress.fail();
              })
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
                        this.form.put('borrowitem/' + this.form.id)
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
                axios.get('borrowitem?page=' + page)
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
                axios.get('pendingitems/user?page=' + page)
                .then(response => {
                    this.pendingitems = response.data;
                    this.$Progress.finish();
                })
                .catch((data) => {
                    this.$Progress.fail();
                    console.log(data);
                });
          },
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
