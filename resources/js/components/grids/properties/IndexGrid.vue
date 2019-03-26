<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="data" :allowSorting='true' :beforeBatchSave='updateData'
                  :editSettings='editSettings' :allowGrouping='true' :toolbarClick='clickHandler' allowExcelExport='true' :allowPaging="true"
                  :pageSettings='pageSettings' :dataBound='dataBound' :height="tableHeight">
            <e-columns>
                <e-column field='id' headerText='Index' :visible="false"></e-column>
                <e-column field='value' headerText='ID' width="150" :isPrimaryKey='true' :allowEditing="false"></e-column>
                <e-column v-if="isShow[property.group]" v-for="(property, index) in userproperties" :key="index" :field="property.name"
                          :headerText='property.name' :type="property.type" :format="formatOption(property.format)" :editType="property.edit"
                          :edit="editOption(property.format, property.option, property.decimal)"></e-column>
                <!--<e-column v-if="isShow[property.group] && property['type'] != 'file'" v-for="(property, index) in userproperties" :key="index" :field="property.name"-->
                <!--:headerText='property.name' :type="property.type" :format="formatOption(property.format)" :editType="property.edit"-->
                <!--:edit="editOption(property.format, property.option, property.decimal)" width="150"></e-column>-->
                <!--<e-column v-else-if="property['type'] == 'file'" :field="property.name" :headerText='property.name' width='150' :template='viewFile'></e-column>-->
            </e-columns>
            <e-aggregates>
                <e-aggregate v-if="agsum">
                    <e-columns>
                        <e-column v-for="(property, index) in userproperties" :key="index"
                                  v-if="property.format == 'currency' || property.format == 'integer' || property.format == 'decimal' || property.format == 'percentage'"
                                  type="Sum" :field="property.name" :format="formatOption(property.format)" :groupFooterTemplate='footerSum'></e-column>
                    </e-columns>
                </e-aggregate>
                <e-aggregate v-if="agavg">
                    <e-columns>
                        <e-column v-for="(property, index) in userproperties" :key="index"
                                  v-if="property.format == 'currency' || property.format == 'integer' || property.format == 'decimal' || property.format == 'percentage'"
                                  type="Average" :field="property.name" :format="formatOption(property.format)" :groupCaptionTemplate='footerAvg'></e-column>
                    </e-columns>
                </e-aggregate>
                <e-aggregate v-if="agsum">
                    <e-columns>
                        <e-column v-for="(property, index) in userproperties" :key="index"
                                  v-if="property.format == 'currency' || property.format == 'integer' || property.format == 'decimal'" type="Sum" :field="property.name"
                                  :format="formatOption(property.format)" :footerTemplate='footerSum'></e-column>
                    </e-columns>
                </e-aggregate>
                <e-aggregate v-if="agavg">
                    <e-columns>
                        <e-column v-for="(property, index) in userproperties" :key="index"
                                  v-if="property.format == 'currency' || property.format == 'integer' || property.format == 'decimal' || property.format == 'percentage'"
                                  type="Average" :field="property.name" :format="formatOption(property.format)" :footerTemplate='footerAvg'></e-column>
                    </e-columns>
                </e-aggregate>
            </e-aggregates>
        </ejs-grid>
    </div>
