<template>
    <div id="app">
      <div class="py-10">
<<<<<<< HEAD
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-5">
=======
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
          <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h2 class="py-0.5 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                {{ $t('New Order') }}
              </h2>
            </div>
            <br />
            <div class="mt-4 flex md:mt-0 md:ml-4">
              <router-link class="btn btn-blue shadow-sm rounded-md" to="/orders/list">
                {{ $t('Return to orders list') }}
              </router-link>
            </div>
          </div>
<<<<<<< HEAD
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mt-10 my-6 bg-white shadow overflow-hidden sm:rounded-md">
              <div class="bg-white md:grid md:grid-cols-2 px-5 py-5">
=======
          <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-1">
            <div class="mt-10 my-6 bg-white shadow overflow-hidden sm:rounded-md">
              <div class="bg-white md:grid md:grid-cols-2 lg:px-4 px-1 py-1">
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
                <div class="md:col-span-2">
                  <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-3">
                      <loading :status="loading.form" />
                      <form @submit.prevent="saveOrder">
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="subject">{{ $t('Subject') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
<<<<<<< HEAD
                           <input
=======
                          <input
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
                            id="subject"
                            v-model="order.subject"
                            :placeholder="$t('Subject')"
                            class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            required
                          />
                        </div>
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="branches">{{ $t('Branches') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                          <input-select id="branches" v-model="order.branches_id" :options="branchList" option-label="name" required />
                        </div>
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="order_body">{{ $t('Order details') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
<<<<<<< HEAD
                          <input-wysiwyg id="order_body" v-model="order.body" value="New Order" disabled></input-wysiwyg>
                        </div>
                        <tr>
                          <th style="width:50%" class="text-left text-sm font-medium text-gray-700">{{ $t('Item Name') }}</th>
                          <th style="width:20%" class="text-left text-sm font-medium text-gray-700">{{ $t('Count') }}</th>
                          <th style="width:40%" class="text-left text-sm font-medium text-gray-700">{{ $t('Details') }}</th>
                          <th style="width:10%" class="text-left text-sm font-medium text-gray-700">{{ $t('Action') }}</th>
                        </tr>
                        <tr v-for="(item, index) in order.orderItems" :key="index">
                          <td>
                            <treeselect
  :options="options"
  :disable-branch-nodes="true"
  :show-count="true"
   v-model="brandList"
  placeholder="Where are you from?"
  />
=======
                          <input-wysiwyg id="order_body" v-model="order.body"></input-wysiwyg>
                        </div>
                        <tr>
                          <th style="width:45%" class="text-left text-sm font-medium text-gray-700">{{ $t('Item Name') }}</th>
                          <th style="width:30%" class="text-left text-sm font-medium text-gray-700">{{ $t('Count') }}</th>
                          <th style="width:35%" class="text-left text-sm font-medium text-gray-700">{{ $t('Details') }}</th>
                          <th style="width:5%" class="text-left text-sm font-medium text-gray-700">{{ $t('Action') }}</th>
                        </tr>
                        <tr v-for="(item, index) in order.orderItems" :key="index">
                          <td>
                            <input
                              class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                              type="text"
                              v-model="item.item"
                              required
                            />
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
                          </td>
                          <td>
                            <input
                              class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
<<<<<<< HEAD
                              type="number"
                              v-model="item.item_count"
=======
                              type="number" min="1" step="1"
                              v-model="item.item_count"

>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
                            />
                          </td>
                          <td>
                            <input
                              class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                              type="text"
                              v-model="item.details"
                            /><br />
                          </td>
                          <td>
<<<<<<< HEAD
                            <button class="btn btn-red shadow-sm rounded-md" @click="removeInvoiceItem(index)">{{ $t('delete') }}</button>
=======
                            <button class="btn-sm btn btn-red shadow-sm rounded-md" @click="removeInvoiceItem(index)">x</button>
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
                          </td>
                        </tr>
                        <div class="bg-gray-100 px-4 py-3 sm:px-6">
                          <div class="inline-flex">
                            <div class="float-left col-md-12">
<<<<<<< HEAD
                              <button class="btn btn-blue shadow-sm rounded-md" @click="addInvoiceItem()">{{ $t('Add Item') }}</button>
=======
                              <button class="btn btn-blue shadow-sm rounded-md" @click="addInvoiceItem()">{{ $t('Add Item') }}</button>&nbsp;
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
                            </div>
                            <button class="btn btn-green shadow-sm rounded-md" type="submit">
                              {{ $t('Save') }}
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
<<<<<<< HEAD
  import Treeselect from '@riophae/vue-treeselect'
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'

  export default {
    components: {
        Treeselect
    },
=======
  export default {
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
    name: "index",
    metaInfo() {
      return {
        title: this.$i18n.t('New order')
      }
    },
    data() {
      return {
        loading: {
          form: false,
          file: false,
        },
<<<<<<< HEAD
        options: [],
            stockList: [],

        brandList: [],
=======
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
        uploadingFileProgress: 0,
        order: {
          subject: null,
          branches_id: null,
          body: '',
          attachments: [],
          orderItems: [
            {
              item: "",
<<<<<<< HEAD
              item_count: 0,
=======
              item_count: 1,
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
              details: "",
            }
          ],
        },
        branchList: [],
      };
    },
    mounted() {
<<<<<<< HEAD
        this.fetchOptions();
        this.getBrand();
        this.getStock();
      this.getBranches();
    },
    methods: {
        fetchOptions() {
      axios.get('api/dashboard/admin/stocks/')  // Replace with your actual API endpoint
        .then(response => {
          // Assuming the API response is an array of items with `id` and `label`
          this.options = response.data.map(stock => ({
            id: stock.id,
            name: stock.name
          }));
        })
        .catch(error => {
          console.error("Error fetching options:", error);
        });
    },

        getStock() {
            const self = this;
            axios.get('api/dashboard/admin/stocks/').then(function (response) {
                self.stockList = response.data;
            }).catch(function () {
                self.loading = false;
            });
        },
        getBrand() {
            const self = this;
            axios.get('api/dashboard/admin/brands').then(function (response) {
                self.brandList = response.data;
            });
        },
      getBranches() {
        const self = this;
=======
      this.getBranches();
    },
    methods: {
      getBranches() {
        const self = this;
        self.loading.form = true;
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
        axios.get('api/orders/branches')
          .then(function (response) {
            self.branchList = response.data;
            if (self.branchList.length > 0) {
              self.order.branches_id = self.branchList[0].id;
            }
            self.loading.form = false;
          })
          .catch(function (error) {
            self.loading.form = false;
            console.error('Error fetching branches:', error);
          });
      },
      saveOrder() {
    const self = this;
    if (self.loading.form) return;  // Prevent multiple submissions
    self.loading.form = true;

    axios.post('api/orders', self.order)
        .then(function (response) {
            self.$notify({
                title: self.$i18n.t('Success').toString(),
                text: self.$i18n.t('Data saved correctly').toString(),
                type: 'success'
            });

            // Reset only specific fields
            self.order.body = ''; // Reset Order body
            self.order.attachments = []; // Reset attachments
            self.order.orderItems = [{ item: "", item_count: "", details: "" }]; // Reset items

            self.$router.push('/orders/' + response.data.order.uuid);
        })
        .catch(function (error) {
            console.error('Error saving order:', error);
        })
        .finally(() => {
            self.loading.form = false;
        });
},
      addInvoiceItem() {
        this.order.orderItems.push({
          item: "",
<<<<<<< HEAD
          item_count: 0,
=======
          item_count:1,
>>>>>>> 6eac1b63e6d95e91c6ea87f7042cb26dbfe39899
          details: "",
        });
      },
      removeInvoiceItem(index) {
        this.order.orderItems.splice(index, 1);
      },
    }
  };
  </script>
