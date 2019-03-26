<template>
    <div>
        <div class="control-section">
            <div id="chip-default-wrapper">

                <div class="sample-padding">
                    <!-- initialize default chip component -->
                    <ejs-chiplist id="chip-default" :selection="selection" :selectedChips.sync="selectedChips" :click="onClick">
                        <e-chips>
                            <e-chip v-for="(chip, chipKey) in currentChips" :key="chipKey" :text="chip"></e-chip>
                        </e-chips>
                    </ejs-chiplist>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Vue from "vue";
    import {ChipListPlugin} from "@syncfusion/ej2-vue-buttons";

    Vue.use(ChipListPlugin);

    export default Vue.extend({
        data: function () {
            return {
                currentChips: this.chips,
                selectedChips: [],
                selectedData: {},
            };
        },
        props: {
            chips: {required: true},
            selection: {required: false},
        },
        watch: {
            value(newValue) {
                this.currentChips = newValue;
            }
        },
        methods: {
            onClick(args) {
                switch(this.selection){
                    case 'Multiple':
                        if (args.selected == true) {
                            this.selectedData[args.index] = args.data.text;
                            this.$emit('input', this.selectedData);
                        } else {
                            delete this.selectedData[args.index];
                            if (Object.keys(this.selectedData).length == 0){
                                this.$emit('input', null);
                            }else{
                                this.$emit('input', this.selectedData);
                            }
                        }
                        break;

                    case 'Single':
                        if (args.selected == true) {
                            this.selectedData = {};
                            this.selectedData[args.index] = args.data.text;
                            this.$emit('input', this.selectedData);
                        } else {
                            this.selectedData = {};
                            this.$emit('input', null);
                        }
                        break;

                }
            }
        },
    });
</script>