</template>
<script>

    let customtoolbar = ['Update', 'Cancel', {type: 'Separator'}, { text: 'View', tooltipText: "View details", prefixIcon: 'e-search-icon', id: 'view' }, {
        text: 'Sum / Average',
        tooltipText: 'Sum / Average',
    }, {type: 'Separator'}, 'CsvExport'];
    let isShow = {};

    groups.forEach(group => {
        customtoolbar.push({text: group.name, tooltipText: 'Click to hide ' + group.name, align: 'Right', htmlAttributes: {'class': 'untoggled'}});
        isShow[group.name] = true;
    });

    customtoolbar.push({text: 'Hide All', tooltipText: 'Click to hide all groups', align: 'Right', htmlAttributes: {'class': 'untoggled'}});

    import {Sort, Group, Search, Toolbar, Edit, ExcelExport, Aggregate, Page, Resize} from "@syncfusion/ej2-vue-grids";
    import {Query} from '@syncfusion/ej2-data';


    export default {
        data() {
            return {
                tableHeight: screen.height * 0.50,
                agsum: false,
                agavg: false,
                isShow: isShow,
                toolbar: customtoolbar,
                searchOptions: {ignoreCase: true},
                editSettings: {allowEditing: true, allowAdding: true, allowDeleting: true, mode: 'Batch'},
                pageSettings: {pageSizes: true, pageSize: 20},
                footerSum: function () {
                    return {
                        template: Vue.component('sumTemplate', {
                            template: `<span>Sum: {{data.Sum}}</span>`,
                            data() {
                                return {data: {}};
                            }
                        })
                    }
                },
                footerAvg: function () {
                    return {
                        template: Vue.component('maxTemplate', {
                            template: `<span>Average: {{data.Average}}</span>`,
                            data() {
                                return {data: {}};
                            }
                        })
                    }
                },

            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit, ExcelExport, Aggregate, Page, Resize]
        },
        props: {
            data: {required: true},
            userproperties: {required: true},
            groups: {required: true},
            searchfield: '',
            view: '',
            autofit: {default: true},
            allowview: {default: false},
        },

        mounted() {
            this.setView(this.allowview);
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            }
        },

        computed: {
            isVisible: function () {
                console.log(this.isShow);
                return this.isShow;
            }
        },

        methods: {
            dataBound: function () {
                if (this.autofit) {
                    this.$refs.grid.autoFitColumns();
                } else {
                    return false;
                }
            },
            viewFile: function () {
                return {
                    template: Vue.component('viewFileTemplate', {
                        template: `<div>
                    <a :href="this.data.รูป">View</a>
                </div>`,
                        data: function () {
                            return {
                                data: {}
                            }
                        },
                        props: {
                            field: {required: false},
                        },
                        mounted() {
                            console.log(this);
                        }
                    })
                }
            },
            clickHandler: function (args) {
                switch (args.item.text) {
                    case 'CSV Export':
                        this.$refs.grid.csvExport({fileName: 'EECL_' + this.view + '_' + new Date().toISOString() + '.csv'});
                        break;
                    case 'Edit':
                    case 'Update':
                    case 'Cancel':
                        break;
                    case 'Sum / Average':
                        if (this.agavg && this.agsum) {
                            this.agavg = this.agsum = false;
                        } else {
                            if (!this.agavg && this.agsum) {
                                this.agavg = true
                            } else {
                                this.agsum = true
                            }
                        }
                        break;
                    case 'Hide All':
                        args.item.tooltipText = 'Click to show all groups';
                        this.groups.forEach(group => {
                            this.isShow[group.name] = false;
                        });
                        args.item.text = 'Show All';
                        let i = this.toolbar.length - 1;
                        let j = 6;
                        while (j < i) {
                           this.toolbar[j].htmlAttributes.class = 'toggled';
                           j++;
                        }
                        break;

                    case 'Show All':
                        args.item.tooltipText = 'Click to hide all groups';
                        this.groups.forEach(group => {
                            this.isShow[group.name] = true;
                        });
                        args.item.text = 'Hide All';
                        let x = this.toolbar.length - 1;
                        let y = 6;
                        while (y < x) {
                            this.toolbar[y].htmlAttributes.class = 'untoggled';
                            y++;
                        }
                        break;

                    case 'View':
                        let selectedrecords = this.$refs.grid.getSelectedRecords();  // Get the selected records.
                        window.location.href = "./view"+this.view+"?id=" + selectedrecords[0]['id'];
                        break;

                    default:
                        switch (args.item.htmlAttributes.class) {
                            case 'toggled':
                                args.item.htmlAttributes.class = 'untoggled';
                                args.item.tooltipText = 'Click to hide ' + args.item.text;
                                this.isShow[args.item.text] = true;
                                break;

                            case 'untoggled':
                                args.item.htmlAttributes.class = 'toggled';
                                args.item.tooltipText = 'Click to show ' + args.item.text;
                                this.isShow[args.item.text] = false;
                                break;
                        }
                        break;
                }
            },
            updateData: function (args) {
                console.log(args.batchChanges.changedRecords);
                return new Promise((resolve, reject) => {
                    axios['post']('./update', args.batchChanges.changedRecords)
                        .then(response => {
                            console.log(response.data.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            alert('Fail: Tell worakorn.');
                            reject(error.response.data);
                        });
                });
            },
            refresh: function () {
                this.gridData = [...this.data];
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
            setView: function(args) {
                this.$refs.grid.ej2Instances.toolbarModule.enableItems(['view'], args);//Enable toolbar items.
            },
            // getFile: function (args) {
            //     switch (args) {
            //         case 'image':
            //         case 'file':
            //             console.log('here');
            //             return {
            //                 template: Vue.component('viewFile', {
            //                     template: `<div class="template_checkbox">
            //         <input type="checkbox" checked />
            //     </div>
            //     <div v-else class="template_checkbox">
            //         <input type="checkbox" />
            //     </div>`,
            //                     // data: function () {
            //                     //     return {
            //                     //         data: {}
            //                     //     }
            //                     // },
            //                     // computed: {
            //                     //     cData: function () {
            //                     //         return this.data.Discontinued;
            //                     //     }
            //                     // }
            //                 })
            //             }
            //     }
            // }
        }
    }
</script>

<style>
    .toggled .e-tbar-btn-text {
        opacity: 0.35;
        padding: 0em 0.25em !important;
    }

    .untoggled .e-tbar-btn-text {
        opacity: 0.87;
        padding: 0em 0.25em !important;
    }

    label.e-float-text,
    .e-float-input label.e-float-text,
    .e-float-input.e-control-wrapper label.e-float-text {
        font-family: "Roboto", "Segoe UI", "GeezaPro", "DejaVu Serif", "sans-serif", "-apple-system", "BlinkMacSystemFont";
        padding-left: 0px !important;
    }
</style>