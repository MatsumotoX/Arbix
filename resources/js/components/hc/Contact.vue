<template>
    <div>
        <div style="text-align: left; padding-top:20px;">
            <button class="eec_button" @click="isCreate = true" v-show="!isCreate">New</button>
            <button class="eec_button" @click="onSubmit" v-show="isCreate" :disabled="!addContact.value">Save</button>
            <button class="eec_button" @click="isCreate = false" style="margin-left: 10px;" v-show="isCreate">Cancel</button>
        </div>
        <div v-if="isCreate" class="row">
            <div v-for="(field, fieldName) in properties.column" :key="fieldName" class="col-md-6 col-xl-3" style="padding-top: 10px">
                <eec-inputbox :value.sync="addContact[field['name']]" :placeholder="(field['name'] == 'value')? 'ชื่อ' : field['name']" allowfilter="true"
                              filtertype="contains"
                              @input="addContact[field['name']] = $event" :type="field['type']" :option="field['options']"
                ></eec-inputbox>
            </div>
        </div>

        <contact-grid ref="contactgrid" name="ผู้ติดต่อ" :data.sync="properties.data" :columns.sync="properties.column" :searchfield="searchfield"
                         :allowdelete="true" :allowedit="true" :allowpage="true" maindirectory="Hr" subdirectory="Customer" editmode="Batch"
                         @getdata="getField" style="margin-top: 20px; margin-bottom: 20px;" :confirmdelete="false"></contact-grid>
    </div>
</template>

<script>

    export default {
        props: {
            id: {required: true},
            searchfield: {required: true},
        },

        data()
        {
            return {
                isCreate: false,
                addContact: new Form({
                    customer_id: this.id,
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
                    axios.get('./getContact')
                        .then(response => {
                            this.properties = response.data.properties;
                            // console.log(this.properties);
                            let fields = response.data.fields;
                            this.addContact.addfield(fields);
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
                    this.addContact.post('./storeContact')
                        .then(response => {
                            // console.log(response.data);
                            this.addContact.customer_id = this.id;
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
