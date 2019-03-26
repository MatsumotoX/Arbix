<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="gridData" :allowSorting='true' :beforeBatchSave='updateData'
                  :actionComplete='updateData' :dataBound='dataBound'
                  :editSettings='editSettings' :allowGrouping='allowgroup' :toolbarClick='clickHandler' :allowExcelExport='allowexport' :allowPaging="allowpage"
                  :pageSettings='pageSettings'>
            <e-columns>
                <e-column field='id' headerText='ID' :isPrimaryKey='true' :visible="showId"></e-column>
                <e-column v-for="(column, index) in gridColumns" :key="index" :field="column.name"
                          :headerText='capFirstLetter(column.name)' :type="column.type" :format="formatOption(column.format)" :editType="column.edit"
                          :edit="editOption(column.format, column.option, column.decimal)" :allowEditing="checkEdit(column.name)"></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>

    import {Sort, Group, Search, Toolbar, Edit, ExcelExport, Page, Resize} from "@syncfusion/ej2-vue-grids";
    import {Query} from '@syncfusion/ej2-data';

    export default {
        data() {
            return {
                gridData: '',
                gridColumns: '',
                toolbar: [],
                searchOptions: {ignoreCase: true},
                pageSettings: {pageSizes: this.pagechooser, pageSize: this.pagesize},
                editSettings: {
                    showDeleteConfirmDialog: true,
                    showConfirmDialog: true,
                    allowEditing: this.allowedit,
                    allowAdding: this.allowadd,
                    allowDeleting: this.allowdelete,
                    mode: this.editmode,
                    template: function () {
                        return {
                            template: Vue.component('lineAction', {
                                template: `<div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <eec-inputbox :value.sync="data.type" placeholder="Type"
                              @input="typeSelected($event)" type="select" name="type"
                              :option="[{'text':'camera'}, {'text':'cameraRoll'}, {'text':'datetimepicker'}, {'text':'location'}, {'text':'message'}, {'text':'postback'}, {'text':'uri'}]" optionvalue='text'></eec-inputbox>
            </div>
            <div class="form-group col-md-6" v-show="false">
               <div class="e-float-input e-control-wrapper">
                    <input id="Type" name="type" v-model='data.type' type="text" disabled>
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Type">Type</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" v-if="showName">
               <div class="e-float-input e-control-wrapper">
                    <input id="Name" name="name" v-model='data.name' style="padding-left: 8px;" type="text" required\>
                    <span class="e-float-line"></span>
                    <label class="e-float-text e-label-top" for="Name">Name</label>
                </div>
            </div>
            <div class="form-group col-md-6" v-show="showLabel">
               <div class="e-float-input e-control-wrapper">
                    <input id="Label" name="label" v-model='data.label' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Label">Label</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" v-if="showText">
               <div class="e-float-input e-control-wrapper">
                    <input id="Text" name="text" v-model='data.text' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Text">Text</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" v-if="showData">
               <div class="e-float-input e-control-wrapper">
                    <input id="Data" name="data" v-model='data.data' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Data">Data</label>
                </div>
            </div>
            <div class="form-group col-md-6" v-if="showDisplayText">
               <div class="e-float-input e-control-wrapper">
                    <input id="DisplayText" name="displayText" v-model='data.displayText' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="DisplayText">DisplayText</label>
                </div>
            </div>
            <div class="form-group col-md-6" v-if="showUri">
               <div class="e-float-input e-control-wrapper">
                    <input id="Uri" name="uri" v-model='data.uri' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Uri">Uri</label>
                </div>
            </div>
            <div class="form-group col-md-6" v-if="showSpecial">
               <div class="e-float-input e-control-wrapper">
                    <input id="Special" name="special" v-model='data.special' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Special">Special</label>
                </div>
            </div>
            <div class="form-group col-md-6" v-if="showMode">
                <eec-inputbox :value.sync="data.mode" placeholder="Mode"
                              @input="modeSelected($event)" type="select" name="mode"
                              :option="[{'text':'Date (YYYY-MM-DD)', 'value':'date'}, {'text':'time (HH:MM)', 'value':'date'}, {'text':'datetime (YYYY-MM-DDTHH:MM)', 'value':'datetime'}]"></eec-inputbox>
            </div>
            <div class="form-group col-md-6" v-show="false">
               <div class="e-float-input e-control-wrapper">
                    <input id="Mode" name="mode" v-model='data.mode' type="text" disabled>
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Mode">Mode</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" v-if="showInitial">
               <div class="e-float-input e-control-wrapper">
                    <input id="Initial" name="initial" v-model='data.initial' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Initial">Initial</label>
                </div>
            </div>
            <div class="form-group col-md-6" v-if="showMax">
               <div class="e-float-input e-control-wrapper">
                    <input id="Max" name="max" v-model='data.max' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Max">Max</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" v-if="showMin">
               <div class="e-float-input e-control-wrapper">
                    <input id="Min" name="min" v-model='data.min' style="padding-left: 8px;" type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text" for="Min">Min</label>
                </div>
            </div>
        </div>
        </div>
        </div>`,
                                data() {
                                    return {
                                        data: {},
                                        showName: false,
                                        showLabel: false,
                                        showData: false,
                                        showDisplayText: false,
                                        showText: false,
                                        showUri: false,
                                        showMode: false,
                                        showInitial: false,
                                        showMax: false,
                                        showMin: false,
                                        showSpecial: false,
                                    }
                                },
                                mounted() {
                                    if (this.data.type){
                                        this.typeSelected(this.data.type);
                                    }
                                },
                                methods: {
                                    modeSelected: function (args) {
                                      this.data.mode = args;
                                    },
                                    typeSelected: function (args) { // watch it
                                        this.data.type = args;
                                        this.showName = true;
                                        this.showLabel = true;
                                        this.showData = false;
                                        this.showDisplayText = false;
                                        this.showText = false;
                                        this.showUri = false;
                                        this.showMode = false;
                                        this.showInitial = false;
                                        this.showMax = false;
                                        this.showMin = false;
                                        this.showSpecial = false;

                                        switch (args) {
                                            case 'postback':
                                                this.showData = true;
                                                this.showDisplayText = true;
                                                this.showSpecial = true;
                                                break;
                                            case 'message':
                                                this.showText = true;
                                                break;
                                            case 'uri':
                                                this.showUri = true;
                                                this.showSpecial = true;
                                                break;
                                            case 'datetimepicker':
                                                this.showData = true;
                                                this.showMode = true;
                                                this.showInitial = true;
                                                this.showMax = true;
                                                this.showMin = true;
                                                break;
                                        }
                                    }
                                },
                            })
                        }
                    }
                },
            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit, ExcelExport, Page, Resize]
        },
        props: {
            data: {required: true},
            columns: {required: true},
            name: {required: true},
            maindirectory: {required: true},
            subdirectory: {required: true},

            // Optional
            showId: {default: false},
            allowadd: {default: false},
            allowedit: {default: false},
            allowdelete: {default: false},
            editmode: {default: 'Dialog'},
            routePost: {default: '/grids/dispatcher'},
            routeGet: {default: '/grids/getData'},
            allowexport: {default: false},
            allowpage: {default: false},
            allowgroup: {default: true},
            pagesize: {default: 10},
            pagechooser: {default: false},
            searchfield: {default: ''},
        },

        created() {
            this.getToolbar();
        },

        mounted() {
            this.gridData = this.data;
            this.gridColumns = this.columns;
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            },
            data: function (newVal) {
                this.gridData = newVal;
            },
            columns: function (newVal) {
                this.gridColumns = newVal;
            }
        },

        methods: {
            dataBound: function() {
                this.$refs.grid.autoFitColumns();
            },
            clickHandler: function (args) {
                switch (args.item.text) {
                    case 'CSV Export':
                        this.$refs.grid.csvExport({fileName: 'EECL_' + this.name + '_' + new Date().toISOString() + '.csv'});
                        break;
                    default:
                        break;
                }
            },
            updateData: function (args) {
                let data;
                switch (args.requestType) {
                    case  'save':
                    case 'delete':
                        data = true;
                        args = {requestType: args.requestType, data: args.data};
                        break;
                    default:
                        switch (args.name) {
                            case 'beforeBatchSave':
                                data = true;
                                break;
                        }
                }
                if (data) {
                    delete args.data['null'];

                    console.log(args);
                    return new Promise((resolve, reject) => {
                        axios['post'](this.routePost, args, {
                            params: {
                                type: this.editmode,
                                modelName: this.name,
                                mainDirectory: this.maindirectory,
                                subDirectory: this.subdirectory
                            }
                        })
                            .then(response => {
                                console.log(response.data.data);
                                this.getData();
                                this.$emit('getdata');
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                alert('Fail: Tell worakorn.');
                                reject(error.response);
                            });
                    });
                }
            },
            getData: function () {
                return new Promise((resolve, reject) => {
                    axios['get'](this.routeGet, {
                        params: {
                            modelName: this.name,
                            mainDirectory: this.maindirectory,
                            subDirectory: this.subdirectory
                        }
                    })
                        .then(response => {
                            this.gridData = response.data.data;
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            alert('Fail: Tell worakorn.');
                            reject(error.response);
                        });
                });
            },
            formatOption: function (type) {
                switch (type) {
                    case 'date':
                        return 'yMd';
                    case 'currency':
                        return '฿ ,###.00';
                    case 'percentage':
                        return 'p2';
                    case 'integer':
                        return ',###';
                    case 'decimal':
                        return ',###.########';
                }
            },
            editOption: function (type, option = null, decimal = null) {
                switch (type) {
                    case 'dropdown':
                        let groupOptions = {
                            params: {
                                dataSource: option,
                                fields: {value: 'value', text: 'text'},
                                query: new Query(),
                                actionComplete: () => false
                            }
                        };
                        return groupOptions;
                    case 'currency':
                    case 'decimal':
                    case 'integer':
                        let numericParams = {
                            params: {
                                decimals: decimal,
                                validateDecimalOnType: "true"
                            }
                        };
                        return numericParams;
                    case 'percentage':
                        let percentageParams = {
                            params: {
                                decimals: decimal,
                                step: '0.01',
                                validateDecimalOnType: "true"
                            }
                        };
                        return percentageParams;
                }

            },
            getToolbar: function () {
                if (this.allowadd) {
                    this.toolbar.push('Add');
                }
                if (this.allowedit) {
                    this.toolbar.push('Edit');
                }
                if (this.allowdelete) {
                    this.toolbar.push('Delete');
                }
                if (this.editmode == 'Batch') {
                    if (this.allowadd || this.allowedit || this.allowdelete) {
                        this.toolbar.push('Update');
                        this.toolbar.push('Cancel');
                    } else {
                        this.toolbar = false;
                    }
                }
            },
            capFirstLetter: function (string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            checkEdit: function (columnName) {
                return false;
            }

        }
    }
</script>

<style>
    .form-group.col-md-6 {
        width: 250px;
        height: 54px;
    }

    .form-group.col-md-12 {
        height: 54px;
    }
</style>
