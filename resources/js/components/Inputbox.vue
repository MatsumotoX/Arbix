<template>
    <div>
        <div class="e-float-input" v-if="type == 'text' || type == 'string'">
            <input type="text" :name="placeholder" v-model="inputValue" class="e-field" required/>
            <span class="e-float-line"></span>
            <label class="e-float-text">{{ placeholder }}</label>
        </div>

        <div class="e-float-input" v-if="type == 'textarea'">
            <textarea :name="placeholder" type="text" v-model='inputValue' required></textarea>
            <span class="e-float-line"></span>
            <label class="e-float-text">{{ placeholder }}</label>
        </div>

        <div class="e-float-input" v-if="type == 'number'">
            <input type="number" :name="placeholder" v-model="inputValue" required/>
            <span class="e-float-line"></span>
            <label class="e-float-text">{{ placeholder }}</label>
        </div>

        <div class="e-float-input" v-if="type == 'file' || type == 'image'" v-show="!isSelect">
            <input type="text" v-model="fileName" required @focus="isSelect = true"/>
            <span class="e-float-line"></span>
            <label class="e-float-text">{{ placeholder }}</label>
        </div>

        <div class="e-float-input" v-if="type == 'file' || type == 'image'" v-show="isSelect">
            <input style="padding-left: 8px;"
                   type="file" ref="file" :name="placeholder" v-on:change="handleFileUpload()" @blur="isSelect = false"/>
            <span class="e-float-line"></span>
            <span class="e-clear-icon close-icon" v-show="fileName" @click="clearFile"></span>
            <label class="e-float-text">{{ placeholder }}</label>
        </div>

        <ejs-textbox v-if="type == 'disabled'" floatLabelType="Always" :placeholder="placeholder" v-model="inputValue"
                     :enabled="false"></ejs-textbox>

        <ejs-datepicker ref="date" v-if="type == 'date'" floatLabelType="Auto" strictMode='true' :placeholder='placeholder'
                        v-model="inputValue" @focus="dateFocus()"></ejs-datepicker>

        <ejs-daterangepicker ref="date" v-if="type == 'daterange'" floatLabelType="Auto" strictMode='true' :placeholder='placeholder'
                             v-model="inputValue" @focus="dateFocus()"></ejs-daterangepicker>

        <ejs-datetimepicker ref="date" v-if="type == 'datetime'" floatLabelType="Auto" strictMode='true' :placeholder='placeholder'
                            v-model="inputValue" @focus="dateFocus()"></ejs-datetimepicker>

        <ejs-combobox ref="combo" v-if="type == 'select' || type == 'relation' || type == 'dropdown'" v-model="inputValue" autofill="true"
                      :allowCustom='false' @focus="selectFocus()"
                      :dataSource="option" :fields="fields" :enabled="enable"
                      floatLabelType="Auto" :placeholder='placeholder' :allowFiltering="allowfilter" :filtering="filtering" :change="onChange"
                      :blur="onBlur"></ejs-combobox>

        <ejs-multiselect v-if="type == 'multiselect'" :dataSource='option' v-model="inputValue" autofill="true" :allowCustom='false'
                         :mode="mode"
                         :fields='fields' :placeholder="placeholder" floatLabelType="Auto" :allowFiltering="allowfilter" :filtering="filtering"
                         :change="onChange"></ejs-multiselect>

        <ejs-numerictextbox v-if="type == 'integer'" format=",###" floatLabelType="Auto" :placeholder="placeholder" decimals="0"
                            v-model="inputValue" validateDecimalOnType="true"></ejs-numerictextbox>

        <ejs-numerictextbox v-if="type == 'decimal'" format=",###.########" floatLabelType="Auto" :placeholder="placeholder"
                            :decimals="decimal" v-model="inputValue" validateDecimalOnType="true" :enabled="enable"></ejs-numerictextbox>

        <ejs-numerictextbox v-if="type == 'currency'" format="฿ ,###.00" floatLabelType="Auto" :placeholder="placeholder" :decimals="decimal"
                            v-model="inputValue" validateDecimalOnType="true"></ejs-numerictextbox>

        <ejs-numerictextbox v-if="type == 'percentage'" format="p2" floatLabelType="Auto" :placeholder="placeholder" :decimals="decimal"
                            v-model="inputValue" step="0.01" validateDecimalOnType="true"></ejs-numerictextbox>

        <ejs-maskedtextbox v-if="type == 'phone'" mask='\\+00 90 000 0000' :placeholder='placeholder' floatLabelType='Auto' promptChar="#"
                           v-model="inputValue"></ejs-maskedtextbox>

        <ejs-maskedtextbox v-if="type == 'time'" mask='00:00:00' :placeholder='placeholder' floatLabelType='Auto' promptChar="#"
                           v-model="inputValue" :enabled="enable"></ejs-maskedtextbox>

        <div class="e-float-input" v-if="specialType == 'textSelect' || specialType == 'selectText'" v-show="specialType == 'textSelect'">
            <input type="text" :name="placeholder" v-model="inputValue" required @focus="changeType()"/>
            <span class="e-float-line"></span>
            <label class="e-float-text">{{ placeholder }}</label>
        </div>

        <div v-if="specialType == 'textSelect' || specialType == 'selectText'" v-show="specialType == 'selectText'">
            <ejs-combobox ref="combo" v-model="inputValue" autofill="true" :allowCustom='false'
                          :dataSource="option" :fields="fields" :enabled="enable"
                          floatLabelType="Auto" :placeholder='placeholder' :allowFiltering="allowfilter" :filtering="filtering" :change="onChange"
                          :blur="onSelectBlur"></ejs-combobox>
        </div>
    </div>
