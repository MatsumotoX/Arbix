Vue.component('tabs', {
    template: `
        <div>
            <div class="tabs is-boxed">
                <ul style="margin-bottom: 0px;">
                    <li v-for="(tab, tabindex) in tabs" :key='tabindex' :class="{ 'is-active': tab.isActive }">
                        <a @click="selectTab(tab.id)">{{ tab.name }}</a>
                    </li>
                </ul>
            </div>
            <div class="tabs-details">

                <div class="asset_form">
            
                        <slot></slot>

                    <div class="row" v-if="navigation">
                        <div class="col-6" style="text-align: left">
                            <button class="eec_button eec_btn_margin" type="button" @click="previousIndex">Previous</button>
                        </div>

                        <div class="col-6" style="text-align: right">
                            <button class="eec_button eec_btn_margin" type="button" @click="nextIndex">Next</button>
                        </div>
                    </div>                    
                    
                    <div v-else> <br> </div> 
                        
                </div>
            </div>
            </form>
            
        </div>
    `,

    data() {
        return {
            tabs: [],
            index: 0,
        };
    },

    props: {
        navigation: {default: true},
    },

    created() {
        this.tabs = this.$children;
    },

    methods: {
        selectTab(selectedTab) {
            this.tabs.forEach(tab => {
                tab.isActive = (tab.href == '#' + selectedTab);
            });
        },

        previousIndex() {
            this.index--;
            if (this.index < 0) {
                this.index = this.tabs.length - 1;
            }
            this.tabs.forEach(tab => {
                tab.isActive = (tab.href == '#' + this.index);
            });
        },

        nextIndex() {
            this.index++;
            if (this.index > this.tabs.length - 1) {
                this.index = 0;
            }
            this.tabs.forEach(tab => {
                tab.isActive = (tab.href == '#' + this.index);
            });
        }

    }
});


Vue.component('tab', {
    template: `
        <div v-show="isActive"><slot></slot></div>
    `,

    props: {
        name: {required: true},
        id: {required: true},
        selected: {default: false}
    },

    data() {
        return {
            isActive: false
        };
    },

    computed: {
        href() {
            return '#' + this.id;
        }
    },

    mounted() {
        this.isActive = this.selected;
    },
});

