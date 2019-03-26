@extends('layouts.app') @section('title', 'View')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css"> <!-- CSS reset -->
@endsection

@section('header')
    Add @{{ view }}
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>@{{ mainDirectory }}  /</span>
    <span>@{{ subDirectory }}  /</span>
    <span style="color: #525252;">  View @{{ view }}</span>
@endsection

@section('content')

    <section id="formeec">
        <div class="cd-bubble">

            <div class="row">
                <div :class="(view == 'Customer')? 'col-md-5 col-xl-6':'col-md-6 col-xl-6'">
                    <eec-inputbox @change="recordSelect" :value.sync="info['identity']" :placeholder="recordIdentity"
                                  @input="info['identity'] = $event" type="select" :option="optionRecords"
                                  optiontext="identity" optionvalue="id" allowfilter="true" filtertype="contains"
                    ></eec-inputbox>
                </div>

                <div :class="(view == 'Customer')? 'col-md-5 col-xl-5':'col-md-6 col-xl-6'">
                    <eec-inputbox :value.sync="info['property']" placeholder="Property"
                                  @input="propertySelect" type="select" :option="optionProperties"
                                  optiontext="property" optionvalue="property" allowfilter="true" filtertype="contains"
                    ></eec-inputbox>
                </div>

                <div class="col-md-2 col-xl-1" v-if="view == 'Customer'">
                    <eec-button @click="onView" content="View" type="splitbutton" :items="buttonSearch"></eec-button>
                </div>
            </div>
        </div>

        <div class="cd-bubble">
            <div class="row">
                <div class="col-12">
                    <showgrid ref="propertyGrid" :data.sync="records[info['property']]" :searchfield="searchfield" @add="modalShow('modalAdd')"
                              :property="info['property']" :isfile="info['isFile']"></showgrid>
                </div>
            </div>
        </div>

        <sweet-modal ref="modalAdd">
            <h4 class="section-content-title align-center mbr-fonts-style display-5">
                Add / Edit @{{ info['property'] }}
            </h4>
            <hr>

            <form method="POST" action="/projects"
                  @submit.prevent="onSubmit('submitProperty')">
                <div class="row">
                    <div class="control col-12">
                        <eec-inputbox :value.sync="addProperty['ID']" type="disabled" placeholder="ID"
                                      :options="null"></eec-inputbox>
                    </div>
                    <div ref="edit" class="control col-12">
                        <component :is="inputbox" v-bind="inputboxProperties" @input="input" :value.sync="addProperty['value']"></component>
                        <span class="help is-danger" v-if="addProperty.errors.has('value')" v-text="addProperty.errors.get('value')"></span>
                    </div>
                    <div class="control col-12">
                        <input type="button" value="Add" class="eec_button" style="margin-top: 5px; font-size: 80%;"
                               @click="onSubmit('submitProperty')" :disabled="addProperty.errors.any()">
                        <input type="button" class="eec_button" value="Cancel" style="margin-top: 25px; margin-left: 20px; font-size: 80%;"
                               @click="modalHide('modalAdd')">
                    </div>
                </div>
            </form>
        </sweet-modal>

        @include('properties.modal_success')

    </section>
@endsection @section('script')

    <script src="/js/form/form.js"></script>
    <script type="text/javascript" src="/js/views/properties/show.js"></script>

    <script>

        // console.log(userProperties);
        new Vue({
            el: '#asset',

            data: {
                info: new Form({
                    identity: null,
                    property: null,
                    isFile: null,
                }),

                addProperty: new Form({
                    id: null,
                    ID: null,
                    value: null,
                    property: null,
                }),

                hasCreated: null,
                inputbox: false,
                property: '',
                records: '',
                buttonSearch: [{text: 'Export (Coming)'}],
                optionRecords,
                optionProperties,
                showLoader: true,
                searchfield: '',
                view,
                mainDirectory,
                subDirectory,
                recordIdentity,
                tableHeight: screen.height * 0.65,
            },
            computed: {
                inputboxProperties: function () {
                    return {
                        placeholder: this.property['name'],
                        type: this.property['type'],
                        option: this.property['option'],
                        optiontext: 'text',
                        optionvalue: 'value',
                        digit: this.property['digit'],
                        decimal: this.property['decimal'],
                    }
                }
            },
            watch: {
                addProperty: function (newValue) { // watch it
                    console.log('here');
                    this.addProperty.errors.clear();
                }
            },
            methods: {
                onSubmit(args) {
                    switch (args) {
                        case 'submitProperty':
                            this.$refs['modalLoader'].open();

                            return new Promise((resolve, reject) => {
                                this.addProperty.post('./addOrEdit')
                                    .then(response => {
                                        console.log(response);
                                        this.hasCreated = response.message.value;
                                        this.$refs['modalSuccess'].open();
                                        this.$refs['modalLoader'].close();
                                        this.$refs['modalAdd'].close();
                                        this.recordSelect({value: this.info['identity']});
                                        resolve(response.data);
                                    })
                                    .catch(error => {
                                        console.log(error.response);
                                        this.$refs['modalLoader'].close();
                                        reject(error.response);
                                    });
                            });
                    }
                },
                onView(args) {
                    switch (args) {
                        case 'View':
                            if (this.records.id) {
                                window.location = "/properties/hrs/customers/view?id=" + this.records.id;
                            }
                    }
                },
                recordSelect(args) {
                    if (args.value) {

                        return new Promise((resolve, reject) => {
                            axios.post('show', {id: args.value})
                                .then(response => {
                                    // console.log(response.data.data);
                                    this.records = response.data.data;
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    console.log(error.response.data);
                                    reject(error.response.data);
                                });
                        });
                    }
                },
                propertySelect(args) {
                    this.info['property'] = args;
                    return new Promise((resolve, reject) => {
                        axios.post('getProperty', {property: args})
                            .then(response => {
                                // console.log(response.data.data);
                                this.info['isFile'] = response.data.data;
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error.response.data);
                                reject(error.response.data);
                            });
                    });
                },
                modalShow(modal) {
                    switch (modal) {
                        case 'modalAdd':
                            if (this.info['property'] && this.info['identity']) {
                                this.inputbox = false;
                                return new Promise((resolve, reject) => {
                                    axios.post('edit', {property: this.info['property']})
                                        .then(response => {
                                            console.log(response.data.data);
                                            this.property = response.data.data;
                                            this.inputbox = 'eec-inputbox';

                                            this.addProperty['ID'] = this.records['value'];
                                            this.addProperty['id'] = this.records['id'];
                                            this.addProperty['property'] = this.info['property'];
                                            this.addProperty['value'] = null;
                                            this.$refs[modal].open();
                                            resolve(response.data);
                                        })
                                        .catch(error => {
                                            console.log(error.response.data);
                                            reject(error.response.data);
                                        });
                                });
                            }
                    }
                },
                modalHide(modal) {
                    this.$refs[modal].close();
                },
                input(args) {
                    this.addProperty['value'] = args;
                    this.addProperty.errors.clear();
                }
            },

        });
    </script>
@endsection