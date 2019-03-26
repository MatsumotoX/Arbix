<template>
    <div id='app'>
        <ejs-toast ref='elementRef' id='element' :cssClass="noticlass" :title="notititle" :content="noticontent" :animation="animation" :position='position' :beforeOpen='beforeOpen'></ejs-toast>
    </div>
</template>

<script>
    import Vue from "vue";
    import {ToastPlugin, Toast} from "@syncfusion/ej2-vue-notifications";

    Vue.use(ToastPlugin);
    export default {
        name: 'app',
        data: function () {
            return {
                position: {X: 'Right', Y: 'Bottom'},
                animation: {show: {effect: "SlideBottomIn", duration: 200, easing: "linear"}, hide: {effect: "SlideBottomOut", duration: 300, easing: "linear"}},
                noticlass: null,
                notititle: null,
                noticontent: null,
            }
        },

        props: {
            notitype: {default: null},
            title: {default: null},
            content: {default: false}
        },

        created() {
            this.noticontent = this.content;
            this.noticlass = this.getClass(this.notitype)
        },
        methods: {
            show: function (args) {
                var noticlass = this.getClass(args.type);
                var notititle = this.getTitle(args);
                var noti = {
                    title: notititle,
                    content: args.content,
                    cssClass: noticlass,
                };
                this.$refs.elementRef.show(noti);
            },
            getTitle: function (args) {
                if (args.title) {

                    return args.title;

                } else {

                    switch (args.type) {

                        case 'success':
                            return 'Success !';

                        case 'danger':
                            return 'Error !';
                    }
                }

            },
            getClass: function (args) {

                switch (args) {
                    case 'success':
                        if (!this.title) {
                            this.notititle = 'Success !';
                        }
                        return 'e-toast-success';
                    case 'danger':
                        if (!this.title) {
                            this.notititle = 'Error !';
                        }
                        return 'e-toast-danger';
                }

            },
            beforeOpen: function(e){
                var audio = new Audio('/sound/pull-out.mp3');
                audio.play();
            }

        }
    }
</script>

