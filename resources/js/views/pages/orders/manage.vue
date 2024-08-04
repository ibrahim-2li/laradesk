<template>
    <div class="py-10">
        <header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-5">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="py-0.5 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                            {{ $t('Order details') }}
                        </h2>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <router-link
                            class="btn btn-blue shadow-sm rounded-md"
                            to="/orders/list"
                        >
                            {{ $t('Return to orders list') }}
                        </router-link>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mt-10 my-6 bg-white shadow overflow-hidden sm:rounded-md">
                    <loading :status="loading.form"/>
                    <div class="sm:flex sm:items-center py-3 max-w-full">
                        <div class="px-6 sm:pl-6 sm:pr-3 sm:flex-1 sm:w-3/4">
                            <div class="text-xl truncate">{{ order.subject }}</div>
                        </div>
                        <div class="px-6 sm:pl-3 sm:pr-6 sm:flex-1 sm:w-1/4">
                            <div class="flex items-center sm:float-right">
                                <div class="text-sm sm:pr-2">{{ order.updated_at | momentFormatDateTimeAgo }}</div>

                            </div>
                        </div>
                    </div>
                    <template v-if="order.closed_by == null">
                            <div class="block">
                                <template v-for="orderReply in order.orderReplies">
                                    <div v-if="index === 0" :key="orderReply.id" class="border-t flex p-6">
                                        <img
                                            :alt="$t('Avatar')"
                                            :src="orderReply.user.avatar !== 'gravatar' ? orderReply.user.avatar : orderReply.user.gravatar"
                                            class="h-12 w-12 hidden sm:inline"
                                        />
                                        <div class="sm:pl-6 pb-2 w-full">
                                            <div class="md:flex md:items-center pb-1">
                                                <div class="md:flex-1 text-lg font-semibold text-gray-800">
                                                    {{ orderReply.user.name }}
                                                </div>
                                                <div class="md:flex-1">
                                                    <div class="md:float-right text-sm">
                                                        {{ orderReply.created_at | momentFormatDateTime }}
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-gray-700 order-reply-body" v-html="orderReply.body"/>
                                             <!-- Item Table -->
                                                <div class="text-xl truncate">
                                                    <div class="flex p-1 border-b">
                                                    <div class="sm:pl-6 pb-2 w-full">
                                                        <div class="table">
                                                        <tr>
                                                            <td style="width:50%">
                                                            <div>
                                                                <p class="md:text-left">{{ $t('Item Name') }}</p>
                                                            </div>
                                                            </td>
                                                            <td style="width:50%">
                                                            <div>
                                                                <p class="text-gray-700 ">{{ $t('Details') }}</p>
                                                            </div>
                                                            </td>
                                                            <td style="width:30%">
                                                            <div>
                                                                <p class="text-center text-gray-700">{{ $t('Count') }}</p>
                                                            </div>
                                                            </td>
                                                        </tr>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <template v-for="(Item, index) in order.orderItems">
                                                    <div class="flex p-1 border-t" :key="index">
                                                        <div class="sm:pl-6 pb-2 w-full">
                                                            <div class="table">
                                                                <tr>
                                                                    <td style="width:50%">
                                                                        <p  class="md:float-left  text-sm"  v-html="Item.item"/>
                                                                    </td>
                                                                    <td style="width:50%">
                                                                        <p class="text-gray-700 text-sm" v-html="Item.details"/>
                                                                    </td>
                                                                    <td  style="width:30%">
                                                                        <p class="text-center text-gray-700 text-sm" v-html="Item.item_count"/>
                                                                    </td>
                                                                </tr>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </template>
                                                </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template v-else>
                                <div class="border-t flex p-6">
                                    <img
                                            :alt="$t('Avatar')"
                                            :src="defaultAvatar"
                                            class="h-12 w-12 hidden sm:inline"
                                        />
                                        <div class="sm:pl-6 pb-2 w-full">
                                            <div class="md:flex md:items-center pb-1">
                                                <div class="md:flex-1 text-lg font-semibold text-gray-800">
                                                    {{ order.confirmItems.name }}
                                                </div>
                                                <div class="md:flex-1">
                                                    <div class="md:float-right text-sm">
                                                        {{ order.created_at | momentFormatDateTime }}
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-gray-700 order-reply-body"> {{ $t('Order confirmed successfuly') }}</p>
                                                <br>

                                             <!-- Item Table -->
                                                <div class="text-xl truncate">
                                                    <div class="flex p-1 border-b">
                                                    <div class="sm:pl-6 pb-2 w-full">
                                                        <div class="table">
                                                        <tr>
                                                            <td style="width:50%">
                                                            <div>
                                                                <p class="md:text-left">{{ $t('Item Name') }}</p>
                                                            </div>
                                                            </td>
                                                            <td style="width:50%">
                                                            <div>
                                                                <p class="text-gray-700 ">{{ $t('Details') }}</p>
                                                            </div>
                                                            </td>
                                                            <td style="width:30%">
                                                            <div>
                                                                <p class="text-center text-gray-700">{{ $t('Count') }}</p>
                                                            </div>
                                                            </td>
                                                        </tr>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <template v-for="Item in order.confirmItems">
                                                    <div class="flex p-1 border-t">
                                                        <div class="sm:pl-6 pb-2 w-full">
                                                            <div class="table">
                                                                <tr>
                                                                    <td style="width:50%">
                                                                        <p  class="md:float-left  text-sm"  v-html="Item.item"/>
                                                                    </td>
                                                                    <td style="width:50%">
                                                                        <p class="text-gray-700 text-sm" v-html="Item.details"/>
                                                                    </td>
                                                                    <td  style="width:30%">
                                                                        <p class="text-center text-gray-700 text-sm" v-html="Item.item_count"/>
                                                                    </td>
                                                                </tr>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    </template>
                                                </div>
                                                </div>
                                    </div>
                                </template>
                                <template v-for="(orderReply, index) in order.orderReplies">
                                    <div :class="{'border-t' : index > 0}" class="flex p-6">
                                        <img
                                            :alt="$t('Avatar')"
                                            :src="orderReply.user.avatar !== 'gravatar' ? orderReply.user.avatar : orderReply.user.gravatar"
                                            class="h-12 w-12 hidden sm:inline"
                                        />
                                        <div class="sm:pl-6 pb-2 w-full">
                                            <div class="md:flex md:items-center pb-1">
                                                <div class="md:flex-1 text-lg font-semibold text-gray-800">
                                                    {{ orderReply.user.name }}
                                                </div>
                                                <div class="md:flex-1">
                                                    <div class="md:float-right text-sm">
                                                        {{ orderReply.created_at | momentFormatDateTime }}
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-gray-700 order-reply-body" v-html="orderReply.body"/>
                                             <!-- Item Table -->
                                                <div class="text-xl truncate">
                                                    <div class="flex p-1 border-b">
                                                    <div class="sm:pl-6 pb-2 w-full">
                                                        <div class="table">
                                                        <tr>
                                                            <td style="width:50%">
                                                            <div>
                                                                <p class="md:text-left">{{ $t('Item Name') }}</p>
                                                            </div>
                                                            </td>
                                                            <td style="width:50%">
                                                            <div>
                                                                <p class="text-gray-700 ">{{ $t('Details') }}</p>
                                                            </div>
                                                            </td>
                                                            <td style="width:30%">
                                                            <div>
                                                                <p class="text-center text-gray-700">{{ $t('Count') }}</p>
                                                            </div>
                                                            </td>
                                                        </tr>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <template v-for="(Item, index) in order.orderItems">
                                                    <div class="flex p-1 border-t" :key="index">
                                                        <div class="sm:pl-6 pb-2 w-full">
                                                            <div class="table">
                                                                <tr>
                                                                    <td style="width:50%">
                                                                        <p  class="md:float-left  text-sm"  v-html="Item.item"/>
                                                                    </td>
                                                                    <td style="width:50%">
                                                                        <p class="text-gray-700 text-sm" v-html="Item.details"/>
                                                                    </td>
                                                                    <td  style="width:30%">
                                                                        <p class="text-center text-gray-700 text-sm" v-html="Item.item_count"/>
                                                                    </td>
                                                                </tr>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </template>
                                                </div>
                                        </div>
                                    </div>





                        </template>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: "index",
    metaInfo() {
        return {
            title: this.$i18n.t('Order details')
        }
    },
    data() {
        return {

            defaultAvatar: '/images/default/done.png', // relative path to your public directory

            loading: {
                form: true,
                reply: false,
                file: false,
            },
            replyForm: false,
            uploadingFileProgress: 0,
            order: {
                subject: null,
                created_at: null,
                orderReplies: [],
                itemConfirms: [],

            },
            itemConfirms: {
               orders_status_id: null,
                item: '',
                item_count: '',
                details: ' ,'
            },
            orderReply: {
                order_status_id: null,
                body: '',
                attachments: [],
            },
        }
    },
    filters: {
        momentFormatDateTime: function (value) {
            return moment(value).locale(window.app.app_date_locale).format(window.app.app_date_format + ' HH:mm');
        },
        momentFormatDateTimeAgo: function (value) {
            return moment(value).locale(window.app.app_date_locale).fromNow();
        },
    },
    mounted() {
        this.getOrder();
    },
    methods: {
        getOrder() {
            const self = this;
            self.loading.form = true;
            axios.get('api/orders/' + self.$route.params.uuid).then(function (response) {
                self.loading.form = false;
                self.order = response.data;
            }).catch(function () {
                self.$router.push('/orders/list');
            });
        },
        addReply() {
            const self = this;
            self.loading.reply = true;
            axios.post('api/orders/' + self.$route.params.uuid + '/reply', self.orderReply).then(function (response) {
                self.$notify({
                    title: self.$i18n.t('Success').toString(),
                    text: self.$i18n.t('Data saved correctly').toString(),
                    type: 'success'
                });
                self.discardReply();
                self.order = response.data.order;
                self.loading.reply = false;
            }).catch(function () {
                self.loading.reply = false;
            });
        },
        discardReply() {
            this.orderReply.body = '';
            this.orderReply.attachments = [];
            this.replyForm = false;
        },
        selectUploadFile() {
            if (!this.loading.file) {
                this.$refs.fileInput.click();
            } else {
                this.$notify({
                    title: this.$i18n.t('Error').toString(),
                    text: this.$i18n.t('A file is being uploaded').toString(),
                    type: 'warning'
                });
            }
        },
        uploadFile(e) {
            const self = this;
            const formData = new FormData();
            self.loading.file = true;
            formData.append('file', e.target.files[0]);
            axios.post(
                'api/orders/attachments',
                formData,
                {
                    headers: {'Content-Type': 'multipart/form-data'},
                    onUploadProgress: function (progressEvent) {
                        self.uploadingFileProgress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                    }.bind(this)
                }
            ).then(function (response) {
                self.loading.file = false;
                self.uploadingFileProgress = 0;
                self.$refs.fileInput.value = null;
                self.orderReply.attachments.push(response.data);
            }).catch(function () {
                self.loading.file = false;
                self.uploadingFileProgress = 0;
                self.$refs.fileInput.value = null;
            });
        },
        removeAttachment(attachment) {
            this.orderReply.attachments.splice(attachment, 1);
        }
    }
}
</script>
