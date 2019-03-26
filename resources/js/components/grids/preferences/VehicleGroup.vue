<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="data" :actionComplete = 'actionComplete' :allowSorting='true' :allowGrouping='false'
                  :editSettings='editSettings' :toolbarClick='clickHandler'>
            <e-columns>
                <e-column field='id' headerText='ID' :isPrimaryKey='true' :visible="false"></e-column>
                <e-column field='name' headerText='Group'></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>

    import {Sort, Group, Search, Toolbar, Edit} from "@syncfusion/ej2-vue-grids";

    export default {
        data() {
            return {
                toolbar: [{text: 'Add', tooltipText: 'Add Group', prefixIcon: 'e-add', id: 'add'}, 'Edit'],
                searchOptions: {ignoreCase: true},
                editSettings: {allowEditing: true, allowAdding: true, allowDeleting: true, mode: 'Dialog'},
            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit]
        },
        props: {
            data: {required: true},
            searchfield: '',
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            }
        },

        methods: {
            clickHandler: function (args) {
                // console.log(args);
                switch (args.item.id) {
                    case 'add':
                        this.$emit('add');
                        break;
                }
            },
            actionComplete(args) {
                if ((args.requestType === 'save')) {
                    // console.log(args);
                    // if (args.data.name != args.previousData.name) {
                        return new Promise((resolve, reject) => {
                            axios['post']('./updateGroup', args.data)
                                .then(response => {
                                    this.$emit('getdata');
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    alert('Error: Tell Worakorn.');
                                    reject(error.response.data);
                                });
                        });
                    // }
                }
            },
        },

    }
</script>