</template>

<script>

    function isDate(x) {
        return (null != x) && !isNaN(x) && ("undefined" !== typeof x.getDate);
    }

    import {Query} from '@syncfusion/ej2-data';
    import {DatePickerPlugin, DateRangePickerPlugin} from '@syncfusion/ej2-vue-calendars';
    import {ComboBoxPlugin} from '@syncfusion/ej2-vue-dropdowns';
    import {NumericTextBoxPlugin} from "@syncfusion/ej2-vue-inputs";
    import {MaskedTextBoxPlugin} from "@syncfusion/ej2-vue-inputs";
    import {SplitButtonPlugin} from "@syncfusion/ej2-vue-splitbuttons";
    import {TextBoxPlugin} from '@syncfusion/ej2-vue-inputs';
    import {MultiSelectPlugin} from "@syncfusion/ej2-vue-dropdowns";
    import {MultiSelect, CheckBoxSelection} from '@syncfusion/ej2-dropdowns';
    import { DateTimePickerPlugin } from '@syncfusion/ej2-vue-calendars';

    MultiSelect.Inject(CheckBoxSelection);
    Vue.use(MultiSelectPlugin);
    Vue.use(TextBoxPlugin);
    Vue.use(SplitButtonPlugin);
    Vue.use(NumericTextBoxPlugin);
    Vue.use(MaskedTextBoxPlugin);
    Vue.use(DatePickerPlugin);
    Vue.use(ComboBoxPlugin);
    Vue.use(DateRangePickerPlugin);
    Vue.use(DateTimePickerPlugin);

    export default {
        data() {
            return {
                inputValue: this.value,
                specialType: null,
                fileName: null,
                isSelect: false,
                fields: {text: this.optiontext, value: this.optionvalue, groupBy: this.optiongroup},
            }
        },
        created() {
            if (this.type == 'textSelect') {
                this.specialType = 'textSelect';
            }
        },
        mounted() {
            this.placeholder = this.placeholder.charAt(0).toUpperCase() + this.placeholder.slice(1);

        },
        updated() {
            if (!this.inputValue && this.type == 'textSelect') {
                this.inputValue = this.value;
            }
        },
        props: {
            allowfilter: {default: false},
            filtertype: {required: false},
            placeholder: {required: false},
            value: {required: false},
            mode: {required: false},
            digit: {required: false},
            decimal: {required: false},
            type: {required: false},
            option: {required: false},
            optiontext: {default: 'text'},
            optionvalue: {default: 'value'},
            optiongroup: {default: false},
            enable: {default: true},
        },
        watch: {
            inputValue(newValue, oldValue) {
                if (this.type == 'textSelect') {
                    if (newValue) {
                        this.$emit('input', newValue);
                    }
                    this.$emit('clear');
                } else {
                    this.$emit('input', newValue);
                    this.$emit('clear');
                }
            },
            value(newValue) {

                if (this.type == 'textSelect') {
                    new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            resolve('foo');
                        }, 0);
                    })
                        .then(response => {
                            if (newValue != this.inputValue) {
                                this.specialType = 'textSelect';
                            }
                        })
                        .then(response => {
                            this.inputValue = newValue;
                        })
                } else {

                    this.inputValue = newValue;

                    if (!newValue && (this.type == 'file' || this.type == 'image')) {
                        this.$refs.file.value = null;
                        this.fileName = null;
                    }
                }
            }
        },
        methods: {
            changeType() {
                new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        resolve('foo');
                    }, 0);
                })
                    .then(response => {
                        this.setSelect()
                    })
                    .then(response => {
                        this.$refs.combo.showPopup();
                        this.$refs.combo.focusIn();
                    })
            },
            setSelect() {
                this.specialType = 'selectText';

                return true;
            },
            onSelectBlur() {
                if ((this.value == this.inputValue && typeof this.value == 'string') || !this.inputValue) {
                    this.specialType = 'textSelect';
                }
                this.$emit('blur');
            },
            dateFocus() {
                this.$refs.date.show();
            },
            selectFocus() {
                this.$refs.combo.showPopup();
            },
            handleFileUpload() {
                this.inputValue = this.$refs.file.files[0];
                this.fileName = this.$refs.file.value;
                this.$emit('input', this.exportValue);
            },
            clearFile() {
                this.inputValue = this.$refs.file.value = null;
                this.isSelect = false;
            },
            filtering: function (args) {
                var searchData = this.option;
                var query = new Query();
                //frame the query based on search string with filter type.
                query = (args.text != "") ? query.where(this.optiontext, this.filtertype, args.text, true) : query;
                //pass the filter data source, filter query to updateData method.
                args.updateData(searchData, query);
            },
            onChange(args) {
                this.$emit('change', args);
            },
            onBlur(args) {
                this.$emit('blur', args);
            }
        }
    }
</script>
<style>
    .close-icon {
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: transparent;
        z-index: 1;
        right: 5px;
        top: 0;
        bottom: 0;
        margin: auto;
        padding: 2px;
        border-radius: 50%;
        text-align: center;
        color: #dbdbdb;
        font-weight: normal;
        font-size: 12px;
        cursor: pointer;
    }
</style>