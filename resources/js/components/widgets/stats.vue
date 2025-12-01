<template>
    <div class="grid grid-cols-2 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <loading :status="stats.open_orders == null"/>
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ $t('Open orders') }}
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.open_orders ? stats.open_orders : 0 }}
                </dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <loading :status="stats.pending_orders == null"/>
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ $t('Pending orders') }}
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.pending_orders ? stats.pending_orders : 0 }}
                </dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <loading :status="stats.sended_orders == null"/>
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ $t('Solved orders') }}
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.sended_orders ? stats.sended_orders : 0 }}
                </dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <loading :status="stats.all_orders == null"/>
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ $t('All Orders') }}
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.all_orders ? stats.all_orders : 0 }}
                </dd>
            </div>
        </div>
    </div>
</template>

<script>
import MenuItem from "@/components/layout/dashboard/menu/dash-item";
import dashItem from '../layout/dashboard/menu/dash-item.vue';
export default {
  components: { dashItem },
    name: "stats",
    data() {
        return {
            stats: {
                open_orders: null,
                pending_orders: null,
                sended_orders: null,
                without_agent: null,
            }
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            const self = this;
            axios.get('api/dashboard/stats/count').then(function (response) {
                self.stats = response.data;
            });
        }
    },
}
</script>

