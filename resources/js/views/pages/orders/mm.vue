<template>
    <div class="py-10">
      <header>
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-5">
          <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h2 class="py-0.5 text-2xl font-bold leading-7 text-gray-900 sm:text-2xl sm:leading-9 sm:truncate">
                {{ $t('Order details') }}
              </h2>
            </div>
            <div class="mt-2 flex md:mt-0 md:ml-2">
              <router-link class="btn btn-blue shadow-sm rounded-md" to="/orders/list">
                {{ $t('Return to orders list') }}
              </router-link>
            </div>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mt-6 my-6 bg-white shadow overflow-hidden sm:rounded-md">
            <loading :status="loading.form" />
            <div class="sm:flex sm:items-center py-3 max-w-full">
              <div class="px-6 sm:pl-6 sm:pr-3 sm:flex-1 sm:w-3/4">
                <div class="text-xl truncate">{{ order.subject }}</div>
              </div>
              <div class="px-6 sm:pl-3 sm:pr-6 sm:flex-1 sm:w-1/4">
                <div class="text-sm flex items-center sm:float-right">
                    <div class="text-sm sm:pr-2">
                    {{ order.updated_at | momentFormatDateTimeAgo }}
                  </div>
                  <span :class="statusClass" class="btn shadow-sm rounded-md">
                    {{ order.status.name }}
                  </span>
                </div>
              </div>
            </div>

            <div class="block cursor-not-allowed focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
              <div class="flex items-center px-4 py-4 sm:px-6">
                <div class="min-w-0 flex-1 flex items-center">
                  <div class="min-w-0 flex-1 md:grid md:grid-cols-2 md:gap-3">
                    <div>
                      <div class="leading-5 font-medium text-blue-600 truncate">{{ $t('Count') }}</div>
                    </div>
                    <div class="md:block">
                      <div class="leading-5 text-gray-900">
                        <span class="leading-5 font-medium text-blue-600 truncate">{{ $t('Item Name') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div style="width: 20px"></div>
              </div>
            </div>

            <div v-for="(orderItem, index) in order.orderItems" :key="index" :class="{ 'border-t': index > 0 }" class="flex p-6">
              <div class="sm:pl-6 pb-2 w-full">
                <div class="md:flex md:items-center pb-1">
                  <div class="md:flex-1 text-sm font-semibold text-gray-800">
                    {{ orderItem.quantity }}
                  </div>
                  <div class="md:flex-1">
                    <div class="md:float-center text-sm">
                      {{ orderItem.name ? orderItem.name : 'N/A' }}
                    </div>
                  </div>
                </div>
                <!-- Item Table -->
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </template>

  <script>
  import { computed } from "vue";
  import { mixin as clickaway } from "vue-clickaway";

  export default {
    name: "manage",
    mixins: [clickaway],
    metaInfo() {
      return {
        title: this.$i18n.t('Manage order')
      };
    },
    mounted() {
      this.getOrder();
    },
    data() {
      return {
        defaultAvatar: '/images/default/done.png', // relative path to your public directory
        loading: {
          form: true,
          update: false,
          file: false,
        },
        order: {
          status: null,
          subject: null,
          created_at: null,
          orderItems: [],
        },
        orderItem: {
          orders_status_id: 3,
          body: '',
          attachments: [],
        },
      };
    },
    filters: {
      momentFormatDateTime: function (value) {
        return moment(value).locale(window.app.app_date_locale).format(window.app.app_date_format + ' HH:mm');
      },
      momentFormatDateTimeAgo: function (value) {
        return moment(value).locale(window.app.app_date_locale).fromNow();
      },
    },
    computed: {
      statusClass() {
        if (!this.order || !this.order.status) return "btn-gray";
        switch (this.order.status.name.toLowerCase()) {
          case "تم الطلب":
            return "btn-blue"; // Replace with your blue class
          case "الطلب معلق":
            return "btn-yellow"; // Replace with your yellow class
          case "تم إرسال الطلب":
            return "btn-green"; // Replace with your green class
          default:
            return "btn-gray"; // Default fallback class
        }
      },
    },
    methods: {
      getOrder() {
        const self = this;
        self.loading.form = true;
        axios
          .get("api/orders/" + self.$route.params.uuid)
          .then(function (response) {
            self.loading.form = false;
            self.order = response.data;
            self.orderItem.orders_status_id = response.data.orders_status_id;
          })
          .catch(function () {
            self.$router.push("/orders");
          });
      },
    },
  };
  </script>

  <style>
  /* Custom button styles */
  .btn-blue {
    background-color: #3b82f6;
    color: white;
  }

  .btn-yellow {
    background-color: #facc15;
    color: black;
  }

  .btn-green {
    background-color: #10b981;
    color: white;
  }

  .btn-gray {
    background-color: #10b981;
    color: white;
  }
  </style>
