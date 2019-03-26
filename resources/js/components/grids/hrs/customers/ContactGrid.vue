<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="gridData" :allowSorting='true' :beforeBatchSave='updateData'
                  :actionComplete='updateData' :dataBound='dataBound'
                  :editSettings='editSettings' :allowGrouping='allowgroup' :toolbarClick='clickHandler' :allowExcelExport='allowexport' :allowPaging="allowpage"
                  :pageSettings='pageSettings'>
            <e-columns>
                <e-column field='id' headerText='ID' :isPrimaryKey='true' :visible="showId"></e-column>
                <e-column v-if="column.type != 'file'" v-for="(column, index) in gridColumns" :key="index" :field="column.name"
                          :headerText='capFirstLetter(column.name)' :type="column.type" :format="formatOption(column.format)" :editType="column.edit"
                          :edit="editOption(column.format, column.option, column.decimal)" width="150"></e-column>
                <e-column v-else headerText='นามบัตร' :template='viewFile' :allowEditing='false'></e-column>
            </e-columns>
        </ejs-grid>
        <sweet-modal ref="namecard">
            <div style="z-index: 9999999999999;">
                <h4 class="section-content-title align-center mbr-fonts-style display-5">
                    Add Name Card
                </h4>
                <hr>
                <div class="row">
                    <div class="control col-12">
                        <eec-inputbox :value.sync="addNameCard['นามบัตร']" type="file" @input="addNameCard['นามบัตร'] = $event" placeholder="นามบัตร"></eec-inputbox>
                    </div>
                </div>
                <div class="control">
                    <button class="eec_button" @click="onSubmitAdd()" style="margin-top: 5px; font-size: 80%;">Add</button>
                    <button class="eec_button" @click="modalHide('namecard')" style="margin-top: 25px; margin-left: 20px; font-size: 80%;">Cancel</button>
                </div>
            </div>
        </sweet-modal>
    </div>
</template>
<script>

    var bus = new Vue({});

    import {Sort, Group, Search, Toolbar, Edit, ExcelExport, Page, Resize} from "@syncfusion/ej2-vue-grids";
    import {Query} from '@syncfusion/ej2-data';

    export default {
        data() {
            return {
                gridData: '',
                gridColumns: '',
                toolbar: [],
                searchOptions: {ignoreCase: true},
                editSettings: {
                    showDeleteConfirmDialog: this.confirmdelete,
                    showConfirmDialog: true,
                    allowEditing: this.allowedit,
                    allowAdding: this.allowadd,
                    allowDeleting: this.allowdelete,
                    mode: this.editmode
                },
                pageSettings: {pageSizes: this.pagechooser, pageSize: this.pagesize},
                addNameCard: new Form({
                    id: null,
                    นามบัตร: null
                }),
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
            confirmdelete: {default: true},
            routePost: {default: '/grids/dispatcher'},
            routeGet: {default: './getContact'},
            allowexport: {default: false},
            allowpage: {default: false},
            allowgroup: {default: true},
            allowtoolbar: {default: true},
            autofit: {default: true},
            pagesize: {default: 10},
            pagechooser: {default: false},
            searchfield: {default: ''},
        },

        created()
        {
            this.getToolbar();
        },

        mounted() {
            this.gridData = this.data;
            this.gridColumns = this.columns;
            bus.$on('addnamecard', function (id) {
                vm.$refs.contact.$refs.contactgrid.addNameCard.id = id;
                vm.$refs.contact.$refs.contactgrid.modalShow('namecard');
            })
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
            },
        },

        methods: {
            modalShow(modal) {
                this.$refs[modal].open();
            },
            modalHide(modal) {
                this.$refs[modal].close();
            },
            onSubmitAdd() {
                return new Promise((resolve, reject) => {
                    this.addNameCard.post('./storeNameCard')
                        .then(response => {
                            this.getData();
                            this.$emit('getdata');
                            this.modalHide('namecard');
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            alert('Fail: Tell worakorn.');
                            reject(error.response);
                        });
                });
            },
            dataBound: function () {
                if (this.autofit) {
                    this.$refs.grid.autoFitColumns();
                } else {
                    return false;
                }
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
                    // console.log(args);
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
                                // console.log(response.data.data);
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
                        axios['get'](this.routeGet)
                            .then(response => {
                                this.gridData = response.data.properties;
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                alert('Fail: Tell worakorn.');
                                reject(error.response);
                            });
                    }
                )
                    ;
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
            }
            ,
            editOption: function (type, option = null, decimal = null) {
                switch (type) {
                    case 'dropdown':
                        let field = {value: 'value', text: 'text'};

                        if (option.length > 0) {
                            if ("group" in option[0]) {
                                field = {value: 'value', text: 'text', groupBy: 'group'};
                            }
                        }

                        let groupOptions = {
                            params: {
                                dataSource: option,
                                fields: field,
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

            }
            ,
            getToolbar: function () {
                if (this.allowtoolbar) {
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
                } else {
                    this.toolbar = false;
                }
            }
            ,
            capFirstLetter: function (string) {
                if (string == 'value') {
                    string = 'ชื่อ';
                    return string.charAt(0).toUpperCase() + string.slice(1);
                } else {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }
            }
            ,

            viewFile: function () {
                return {
                    template: Vue.component('viewFileTemplate', {
                        template: `<div>
                    <div v-if="hasFile">
                        <a :href="'/viewfile/' + this.data.นามบัตร" target="_blank">View</a> | <a :href="'/downloadfile/' + this.data.นามบัตร" target="_blank">Download</a>
                    </div>
                    <div v-else>
                        <a @click.prevent="onAdd" href="#">Add</a>
                    </div>
                </div>`,
                        data: function () {
                            return {
                                data: {},
                                hasFile: false,
                            }
                        },
                        mounted() {
                            if (this.data.นามบัตร) {
                                this.hasFile = true;
                            }
                        },
                        methods: {
                            onAdd: function () {
                                bus.$emit('addnamecard', this.data.id);
                            },
                        }
                    })
                }
            }
            ,

        }
    }
</script>
