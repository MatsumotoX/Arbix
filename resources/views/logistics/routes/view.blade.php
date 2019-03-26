@extends('layouts.app') @section('title', 'Logistic - Route')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    @{{ view }} Detail
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>@{{ mainDirectory }}  /</span>
    <span>@{{ subDirectory }}  /</span>
    <span style="color: #525252;">@{{ view }} Detail</span>
@endsection

@section('content')
    <section id="formeec">
        <div class="cd-bubble">
            <div class="row">
                <div class="col-md-12 col-xl-3" style="padding-top: 10px" v-for="(propertyx, propertyName) in data.Info" :key="propertyName">
                    <div class="control">
                        <eec-inputbox v-if="(propertyName != 'ลูกค้า' && propertyName != 'จุดหมาย')" :value.sync="propertyx" :placeholder="propertyName" type="disabled"></eec-inputbox>

                        <eec-inputbox v-else-if="(propertyName == 'ลูกค้า')" :value.sync="addRoute[propertyName]" :placeholder="propertyName" @input="addRoute[propertyName] = $event" @clear="addRoute.errors.clear(propertyName)"
                                      type="select" :option="property[0].property[2].option"></eec-inputbox>

                        <eec-inputbox v-else :value.sync="addRoute[propertyName]" :placeholder="propertyName" @input="addRoute[propertyName] = $event" @clear="addRoute.errors.clear(propertyName)"
                                      type="select" :option="property[0].property[3].option" optiongroup="group"></eec-inputbox>

                        <span class="help is-danger" v-if="addRoute.errors.has(propertyName)" v-text="addRoute.errors.get(propertyName)"></span>

                    </div>
                </div>
            </div>
        </div>
        <div class="cd-bubble-tab">
            <div>
                <tabs>
                    <tab v-for="(group, index) in property" :key="index" v-if="(group['name'] != 'Info' && group['name'] != 'Route')" :id="group['name']"
                         :name="group['name']" :selected="(group['name'] == 'Income')">

                        <div class="row">
                            <div class="col-md-10 col-lg-7" style="padding-top: 10px" v-for="(property, property_index) in group['property']"
                                 :key="property_index" v-if="property['isSpecial'] == 0">
                                <eec-inputbox :value.sync="addRoute[property['name']]" :placeholder="property['name']"
                                              @input="addRoute[property['name']] = $event" :type="property['type']"
                                              :option="property['option']" optiontext='text' optionvalue='value' :digit="property['digit']"
                                              :decimal="property['decimal']" allowfilter="true" filtertype="contains"></eec-inputbox>
                                <span class="help is-danger" v-if="addRoute.errors.has(property['name'])"
                                      v-text="addRoute.errors.get(property['name'])"></span>
                            </div>
                        </div>
                    </tab>

                    <tab id="Route" name="Route">
                    </tab>
                </tabs>
            </div>
        </div>
    </section>

@endsection @section('script')

    <script type="text/javascript" src="/js/views/hrs/customers/view.js"></script>
    <script src="/js/form/form.js"></script>

    <script>

        window.vm = new Vue({
            el: '#asset',

            data: {
                searchfield: '',
                view,
                data,
                property,
                id,
                mainDirectory,
                subDirectory,

                addRoute: new Form(),
            },

            created() {
                for (let datum in this.data) {
                    this.addRoute.addField(this.data[datum]);
                }
            }

        });
    </script>
@endsection