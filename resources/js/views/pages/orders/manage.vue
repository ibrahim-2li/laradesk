<template>
    <div class="py-10">
        <header>
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-5">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2
                            class="py-0.5 text-2xl font-bold leading-7 text-gray-900 sm:text-2xl sm:leading-9 sm:truncate">
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
                            <div class="flex items-center sm:float-right">
                                <div class="text-sm sm:pr-2">{{ order.updated_at | momentFormatDateTimeAgo }}</div>

                            </div>
                        </div>
                    </div>
                    <template v-if="order.orders_status_id == 3">
                        <div class="border-t flex p-6">
                            <img :alt="$t('Avatar')" :src="defaultAvatar" class="h-12 w-12 hidden sm:inline" />
                            <div class="sm:pl-6 pb-2 w-full">
                                <div class="md:flex md:items-center pb-1">
                                    <div class="md:flex-1 text-lg font-semibold text-gray-800">

                                    </div>
                                    <div class="md:flex-1">
                                        <div class="md:float-right text-sm">
                                            {{ order.created_at | momentFormatDateTime }}
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-700 order-reply-body"> <img :alt="$t('Avatar')" :src="defaultAvatar"
                                        class="h-5 w-5 inline  sm:hidden" /> {{ $t('Order confirmed successfuly') }}</p>


                                <!-- Item Table -->
                                <div class="text-xl truncate">
                                    <div
                                        class="block cursor-not-allowed focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="min-w-0 flex-1 md:grid md:grid-cols-2 md:gap-4">
                                                    <div>
                                                        <div
                                                            class="text-sm leading-5 font-medium text-blue-600 truncate">
                                                            {{ $t('Count') }}</div>
                                                    </div>
                                                    <div class="md:block">
                                                        <div class="text-sm leading-5 text-gray-900">
                                                            <span class="text-cool-gray-900 font-medium"></span>
                                                            {{ $t('Item Name') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <template v-for="Iteme in order.confirmItems">
                                        <div class="flex p-1 border-t"></div>
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="min-w-0 flex-1 md:grid md:grid-cols-2 md:gap-4">
                                                    <div>
                                                        <div
                                                            class="text-sm leading-5 font-medium text-blue-600 truncate">
                                                            {{ Iteme.item_count }}</div>
                                                    </div>
                                                    <div class="hidden md:block">
                                                        <div class="text-sm leading-5 text-gray-900">
                                                            <span class="text-cool-gray-900 font-medium">{{ Iteme.item
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <svg-vue class="h-5 w-5 text-gray-400"
                                                    icon="font-awesome.angle-right-regular"></svg-vue>
                                            </div>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </div>
                        <div class="= shadow ">
                            <p class="text-left">_</p>
                        </div>
                    </template>
                    <template v-for="(orderReply, index) in order.orderReplies">
                        <div :class="{ 'border-t': index > 0 }" class="flex p-6">
                            <img :alt="$t('Avatar')"
                                :src="orderReply.user.avatar !== 'gravatar' ? orderReply.user.avatar : orderReply.user.gravatar"
                                class="h-12 w-12 hidden sm:inline" />
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
                                <p class="text-gray-700 order-reply-body" v-html="orderReply.body" />
                                <!-- Item Table -->
                                <div
                                    class="block cursor-not-allowed focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    <div class="flex items-center px-4 py-4 sm:px-6">
                                        <div class="min-w-0 flex-1 flex items-center">
                                            <div class="min-w-0 flex-1 md:grid md:grid-cols-2 md:gap-4">
                                                <div>
                                                    <div class="text-sm leading-5 font-medium text-blue-600 truncate">{{
                                                        $t('Count') }}</div>
                                                </div>
                                                <div class="md:block">
                                                    <div class="text-sm leading-5 text-gray-900">
                                                        <span class="text-cool-gray-900 font-medium"></span>
                                                        {{ $t('Item Name') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="width: 20px"></div>
                                    </div>
                                </div>
                                <div class="text-xl truncate">
                                    <template v-for="(Item, index) in order.orderItems">
                                        <div class="flex p-1 border-t" :key="index"></div>
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="min-w-0 flex-1 md:grid md:grid-cols-2 md:gap-4">
                                                    <div>
                                                        <div
                                                            class="text-sm leading-5 font-medium text-blue-600 truncate">
                                                            {{ Item.item_count }}</div>
                                                    </div>
                                                    <div class="hidden md:block">
                                                        <div class="text-sm leading-5 text-gray-900">
                                                            <span class="text-cool-gray-900 font-medium">{{ Item.item
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <svg-vue class="h-5 w-5 text-gray-400"
                                                    icon="font-awesome.angle-right-regular"></svg-vue>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>





                    </template>
                    <template v-if="order.closed_by == null">
                        <div class="block">
                            <template v-for="orderReply in order.orderReplies">
                                <div v-if="index === 0" :key="orderReply.id" class="border-t flex p-6">
                                    <img :alt="$t('Avatar')"
                                        :src="orderReply.user.avatar !== 'gravatar' ? orderReply.user.avatar : orderReply.user.gravatar"
                                        class="h-12 w-12 hidden sm:inline" />
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
                                        <p class="text-gray-700 order-reply-body" v-html="orderReply.body" />
                                        <!-- Item Table -->
                                        <div class="text-xl truncate">
                                            <div class="flex p-1 border-b">
                                                <div class="sm:pl-6 pb-2 w-full">
                                                    <div class="table">
                                                        <tr>
                                                            <td style="width:50%">
                                                                <div>
                                                                    <p class="md:text-left font-bold text-sm">Item</p>
                                                                </div>
                                                            </td>

                                                            <td style="width:50%">
                                                                <div>
                                                                    <p
                                                                        class="text-center text-gray-700 font-bold text-sm">
                                                                        Count</p>
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
                                                                    <p class="md:float-left  text-sm"
                                                                        v-html="Item.item" />
                                                                </td>

                                                                <td style="width:50%">
                                                                    <p class="text-center text-gray-700 text-sm"
                                                                        v-html="Item.item_count" />
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
                </div>
            </div>
        </main>
    </div>
</template>
<script>

import { mixin as clickaway } from "vue-clickaway";

export default {
    name: "manage",
    mixins: [clickaway],
    metaInfo() {
        return {
            title: this.$i18n.t('Manage order')
        }
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
                labels: [],
                orderReplies: [],
                itemConfirms: [],

            },
            orderReply: {
                orders_status_id: 3,
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
    methods: {
        getOrder() {
            const self = this;
            self.loading.form = true;
            axios.get('api/orders/' + self.$route.params.uuid).then(function (response) {
                self.loading.form = false;
                self.order = response.data;
                self.orderReply.orders_status_id = response.data.orders_status_id;
            }).catch(function () {
                self.$router.push('/orders');
            });
        },


    }
}
</script>
