<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="gridData" :allowSorting='true' :beforeBatchSave='updateData'
                  :actionComplete='updateData'
                  :editSettings='editSettings' :allowGrouping='allowgroup' :toolbarClick='clickHandler' :allowExcelExport='allowexport' :allowPaging="allowpage"
                  :pageSettings='pageSettings'>
            <e-columns>
                <e-column field='id' headerText='ID' :isPrimaryKey='true' :visible="showId"></e-column>
                <e-column v-for="(column, index) in gridColumns" :key="index" :field="column.name"
                          :headerText='capFirstLetter(column.name)' :type="column.type" :format="formatOption(column.format)" :editType="column.edit"
                          :edit="editOption(column.format, column.option, column.decimal)" width="150" :allowEditing="checkEdit(column.name)"></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>

    import {Sort, Group, Search, Toolbar, Edit, ExcelExport, Page} from "@syncfusion/ej2-vue-grids";
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
               <div class="e-float-input e-control-wrapper">
                    <input id="Name" name="name" v-model='data.name' type="text" required\>
                    <span class="e-float-line"></span>
                    <label class="e-float-text e-label-top" for="Name">Name</label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <eec-inputbox :value.sync="data.content_id" placeholder="Content" allowfilter="true" filtertype="contains"
                              @input="contentSelected($event)" type="multiselect" :option="gridcomponent.column[2].option" optiongroup="group"
                              ></eec-inputbox>
            </div>
            <div class="form-group col-md-12" v-show="false">
               <div class="e-float-input e-control-wrapper">
                    <input id="content_id" name="content_id" v-model='data.content_id' type="text">
                    <span class="e-float-line"></span>
                    <label class="e-float-text e-label-top" for="content_id">content_id</label>
                </div>
            </div>
        </div>
        </div>`,
                                data() {
                                    return {
                                        data: {},
                                        gridcomponent: '',
                                    }
                                },

                                created() {
                                    this.gridcomponent = component;
                                    axios['get']('./getData')
                                        .then(response => {
                                            this.gridcomponent = response.data.component;
                                        })
                                        .catch(error => {
                                            console.log(error);
                                        });
                                },

                                methods: {
                                    contentSelected: function (args) {
                                        this.data.content_id = args;
                                    },
                                }
                            })
                        }
                    }
                },
            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit, ExcelExport, Page]
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
                        let contents = args.data['content']['id'].split(",");
                        let content_id = [];

                        contents.forEach(function (content) {
                            content_id.push({'model': content.split('_')[0], 'id': content.split('_')[1]});
                        });

                        args.data['content_id'] = JSON.stringify(content_id);
                        delete args.data['null'];
                        delete args.data['content'];
                        delete args.data['ej2'];
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
        width: 500px;
        height: 54px;
    }
</style>
