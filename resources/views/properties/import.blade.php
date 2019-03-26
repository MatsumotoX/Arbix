@extends('layouts.app') @section('title', 'Assets')

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    Import @{{ view }}s
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>@{{ mainDirectory }}  /</span>
    <span>@{{ subDirectory }}  /</span>
    <span style="color: #525252;">  Import @{{ view }}s</span>
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble">
            <div class="row">
                <div class="col-10">
                    <form method="POST" action="/projects"
                          @submit.prevent="onSubmit('checkImport')"
                          @mouseenter="importRecords.errors.clear('file')">
                        <eec-inputbox :value.sync="importRecords['file']" placeholder="Choose File"
                                      @input="importRecords['file'] = $event" type="file"></eec-inputbox>
                        <span class="help is-danger" v-if="importRecords.errors.has('file')" v-text="importRecords.errors.get('file')"></span>

                    </form>
                </div>
                <div class="col-2">

                    <input type="button" value="Check" class="cd-btn cd-full"
                           @click="onSubmit('checkImport')">
                </div>
            </div>
        </div>
        <div class="cd-bubble">

        </div>
    </section>

    <sweet-modal ref="selectImport">
        <h4 class="section-content-title align-center mbr-fonts-style display-5">
            Select Property(s) to Import:
        </h4>
        <hr>
        <div class="col-12">
            <div v-for="(chips, group) in properties" :key="group">
                <h4 class="section-content-title align-left mbr-fonts-style display-5">
                    @{{ group }}
                </h4>
                <eec-chip :chips="chips" selection="Multiple" @input="onInput($event, group)"></eec-chip>
                <hr>
            </div>

            <input type="button" class="eec_button" value="Cancel" style="margin-top: 5px; font-size: 80%;"
                   @click="modalHide('selectImport')">
            <input type="button" value="Next" class="eec_button" style="margin-top: 25px; margin-left: 20px; font-size: 80%;"
                   @click="onSubmit('submitProperty')" :disabled="!dump">
        </div>
    </sweet-modal>

    <sweet-modal ref="selectFormat">
        <h4 class="section-content-title align-center mbr-fonts-style display-5">
            Select Date Format:
        </h4>
        <hr>
        <div class="col-12">
            <eec-chip :chips="format" selection="Single" @input="onInputDate($event)"></eec-chip>
            <hr>
            <input type="button" value="Back" class="eec_button" style="margin-top: 5px; font-size: 80%;"
                   @click="onBack('selectFormat', 'selectImport')">
            <input type="button" value="Next" class="eec_button" style="margin-top: 25px; margin-left: 20px; font-size: 80%;"
                   @click="onSubmit('submitFormat')" :disabled="!dateformat">
        </div>
    </sweet-modal>

    <sweet-modal ref="confirmImport">
        <h4 class="section-content-title align-center mbr-fonts-style display-5">
            Confirming Import:
        </h4>
        <hr>
        <div v-for="(group, groupName) in selected" :key="groupName" class="col-12" style="text-align: left; font-size: 80%;">
            <span>@{{ groupName }}: </span>
            {{--<eec-chip :chips.sync="group" selection="None" @input="onInput($event, group)"></eec-chip>--}}
            <ejs-chiplist>
                <e-chips>
                    <e-chip v-for="(property, propertyKey) in group" :key="propertyKey" :text="property"></e-chip>
                </e-chips>
            </ejs-chiplist>
        </div>
        <div class="col-12">
            <hr>
            <p style="text-align: left; font-size: 80%;">No of @{{ view }}(s) to be checked: @{{ data.length }}</p>
            <input type="button" value="Back" class="eec_button" style="margin-top: 5px; font-size: 80%;"
                   @click="(isDate) ? onBack('confirmImport', 'selectFormat'): onBack('confirmImport', 'selectImport')">
            <input type="button" value="Confirm" class="eec_button" style="margin-top: 25px; margin-left: 20px; font-size: 80%;"
                   @click="onSubmit('confirmImport')">
        </div>
    </sweet-modal>

    @include('properties.modal_success')

@endsection

