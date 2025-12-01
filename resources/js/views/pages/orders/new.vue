<template>
    <div id="app">
      <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-2">
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
          <div class="max-w-5xl mx-auto px-1 sm:px-6 lg:px-1">
            <div class="mt-10 my-6 bg-white shadow overflow-hidden sm:rounded-md">
              <div class="bg-white md:grid md:grid-cols-2 lg:px-4 px-2 py-4">
                <div class="md:col-span-2">
                  <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-3">
                      <loading :status="loading.form" />
                      <form @submit.prevent="saveOrder">
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="subject">{{ $t('Subject') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                          <input
                            id="subject"
                            v-model="order.subject"
                            :placeholder="$t('Subject')"
                            class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            required
                          />
                        </div>
                        <label class=" text-sm font-medium leading-5 text-gray-700" for="branches">{{ $t('Branches') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                          <input-select id="branches" v-model="order.branches_id" :options="branchList" option-label="name" required />
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                        </div>
                        <tr>
                          <th style="width:45%" class="text-left text-sm font-medium text-gray-700">{{ $t('Item Name') }}</th>
                          <th style="width:30%" class="text-left text-sm font-medium text-gray-700">{{ $t('Quantity') }}</th>
                          <th style="width:5%" class="text-left text-sm font-medium text-gray-700">{{ $t('Action') }}</th>
                        </tr>
                        <tr v-for="(item, index) in order.orderItems" :key="index">
                          <td>
                            <input-select id="stocks" v-model="item.name" :placeholder="$t('Select Items')" :options="filteredItemsList(index)" option-label="name" required />
                          </td>
                          <td>
                            <input
                              class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                              type="number" min="1" step="1"
                              v-model="item.quantity"
                              required
                            />
                          </td>

                          <td>
                            <button class="btn-sm btn btn-red shadow-sm rounded-md" @click="removeInvoiceItem(index)">x</button>
                          </td>
                        </tr>
                        <div class="bg-white-100 ">
                          <div class="inline-flex">
                            <div class="float-left col-md-12">
                              <button class="btn btn-blue shadow-sm rounded-md" @click.prevent="addInvoiceItem">{{ $t('Add Item') }}</button>&nbsp;
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
  export default {
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
        uploadingFileProgress: 0,
        order: {
          subject: null,
          branches_id: null,
          attachments: [],
          orderItems: [
            {
              name: "",
              quantity: 1,
            }
          ],
        },
        branchList: [],
        itemsList: [],
      };
    },
    mounted() {
      this.getBranches();
      this.getItems();
     // this.fetchOptions();
    },
    methods: {
      getBranches() {
        const self = this;
        self.loading.form = true;
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
      getItems() {
        const self = this;
        self.loading.form = true;
        axios.get('api/stocks/all')
          .then(function (response) {
            self.itemsList = response.data;
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

        // Map item IDs to item names
        self.order.orderItems = self.order.orderItems.map(item => {
          const selectedItem = self.itemsList.find(i => i.id === item.name);
          return {
            stock_id: selectedItem ? selectedItem.id : item.name,
            quantity: item.quantity
          };
        });

        axios.post('api/orders', self.order)
          .then(function (response) {
            self.$notify({
              title: self.$i18n.t('Success').toString(),
              text: self.$i18n.t('Data saved correctly').toString(),
              type: 'success'
            });

            // Reset only specific fields
            self.order.subject = ''; // Reset Order subject
            self.order.branches_id = null; // Reset branches_id
            self.order.orderItems = [{ name: "", quantity: 1 }]; // Reset items

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
          name: "",
          quantity: 1,
        });
      },
      removeInvoiceItem(index) {
        this.order.orderItems.splice(index, 1);
      },
      filteredItemsList(index) {
        const selectedItems = this.order.orderItems.map((item, i) => i !== index ? item.name : null);
        return this.itemsList.filter(item => !selectedItems.includes(item.id));
      }
    }
  };
  </script>
