<template>
    <div id="app">
        <ejs-grid ref='grid' :enableVirtualization=true :height="tableheight" :showColumnChooser='true' :allowReordering='true' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="data" :allowSorting='true' :allowFiltering='false' :allowGrouping='true' :toolbarClick='clickHandler' :rowDeselected='rowSelected' :rowSelected='rowSelected'>
            <e-columns>
                <e-column type='checkbox' width='50'></e-column>
                <e-column v-for="(blueprint, blueprintKey) in blueprints" :key="blueprintKey" :visible=blueprint.visible :field=blueprint.column :headerText=blueprint.column textAlign='Right' width=150></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>
    import Vue from "vue";
    import { GridPlugin, Page, Sort, Filter, Group, Aggregate, Search, Toolbar, ColumnChooser, Reorder, VirtualScroll} from "@syncfusion/ej2-vue-grids";
    import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";

    Vue.use(GridPlugin);
    Vue.use(ButtonPlugin);

    export default {
        data() {
            return {
                data: vehicles,
                // toolbar: [{ text: 'View', tooltipText: "View vehicle's details", prefixIcon: 'fa fa-desktop', id: 'viewVehicle' },'ColumnChooser'],
                toolbar: ['ColumnChooser'],
                // toolbar: ['Print', 'Search'],
                searchOptions: { ignoreCase: true },
                pageSettings: { pageSize: 20 },
                // filterOptions: {
                //     type: 'Menu'
                // },
                footerSum: function () {
                    return  { template : Vue.component('sumTemplate', {
                            template: `<span>Sum: {{data.Sum}}</span>`,
                            data () {return { data: {}};}
                        })
                    }
                }
            };
        },

        provide: {
            grid: [Page, Sort, Filter, Group, Aggregate, Search, Toolbar, ColumnChooser, Reorder, VirtualScroll]
        },
        props: {
            vehicles: { required: true },
            blueprints: { required: true },
            tableheight: Number,
            searchfield: '',
        },

        watch: {
            searchfield: function(newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            }
        },
        mounted() {
            this.$refs.grid.ej2Instances.toolbarModule.enableItems(['viewVehicle'],false);//Disable toolbar items.
        },
        methods: {
            clickHandler: function(args) {
                if (args.item.id === 'viewVehicle') {
                    let selectedrecords = this.$refs.grid.getSelectedRecords();  // Get the selected records.
                    window.location.href = "/assets/vehicles/show/" + selectedrecords[0]['id'];
                }
            },
            rowSelected: function(args) {
                let selectedrowindex = this.$refs.grid.getSelectedRowIndexes();  // Get the selected row indexes.
                if (selectedrowindex.length > 1 || selectedrowindex.length == 0) {
                    this.$refs.grid.ej2Instances.toolbarModule.enableItems(['viewVehicle'],false);//Disable toolbar items.
                }else {
                    this.$refs.grid.ej2Instances.toolbarModule.enableItems(['viewVehicle'],true);//Disable toolbar items.
                }
            }
        },

    }
</script>
<style>
    @import "/css/grid/material.css";
</style>