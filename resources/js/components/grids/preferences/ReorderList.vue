<template>
    <div class="fluid" id="reorderlist">

        <div class="row">
            <div class="col-md-6">
                <draggable class="list-group" element="ul" v-model="list" :options="dragOptions" :move="onMove" @start="isDragging=true" @end="reordered">
                    <transition-group type="transition" :name="'flip-list'">
                        <li class="list-group-item" v-for="element in list" :key="element.order">
                            <i :class="element.fixed? 'fa fa-anchor' : 'glyphicon glyphicon-pushpin'" @click=" element.fixed=! element.fixed" aria-hidden="true"></i>
                            {{element.name}}
                            <!--<span class="badge">{{element.order}}</span>-->
                        </li>
                    </transition-group>
                </draggable>
            </div>

            <div class="col-md-6">
                <draggable element="span" v-model="list2" :options="dragOptions" :move="onMove" @start="isDragging=true" @end="reordered">
                    <transition-group name="no" class="list-group" tag="ul">
                        <li class="list-group-item" v-for="element in list2" :key="element.order">
                            <i :class="element.fixed? 'fa fa-anchor' : 'glyphicon glyphicon-pushpin'" @click=" element.fixed=! element.fixed" aria-hidden="true"></i>
                            {{element.name}}
                            <!--<span class="badge">{{element.order}}</span>-->
                        </li>
                    </transition-group>
                </draggable>
            </div>

        </div>
    </div>
</template>

<script>
    import draggable from "vuedraggable";

    export default {
        components: {
            draggable
        },
        data() {
            return {
                list: this.listprop1,
                list2: [],
                editable: true,
                isDragging: false,
                delayedDragging: false
            };
        },
        props: {
            listprop1: {required: true},
            listprop2: {default: null},
        },
        created() {
            this.originalList = this.list;
        },
        methods: {
            orderList() { //Sort by original
                this.list = this.list.sort((one, two) => {
                    return one.order - two.order;
                });
            },
            onMove({relatedContext, draggedContext}) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },
            reordered() {
                this.isDragging = false;
                this.$emit('reorder', [this.list, this.list2]);
            }
        },
        computed: {
            dragOptions() {
                return {
                    animation: 0,
                    group: "description",
                    disabled: !this.editable,
                    ghostClass: "ghost"
                };
            },
        },
        watch: {
            isDragging(newValue) {
                if (newValue) {
                    this.delayedDragging = true;
                    return;
                }
                this.$nextTick(() => {
                    this.delayedDragging = false;
                });
            },
            listprop1(newValue) {
                this.list = newValue;
            },
            listprop2(newValue) {
                this.list2 = newValue;
            }
        }
    };
</script>

<style>
    #reorderlist {
        color: #777777 !important;
        font-size: 13px !important;
        font-family: "Roboto", "Segoe UI", "GeezaPro", "DejaVu Serif", "sans-serif", "-apple-system", "BlinkMacSystemFont" !important;
    }

    .flip-list-move {
        transition: transform 0.5s;
    }

    .no-move {
        transition: transform 0s;
    }

    .ghost {
        opacity: 0.5;
        background: #c8ebfb;
    }

    .list-group {
        min-height: 20px;
    }

    .list-group-item {
        cursor: move;
    }

    .list-group-item i {
        cursor: pointer;
    }
</style>
