<template>
    <div class="py-10">
        <header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-5">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="py-0.5 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                            {{ $t('New Order') }}
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
                    <form @submit.prevent="saveOrder">
                        <div class="bg-white md:grid md:grid-cols-3 px-4 py-5">
                            <div class="md:col-span-2">
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3">
                                        <label class="block text-sm font-medium leading-5 text-gray-700" for="subject">{{ $t('Subject') }}</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input
                                                id="subject"
                                                v-model="order.subject"
                                                :placeholder="$t('Subject')"
                                                class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div v-if="branchList.length > 0" class="col-span-3">
                                        <label class="block text-sm font-medium leading-5 text-gray-700" for="branches">{{ $t('Branches') }}</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input-select
                                                id="branches"
                                                v-model="order.branches_id"
                                                :options="branchList"
                                                option-label="name"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-sm font-medium leading-5 text-gray-700" for="order_body">{{ $t('Order body') }}</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input-wysiwyg
                                                id="order_body"
                                                v-model="order.body"
                                                :plugins="{images: false, attachment: false}"
                                                @selectUploadFile="selectUploadFile"
                                            >
                                                <template v-slot:top>
                                                    <div :class="{'bg-gray-200': uploadingFileProgress > 0}" class="h-1 w-full">
                                                        <div :style="{width: uploadingFileProgress + '%'}" class="bg-blue-500 py-0.5"></div>
                                                    </div>
                                                </template>
                                            </input-wysiwyg>
                                        </div>
                                    </div>
                                    <div v-if="order.attachments.length > 0" class="col-span-3">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-2">
                                            <template v-for="(attachment, index) in order.attachments">
                                                <attachment :details="attachment" v-on:remove="removeAttachment(index)"/>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-100 px-4 py-3 sm:px-6">
                            <div class="inline-flex">
                                <button
                                    class="btn btn-green shadow-sm rounded-md"
                                    type="submit"
                                >
                                    {{ $t('Create order') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <input ref="fileInput" hidden type="file" @change="uploadFile($event)">
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
                body: '',
                attachments: [],
            },
            branchList: [],
        }
    },
    mounted() {
        this.getBranches();
    },
    methods: {
        getBranches() {
            const self = this;
            self.loading.form = true;
            axios.get('api/orders/branches').then(function (response) {
                self.branchList = response.data;
                self.loading.form = false;
            }).catch(function (error) {
                self.loading.form = false;
                console.error('Error saving order:', error);
            });
        },
        saveOrder() {
    const self = this;
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

            self.$router.push('/orders/' + response.data.order.uuid);
        })
        .catch(function () {
            self.loading.form = false;
        });
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
                self.order.attachments.push(response.data);
            }).catch(function () {
                self.loading.file = false;
                self.uploadingFileProgress = 0;
                self.$refs.fileInput.value = null;
            });
        },
        removeAttachment(attachment) {
            this.order.attachments.splice(attachment, 1);
        }
    }
}
</script>