@section('script')

    <script src="/js/form/form.js"></script>
    <script src="/js/views/properties/create.js"></script>
    <script src="/js/tabs.js"></script>

    <script>

        new Vue({
            el: '#asset',

            data: {
                importRecords: new Form({
                    file: null
                }),
                properties: '',
                selected: {},
                dateformat: '',
                dump: '',
                data: '',
                dateProperty: '',
                hasCreated: '',
                view,
                mainDirectory,
                subDirectory,
                format: ['Y-m-d', 'Y-d-m', 'd-m-Y', 'm-d-Y', 'Y/m/d', 'Y/d/m', 'd/m/Y', 'm/d/Y'],
            },

            methods: {
                onSubmit(args) {
                    switch (args) {
                        case 'checkImport':
                            this.importRecords.post('checkimport')
                                .then(response => {
                                    this.properties = response.properties;
                                    this.data = response.data;
                                    this.modalShow('selectImport');
                                    console.log(response)
                                })
                                .catch(response => {
                                    console.log(response.message);
                                    // if (response.message == 'No ID founded'){
                                    //     this.importRecords.errors.ad('file', 'test');
                                    // }
                                });
                            break;
                        case 'submitProperty':
                            return new Promise((resolve, reject) => {
                                axios.post('checkdate', {properties: this.selected})
                                    .then(response => {
                                        if (response.data.date.length > 0) {
                                            this.onNext('selectImport', 'selectFormat');
                                            this.isDate = true;
                                            this.dateProperty = response.data.date;
                                        } else {
                                            this.onNext('selectImport', 'confirmImport');
                                            this.isDate = false;
                                            this.dateProperty = '';
                                        }
                                        // console.log(response.data.data);
                                        resolve(response.data);
                                    })
                                    .catch(error => {
                                        console.log(error.response.data);
                                        reject(error.response.data);
                                    });
                            });
                        case 'submitFormat':
                            this.onNext('selectFormat', 'confirmImport');
                            break;
                        case 'confirmImport':
                            this.$refs['modalImportLoader'].open();

                            return new Promise((resolve, reject) => {
                                axios.post('import', {properties: this.selected, formats: this.dateformat, data: this.data, date: this.dateProperty})
                                    .then(response => {
                                        this.$refs['modalImportSuccess'].open();
                                        this.$refs['modalImportLoader'].close();
                                        this.$refs['confirmImport'].close();
                                        console.log(response.data.message);
                                        resolve(response.data);
                                    })
                                    .catch(error => {
                                        console.log(error.response.data);
                                        reject(error.response.data);
                                    });
                            });
                    }
                },
                onInput(args, group) {
                    if (args) {
                        this.selected[group] = args;
                    } else {
                        delete this.selected[group];
                    }
                    if (Object.keys(this.selected).length > 0) {
                        this.onDump(Date());
                    } else {
                        this.onDump(null);
                    }
                },
                onDump(args) {
                    this.dump = args;
                },
                onInputDate(args) {
                    this.dateformat = args;
                },
                onBack(currentModal, backModal) {
                    this.modalShow(backModal);
                    this.modalHide(currentModal);
                },
                onNext(currentModal, nextModal) {
                    this.modalShow(nextModal);
                    this.modalHide(currentModal);
                },
                modalShow(args) {
                    switch (args) {
                        case 'selectImport':
                            this.$refs[args].open();
                            break;
                        case 'modalAdd':
                            if (this.info['property'] && this.info['identity']) {
                                this.inputbox = false;
                                return new Promise((resolve, reject) => {
                                    axios.post('edit', {property: this.info['property']})
                                        .then(response => {
                                            // console.log(response.data.data);
                                            this.property = response.data.data;
                                            this.inputbox = 'eec-inputbox';

                                            this.addProperty['vehicleId'] = this.vehicle['value'];
                                            this.addProperty['id'] = this.vehicle['id'];
                                            this.addProperty['property'] = this.info['property'];
                                            this.addProperty['value'] = null;
                                            this.$refs[args].open();
                                            resolve(response.data);
                                        })
                                        .catch(error => {
                                            console.log(error.response.data);
                                            reject(error.response.data);
                                        });
                                });
                            }
                            break;
                        default:
                            this.$refs[args].open();
                    }
                },
                modalHide(modal) {
                    this.$refs[modal].close();
                },
            },
        });
    </script>
@endsection