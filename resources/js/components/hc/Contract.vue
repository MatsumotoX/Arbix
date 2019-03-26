<template>
    <div>
        <div style="text-align: left; padding-top:20px;">
            <button class="eec_button" @click="isCreate = true" v-show="!isCreate">New</button>
            <button class="eec_button" @click="onSubmit" v-show="isCreate" :disabled="!addContract.value || !addContract.สัญญา || !addContract.ช่วงสัญญา">Save</button>
            <button class="eec_button" @click="isCreate = false" style="margin-left: 10px;" v-show="isCreate">Cancel</button>
        </div>
        <div v-if="isCreate" class="row">
            <div class="col-md-6 col-xl-3" style="padding-top: 10px">
                <eec-inputbox :value.sync="addContract.value" placeholder="Title" @input="addContract.value = $event" type="string"></eec-inputbox>
            </div>
            <div class="col-md-6 col-xl-3" style="padding-top: 10px">
                <eec-inputbox :value.sync="addContract.สัญญา" placeholder="สัญญา" @input="addContract.สัญญา = $event" type="file"></eec-inputbox>
            </div>
            <div class="col-md-6 col-xl-3" style="padding-top: 10px">
                <eec-inputbox :value.sync="addContract.range" placeholder="ช่วงสัญญา" @input="addContract.ช่วงสัญญา = $event" type="daterange"></eec-inputbox>
            </div>
        </div>

        <contract-grid ref="contractgrid" name="สัญญา" :data.sync="properties.data" :columns.sync="properties.column" :searchfield="searchfield"
        :allowpage="true" maindirectory="Hr" subdirectory="Customer" editmode="Batch"
        @getdata="getField" style="margin-top: 20px; margin-bottom: 20px;" :confirmdelete="false"></contract-grid>
    </div>
</template>

<script>

    export default {
        props: {
            id: {required: true},
            searchfield: {required: true},
        },

        data() {
            return {
                isCreate: false,
                addContract: new Form({
                    customer_id: this.id,
                    value: null,
                    สัญญา: null,
                    ช่วงสัญญา: null
                }),
                properties: {data:null}
            };
        },

        created() {
            this.getField();
        },

        methods: {
            getField: function () {
                return new Promise((resolve, reject) => {
                    axios.get('./getContract')
                        .then(response => {
                            this.properties = response.data.properties;
                            // console.log(this.properties);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            onSubmit: function () {
                return new Promise((resolve, reject) => {
                    this.addContract.post('./storeContract')
                        .then(response => {
                            console.log(response.data);
                            this.addContract.customer_id = this.id;
                            this.isCreate = false;
                            this.getField();
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            }
        },
        computed: {},

    }
</script>
