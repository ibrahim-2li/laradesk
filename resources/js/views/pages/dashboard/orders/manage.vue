<template>
    <div class="bg-white flex-1 relative overflow-auto">
        <loading :status="loading.form" />
        <div class="flex relative">
            <div :style="{ height: 'calc(100vh - 70px)' }" class="flex-auto min-w-0">
                <div class="w-full h:auto sm:h-14 border-b sm:px-3">
                    <div class="sm:flex sm:justify-between">
                        <div class="flex">
                            <button class="btn p-4 rounded-none" type="button"
                                @click="$router.push('/dashboard/orders')">
                                <svg-vue class="h-5 w-5 text-gray-700"
                                    icon="font-awesome.chevron-left-regular"></svg-vue>
                            </button>
                            <div v-on-clickaway="closeActionDropdown" class="block">
                                <div class="relative inline-block text-left">
                                    <button class="btn p-4 rounded-none" type="button"
                                        @click="toggleActionDropdown('agent')">
                                        <svg-vue class="h-5 w-5 text-gray-700"
                                            icon="font-awesome.user-tag-regular"></svg-vue>
                                    </button>
                                    <div v-show="actions.agent"
                                        class="origin-top-right absolute left-0 mt-1 w-56 rounded-md shadow-lg z-20">
                                        <div class="rounded-md bg-white shadow-xs">
                                            <div class="py-1">
                                                <template v-for="agent in agentList">
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        href="#" role="menuitem"
                                                        @click.prevent="action('agent', agent.id)">
                                                        {{ agent.name }}
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative inline-block text-left">

                                </div>
                                <div class="relative inline-block text-left">
                                    <button class="btn p-4 rounded-none" type="button"
                                        @click="toggleActionDropdown('label')">
                                        <svg-vue class="h-5 w-5 text-gray-700"
                                            icon="font-awesome.tags-regular"></svg-vue>
                                    </button>
                                    <div v-show="actions.label"
                                        class="origin-top-right absolute left-0 mt-1 w-56 rounded-md shadow-lg z-20">
                                        <div class="rounded-md bg-white shadow-xs">
                                            <div class="py-1">
                                                <template v-for="label in labelList">
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        href="#" role="menuitem"
                                                        @click.prevent="action('label', label.id)">
                                                        {{ label.name }}
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative inline-block text-left">
                                    <button class="btn p-4 rounded-none" type="button"
                                        @click="toggleActionDropdown('priority')">
                                        <svg-vue class="h-5 w-5 text-gray-700"
                                            icon="font-awesome.pennant-regular"></svg-vue>
                                    </button>
                                    <div v-show="actions.priority"
                                        class="origin-top-right absolute left-0 mt-1 w-56 rounded-md shadow-lg z-20">
                                        <div class="rounded-md bg-white shadow-xs">
                                            <div class="py-1">
                                                <template v-for="priority in priorityList">
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        href="#" role="menuitem"
                                                        @click.prevent="action('priority', priority.id)">
                                                        {{ priority.name }}
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn p-4 rounded-none" type="button" @click="deleteOrderModal = true">
                                <svg-vue class="h-5 w-5 text-gray-700" icon="font-awesome.trash-alt-regular"></svg-vue>
                            </button>
                        </div>
                        <div class="flex items-center justify-end m-3 sm:m-0">
                            <div class="text-2xl font-semibold">#{{ order.id }}</div>
                            <div v-if="order.status" class="px-3">
                                <div
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                                    <span :id="order.closed_by">{{ order.user.name }}</span>
                                    |{{ order.status.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-conversation">
                    <div class="block">
                        <div class="sm:flex sm:items-center py-3 max-w-full border-b">
                            <div class="px-6 sm:pl-6 sm:pr-3 sm:flex-1 sm:w-3/4">
                                <div class="text-xl truncate">{{ order.subject }}</div>
                                <template v-for="(label, index) in order.labels">
                                    <div :style="{ backgroundColor: label.color }"
                                        class="inline-flex items-center px-2 py-0.5 mr-1 rounded text-xs font-medium leading-4 text-gray-100">
                                        {{ label.name }}
                                        <button
                                            class="flex-shrink-0 ml-1.5 inline-flex text-gray-100 focus:outline-none focus:text-gray-100 cursor-pointer"
                                            type="button" @click="removeLabel(index)">
                                            <svg-vue class="h-3 w-3" icon="font-awesome.times-solid"></svg-vue>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div class="px-6 sm:pl-3 sm:pr-6 sm:flex-1 sm:w-1/4">
                                <div class="flex items-center sm:float-right mt-3 sm:mt-0">
                                    <template v-if="order.orders_status_id == 1">
                                        <button class="flex items-center btn btn-green p-2 ml-3 sm:ml-0" type="button"
                                            @click="updateForm = true">
                                            <svg-vue class="h-4 w-4 mr-2" icon="font-awesome.reply-regular"></svg-vue>
                                            {{ $t('Send Order') }}
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div v-show="updateForm" class="px-6 py-3 border-b">
                            <loading :status="loading.update" />
                            <form @submit.prevent="update">
                                <table>
                                    <thead>
                                        <tr>
                                            <td style="width:50%" class="text-left text-sm font-medium text-gray-700">{{
                                                $t('Item Name')
                                            }}</td>
                                            <td style="width:30%" class="text-left text-sm font-medium text-gray-700">{{
                                                $t('Count') }}
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(items, index) in order.orderItems" :key="index">
                                            <td>
                                                <input
                                                    class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                    type="text" v-model="items.name" required disabled/><br>
                                            </td>
                                            <td>
                                                <input
                                                    class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                    type="number" v-model="items.quantity" /><br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="bg-gray-100 px-4 py-3 sm:px-2">
                                    <div class="inline-flex">
                                        <button class="btn btn-secondary rounded-none" type="button"
                                            @click="discardReply">
                                            {{ $t('Discard') }}
                                        </button>
                                        <div class="flex">
                                            <select required id="status" v-model="orderDone.orders_status_id"
                                                aria-label="Sort by"
                                                class="block form-select pl-3 pr-9 py-2 border-l border-r-0 border-t-0 border-b-0 border-gray-400 rounded-none bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                                <template v-for="status in statusList">
                                                    <option :value="status.id">{{ status.name }}</option>
                                                </template>
                                            </select>
                                            <button class="btn btn-green rounded-none" type="submit">
                                                {{ $t('Send') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <main>
                            <div class="max-w-max mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="mt-6 my-6 bg-white shadow overflow-hidden sm:rounded-md">
                                    <loading :status="loading.form" />
                                    <div class="sm:flex sm:items-center py-3 max-w-full">
                                        <div class="px-6 sm:pl-6 sm:pr-3 sm:flex-1 sm:w-3/4">
                                        </div>
                                        <div class="px-6 sm:pl-3 sm:pr-6 sm:flex-1 sm:w-1/4">
                                            <div class="text-sm flex items-center sm:float-right">
                                                <div class="text-sm sm:pr-2">
                                                    {{ order.updated_at | momentFormatDateTimeAgo }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="block cursor-not-allowed focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="min-w-0 flex-1 md:grid md:grid-cols-2 md:gap-3">
                                                    <div>
                                                        <div class="leading-5 font-medium text-blue-600 truncate">{{
                                                            $t('Count') }}
                                                        </div>
                                                    </div>
                                                    <div class="md:block">
                                                        <div class="leading-5 text-gray-900">
                                                            <span
                                                                class="leading-5 font-medium text-blue-600 truncate">{{$t('Item Name') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="width: 20px"></div>
                                        </div>
                                    </div>

                                    <div v-for="(orderItem, index) in order.orderItems" :key="index"
                                        :class="{ 'border-t': index > 0 }" class="flex p-3">
                                        <div class="sm:pl-6 pb-0 w-full">
                                            <div class="md:flex md:items-center pb-0">
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
                </div>
            </div>
            <div :style="{ flex: '0 0 240px', width: '240px' }" class="bg-gray-100 hidden lg:block border-l">
                <div v-if="order.user" class="p-6">
                    <div class="flex">
                        <img :alt="$t('Avatar')"
                            :src="order.user.avatar !== 'gravatar' ? order.user.avatar : order.user.gravatar"
                            class="h-16 w-16" />
                    </div>
                    <div class="mt-2">
                        <div class="text-gray-800 font-medium truncate">{{ order.user.name }}</div>
                        <div class="flex items-center text-sm leading-5 text-gray-600">
                            <svg-vue class="flex-shrink-0 mr-1.5 h-4 w-4" icon="font-awesome.envelope-solid"></svg-vue>
                            <span class="truncate">{{ order.user.email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="deleteOrderModal" class="fixed z-20 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <transition duration="300" enter-active-class="ease-out duration-300" enter-class="opacity-0"
                    enter-to-class="opacity-100" leave-active-class="ease-in duration-200" leave-class="opacity-100"
                    leave-to-class="opacity-0">
                    <div v-show="deleteOrderModal" class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                </transition>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
                <transition enter-active-class="ease-out duration-300"
                    enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="ease-in duration-200"
                    leave-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div v-show="deleteOrderModal" aria-labelledby="modal-headline" aria-modal="true"
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                        role="dialog">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg-vue class="h-6 w-6 pb-1 text-red-600"
                                        icon="font-awesome.exclamation-triangle-light"></svg-vue>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 id="modal-headline" class="text-lg leading-6 font-medium text-gray-900">
                                        {{ $t('Delete order') }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm leading-5 text-gray-500">
                                            {{ $t('Are you sure you want to delete the order?') }}
                                            {{ $t('All data will be permanently removed') }}.
                                            {{ $t('All related data will be deleted') }}.
                                            {{ $t('This action cannot be undone') }}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button class="btn btn-red mr-2 sm:mr-0" type="button" @click="deleteOrder">
                                {{ $t('Delete order') }}
                            </button>
                            <button class="btn btn-white mr-0 sm:mr-2" type="button" @click="deleteOrderModal = false">
                                {{ $t('Cancel') }}
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>
<script>
import { first } from "lodash";
import { update } from "lodash";
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
        this.getFilters();
        this.getCannedReplies();
    },
    data() {
        return {
            loading: {
                form: true,
                update: false,
                file: false,
            },
            deleteOrderModal: false,
            updateForm: false,
            uploadingFileProgress: 0,
            order: {
                subject: null,
                created_at: null,
                labels: [],
                orderItems: [],
            },
            orderItems: {
                orders_status_id: 3,
                quantity: '',
            },
            orderDone: {
                orders_status_id: 3,
            },
            actions: {
                agent: false,
                branch: false,
                label: false,
                priority: false,
            },
            cannedReplyList: [],
            agentList: [],
            branchList: [],
            labelList: [],
            statusList: [],
            priorityList: [],
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
            axios.get('api/dashboard/orders/' + self.$route.params.uuid).then(function (response) {
                self.loading.form = false;
                self.order = response.data;
                self.order.orders_status_id = response.data.orders_status_id;
            }).catch(function () {
                self.$router.push('/dashboard/orders');
            });
        },
        getFilters() {
            const self = this;
            axios.get('api/dashboard/orders/filters').then(function (response) {
                self.agentList = response.data.agents;
                self.branchList = response.data.branchs;
                self.labelList = response.data.labels;
                self.statusList = response.data.statuses;
                self.priorityList = response.data.priorities;
            });
        },
        discardReply() {
            this.orderItems = '';
            this.updateForm = false;
        },

        update() {
            const self = this;
            self.loading.update = true;
            axios.post('api/dashboard/orders/' + self.$route.params.uuid + '/update', {
                orderItems: self.order.orderItems,
                orders_status_id: self.orderItems.orders_status_id
            }).then(function (response) {
                self.$notify({
                    title: self.$i18n.t('Success').toString(),
                    text: self.$i18n.t('Data saved correctly').toString(),
                    type: 'success'
                });
                self.order = response.data.order;
                self.orderItems.orders_status_id = response.data.orders_status_id;
                self.discardReply();
                self.loading.reply = false;
            }).catch(function () {
                self.loading.reply = false;
            });
        },

        addReply() {
            const self = this;
            self.loading.reply = true;
            axios.post('api/dashboard/orders/' + self.$route.params.uuid + '/reply', {
                orderItems: self.order.orderItems,
                orders_status_id: self.orderReply.orders_status_id
            }).then(function (response) {
                self.$notify({
                    title: self.$i18n.t('Success').toString(),
                    text: self.$i18n.t('Data saved correctly').toString(),
                    type: 'success'
                });
                self.order = response.data.order;
                self.orderReply.orders_status_id = response.data.orders_status_id;
                self.discardReply();
                self.loading.reply = false;
            }).catch(function () {
                self.loading.reply = false;
            });
        },
        closeActionDropdown() {
            this.actions.agent = false;
            this.actions.branch = false;
            this.actions.label = false;
            this.actions.priority = false;
        },
        toggleActionDropdown(action) {
            if (action === 'agent') {
                this.actions.agent = !this.actions.agent;
                this.actions.branch = false;
                this.actions.label = false;
                this.actions.priority = false;
            }
            if (action === 'branch') {
                this.actions.branch = !this.actions.branch;
                this.actions.agent = false;
                this.actions.label = false;
                this.actions.priority = false;
            }
            if (action === 'label') {
                this.actions.label = !this.actions.label;
                this.actions.agent = false;
                this.actions.branch = false;
                this.actions.priority = false;
            }
            if (action === 'priority') {
                this.actions.priority = !this.actions.priority;
                this.actions.agent = false;
                this.actions.branch = false;
                this.actions.label = false;
            }
        },
        action(param, value) {
            const self = this;
            axios.post('api/dashboard/orders/' + self.$route.params.uuid + '/quick-actions', {
                action: param,
                value: value,
            }).then(function (response) {
                self.closeActionDropdown();
                if (!response.data.access) {
                    self.$router.push('/dashboard/orders');
                } else {
                    self.order = response.data.order;
                    self.orderReply.orders_status_id = response.data.orders_status_id;
                }
            }).catch(function () {
                self.closeActionDropdown();
            });
        },
        deleteOrder() {
            const self = this;
            axios.delete('api/dashboard/orders/' + self.$route.params.uuid).then(function () {
                self.$notify({
                    title: self.$i18n.t('Success').toString(),
                    text: self.$i18n.t('Data deleted successfully').toString(),
                    type: 'success'
                });
                self.$router.push('/dashboard/orders');
            }).catch(function () {
                self.deleteOrderModal = false;
            });
        }
    }
}
</script>
