<template>
    <router-link
        :to="to"
    >
    <li>
        <svg-vue
                    :icon="icon"
                    class=" w-8 h-8 "
                            ></svg-vue>
                    <span class=" display: flex">
                        <p class="mt-1 text-xl font-semibold text-gray-900 float:left">{{ label }}</p>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 float:left">
                            {{ value }}
                        </h3>

                    </span>
                </li>

    </router-link>
</template>

<script>
export default {
    name: "dash-item",
    props: {
        mobile: {
            type: Boolean,
            default: false,
        },
        to: {
            type: String,
            require: true,
        },
        icon: {
            type: String,
            require: true,
        },
        label: {
            type: String,
            require: true,
        },
        value: {
            type: String,
            require: true,
        },
    }
}
</script>
