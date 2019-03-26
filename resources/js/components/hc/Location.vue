<template>
    <div>
        <div style="text-align: left; padding-top:20px;">
            <button class="eec_button" @click="isCreate = true" v-show="!isCreate">New</button>
            <button class="eec_button" @click="onSubmit" v-show="isCreate" :disabled="!addLocation.value">Save</button>
            <button class="eec_button" @click="isCreate = false" style="margin-left: 10px;" v-show="isCreate">Cancel</button>
        </div>
        <div v-if="isCreate" class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 col-xl-3" style="padding-top: 10px">
                        <eec-inputbox :value.sync="findPlace" placeholder="Place Name / Address" @input="findPlace = $event" type="string"></eec-inputbox>
                        <span class="help is-danger" v-if="placeNotFound" v-text="placeNotFound"></span>
                    </div>
                    <div class="col-md-2 col-xl-1" style="padding-top: 30px">
                        <button class="eec_button" @click="onSearch" v-show="isCreate" :disabled="!findPlace">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isCreate && placeFound" class="row">
            <div class="col-12">
                <hr>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div v-for="(field, fieldName) in properties.column" :key="fieldName" class="col-md-6 col-xl-3" style="padding-top: 10px">
                        <eec-inputbox :value.sync="addLocation[field['name']]" :placeholder="(field['name'] == 'value')? 'ชื่อ' : field['name']" allowfilter="true"
                                      filtertype="contains"
                                      @input="addLocation[field['name']] = $event" :type="field['type']" :option="field['option']"
                        ></eec-inputbox>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <iframe width="600" height="450" frameborder="0" style="border:0" :src="addLocation['embed']" allowfullscreen></iframe>
            </div>
        </div>

        <allpurpose-grid name="ที่ตั้ง" :data.sync="properties.data" :columns.sync="properties.column" :searchfield="searchfield"
        :allowdelete="true" :allowedit="true" :allowpage="true" maindirectory="Hr" subdirectory="Customer" editmode="Batch"
        @getdata="getField" style="margin-top: 20px; margin-bottom: 20px;" :confirmdelete="false"></allpurpose-grid>
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
                placeFound: false,
                placeNotFound: null,
                addLocation: new Form({
                    customer_id: this.id,
                    embed: null,
                }),
                findPlace: null,
                properties: {data: null},
            };
        },

        created() {
            this.getField();
        },

        watch: {
            findPlace: function () {
                this.placeNotFound = null;
            }
        },

        methods: {
            getField: function () {
                return new Promise((resolve, reject) => {
                    axios.get('./getLocation', {
                            params: {
                                id: this.id
                            }
                        })
                        .then(response => {
                            // console.log(response);
                            this.properties = response.data.properties;
                            // // console.log(this.properties);
                            let fields = response.data.fields;
                            this.addLocation.addfield(fields);
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
                    this.addLocation.post('./storeLocation')
                        .then(response => {
                            console.log(response.data);
                            this.isCreate = false;
                            this.getField();
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            onSearch: function () {
                return new Promise((resolve, reject) => {
                    axios['post']('/googles/maps/dispatcher', {input: this.findPlace}, {
                        params: {
                            method: 'findPlace',
                        }
                    })
                        .then(response => {
                            if (response.data.data.status != 'ZERO_RESULTS') {
                                let placeId = response.data.data.candidates[0].place_id;
                                this.addLocation.placeId = placeId;
                                this.placeDetail(placeId);
                            } else {
                                this.placeNotFound = 'Place cannot be found';
                                this.placeFound = false;
                            }
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            placeDetail: function (args) {
                return new Promise((resolve, reject) => {
                    axios['post']('/googles/maps/dispatcher', {placeId: args}, {
                        params: {
                            method: 'placeDetail',
                        }
                    })
                        .then(response => {
                            // console.log(response);
                            let detail = response.data.data;
                            this.addLocation.value = detail['name'];
                            this.addLocation.latitude = detail['location']['latitude'];
                            this.addLocation.longitude = detail['location']['longitude'];
                            this.addLocation.address = detail['address']['address'];
                            this.addLocation.ตำบล = detail['address']['ตำบล'];
                            this.addLocation.อำเภอ = detail['address']['อำเภอ'];
                            this.addLocation.จังหวัด = detail['address']['จังหวัด'];
                            this.addLocation.ประเทศ = detail['address']['ประเทศ'];
                            this.addLocation.รหัสไปรษณีย์ = detail['address']['รหัสไปรษณีย์'];
                            this.addLocation.url = detail['url'];
                            this.addLocation.embed = detail['embed'];
                            this.addLocation.billing_address = 0;
                            this.placeFound = true;
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            }
        },

    }
</script>

