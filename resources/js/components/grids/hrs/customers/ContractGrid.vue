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
                <e-column v-else headerText='สัญญา' :template='viewFile' :allowEditing='false'></e-column>
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
                editSettings: {
                    showDeleteConfirmDialog: this.confirmdelete,
                    showConfirmDialog: true,
                    allowEditing: this.allowedit,
                    allowAdding: this.allowadd,
                    allowDeleting: this.allowdelete,
                    mode: this.editmode
                },
                pageSettings: {pageSizes: this.pagechooser, pageSize: this.pagesize},
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
            },
        },

        methods: {
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
                    string = 'title';
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
                        <a :href="'/viewfile/' + this.data.สัญญา" target="_blank">View</a> | <a :href="'/downloadfile/' + this.data.สัญญา" target="_blank">Download</a>
                </div>`,
                        data: function () {
                            return {
                                data: {},
                            }
                        },
                    })
                }
            }
            ,

        }
    }
</script>
