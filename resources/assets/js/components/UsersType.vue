<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #38c172; color: #ffffff !important;">Users Type</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="80%">User Type</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admin</td>
                                    <td>Edit | Delete</td>
                                </tr>
                            </tbody>
                        </table>
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
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input id="serial" type="text" name="serial" readonly v-model="form.serial" :class="{'is-invalid': form.errors.has('serial')}" class="form-control form-control-sm" placeholder="Enter item serial">
                                                    <has-error :form="form" field="serial"></has-error>
                                                </div>
                                                <div class="col-md-2">
                                                    <button @click="generateSerial" type="button" class="btn btn-secondary btn-sm"><i class="fa fa-recycle"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="editMode">
                                            <label for="serial">Serial Code</label>
                                            <input id="serial" type="text" name="serial" readonly v-model="form.serial" :class="{'is-invalid': form.errors.has('serial')}" class="form-control form-control-sm" placeholder="Enter item serial">
                                            <has-error :form="form" field="serial"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Item name</label>
                                        <input id="name" type="text" name="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}" class="form-control form-control-sm" placeholder="Enter item name">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Item description</label>
                                        <input id="description" type="text" name="description" v-model="form.description" :class="{'is-invalid': form.errors.has('description')}" class="form-control form-control-sm" placeholder="Enter item description">
                                        <has-error :form="form" field="description"></has-error>
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
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
