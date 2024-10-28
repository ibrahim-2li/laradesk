<template>
    <main style="direction:ltr" class="flex-1 relative overflow-y-auto py-6 focus:outline-none" tabindex="0">
        <form @submit.prevent="saveStock">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-5">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="py-0.5 text-2xl font-semibold text-gray-900">{{ $t('Create Item') }}</h1>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mt-6 shadow sm:rounded-lg">
                    <loading :status="loading"/>
                    <div class="bg-white md:grid md:grid-cols-3 md:gap-6 px-4 py-5 sm:p-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $t('Item details') }}</h3>
                            <p class="mt-1 text-sm leading-5 text-gray-500">
                                {{ $t('This information will be displayed publicly') }}.
                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium leading-5 text-gray-700" for="name">{{ $t('Item Name') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input
                                            id="name"
                                            v-model="stock.name"
                                            :placeholder="$t('The Name')"
                                            class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                            required
                                        >
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <p class="mt-1 text-sm leading-5 text-gray-500">
                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium leading-5 text-gray-700" for="name">{{ $t('Item Count') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input integer
                                            id="count"
                                            v-model="stock.count"
                                            type="number"
                                            :placeholder="$t('The count')"
                                            class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                            required
                                        >
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900"></h3>
                            <p class="mt-1 text-sm leading-5 text-gray-500">

                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium leading-5 text-gray-700" for="name">{{ $t('Item details') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <textarea
                                            id="details"
                                            v-model="stock.details"
                                            :placeholder="$t('The Product Details')"
                                            class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                            required
                                        ></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="md:col-span-3">
                            <div class="py-3">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $t('Item Brand') }}</h3>
                            <p class="mt-1 text-sm leading-5 text-gray-500">
                                {{ $t('Brand for items') }}.
                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium leading-5 text-gray-700" for="role">{{ $t('Brand') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <select
                                            id="brand_id"
                                            v-model="stock.brand_id"
                                            class="mt-1 block form-select w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                            required
                                        >
                                            <option :value="null" disabled>{{ $t('Select an option') }}</option>
                                            <option v-for="brand in brands" :value="brand.id">{{ brand.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <div class="mt-2 relative text-xs text-gray-500">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-100 text-right px-4 py-3 sm:px-6">
                        <span class="inline-flex">
                            <router-link
                                class="btn btn-secondary shadow-sm rounded-md mr-4"
                                to="/dashboard/admin/stock"
                            >
                                {{ $t('Cancel') }}
                            </router-link>
                            <button
                                class="btn btn-green shadow-sm rounded-md"
                                type="submit"
                            >
                                {{ $t('Create Item') }}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </main>
</template>

<script>
export default {
    name: "new-stock",
    metaInfo() {
        return {
            title: this.$i18n.t('Create stock')
        }
    },
    data() {
        return {
            loading: true,
            brands: [],
            stock: {
                name: null,
                details: null,
                brand_id: null,
                count:null,
            },
        }
    },
    mounted() {
        this.getBrand();
    },
    methods: {
        saveStock() {
            const self = this;
            self.loading = true;
            axios.post('api/dashboard/admin/stocks', self.stock).then(function (response) {
                self.loading = false;
                self.$notify({
                    title: self.$i18n.t('Success').toString(),
                    text: self.$i18n.t('Data saved correctly').toString(),
                    type: 'success'
                });
                self.$router.push('/dashboard/admin/stock/' + response.data.stock.id + '/edit');
            }).catch(function () {
                self.loading = false;
            });
        },
        getBrand() {
            const self = this;
            self.loading = true;
            axios.get('api/dashboard/admin/brands/').then(function (response) {
                self.brands = response.data;
                self.loading = false;
            }).catch(function () {
                self.loading = false;
            });
        },
    }
}
</script>
