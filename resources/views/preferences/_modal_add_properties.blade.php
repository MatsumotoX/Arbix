<sweet-modal ref="modalAddProperty">
    <h4 class="section-content-title align-center mbr-fonts-style display-5">
        Add @{{ view }} Property
    </h4>
    <hr>

    <form method="POST" action="/projects"
          @submit.prevent="onSubmit('addProperty')"
          @mousedown="addProperty.errors.clear()">
        <div class="row">
            <div class="control col-12">
                <eec-inputbox :value.sync="addProperty['name']" type="text" @input="addProperty['name'] = $event" placeholder="Property Name"
                              :options="null"></eec-inputbox>
                <span class="help is-danger" v-if="addProperty.errors.has('name')" v-text="addProperty.errors.get('name')"></span>
            </div>
        </div>
        <div class="row">
            <div class="control col-6" style="font-size: 0.8em !important;">
                <v-select v-model="addProperty['group_id']" placeholder="Group" :options="groupOption"></v-select>
                <span class="help is-danger" v-if="addProperty.errors.has('group_id')" v-text="addProperty.errors.get('group_id')"></span>
            </div>
            <div class="control col-6" style="font-size: 0.8em !important;">
                <v-select v-model="addProperty['type']" placeholder="Type" :options="typeOption"></v-select>
                <span class="help is-danger" v-if="addProperty.errors.has('type')" v-text="addProperty.errors.get('type')"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" style="text-align: left; margin-bottom: 15px;">
                <span style="font-size: 0.8em; color: #777777; margin-left: 10px; margin-right: 10px;">Allow Duplicate: </span>
                <input type="radio" name="importOption" id="false" value=0
                       v-model="addProperty['isUnique']"
                       style="cursor: pointer; font-size: 0.8em;">
                <label for="false" style="cursor: pointer; font-size: 0.8em; margin-right: 10px;">Yes</label>
                <input type="radio" name="importOption" id="true" value=1
                       v-model="addProperty['isUnique']"
                       style="cursor: pointer;">
                <label for="true" style="cursor: pointer; font-size: 0.8em;">No</label>
            </div>
            <div class="control col-6" style="font-size: 0.8em !important; margin-top: -15px;" v-show="addProperty['type'] == 'relation'">
                <v-select v-model="addProperty['relation']" placeholder="Relation" :options="relation"></v-select>
                <span class="help is-danger" v-if="addProperty.errors.has('relation')" v-text="addProperty.errors.get('relation')"></span>
            </div>
            <div class="control col-6 col-md-3" v-show="addProperty['type'] == 'decimal'">
                <eec-inputbox :value.sync="addProperty['digit1']" type="number" @input="addProperty['digit1'] = $event" placeholder="Whole number"
                              :options="null"></eec-inputbox>
                <span class="help is-danger" v-if="addProperty.errors.has('digit1')" v-text="addProperty.errors.get('digit1')"></span>
            </div>
            <div class="control col-6 col-md-3" v-show="addProperty['type'] == 'decimal'">
                <eec-inputbox :value.sync="addProperty['digit2']" type="number" @input="addProperty['digit2'] = $event" placeholder="Decimal Place"
                              :options="null"></eec-inputbox>
                <span class="help is-danger" v-if="addProperty.errors.has('digit2')" v-text="addProperty.errors.get('digit2')"></span>
            </div>
            <div class="control col-md-6" v-show="addProperty['type'] == 'decimal'">
            </div>
            <div class="control col-12 col-md-6" v-show="addProperty['type'] == 'decimal'">
                <span class="help is-danger">[Ex: @{{ digitExample }}]</span>
            </div>
        </div>
        <div class="row">

            <div class="control col-12" style="margin-bottom: 15px;">
                <input type="button" class="eec_button" :value="(showAdvance) ? 'Hide':'Advance'" style="margin-top: 5px; font-size: 80%;"
                       @click="showAdvance = !showAdvance">
            </div>

            <div class="col-md-6" style="text-align: left; margin-bottom: 15px;" v-if="showAdvance == true">
                <span style="font-size: 0.8em; color: #777777; margin-left: 10px; margin-right: 10px;">Record: </span>
                <input type="radio" name="hasMultiple" id="false" value=0
                       v-model="addProperty['hasMultiple']"
                       style="cursor: pointer; font-size: 0.8em;">
                <label for="false" style="cursor: pointer; font-size: 0.8em; margin-right: 10px;">Single</label>
                <input type="radio" name="hasMultiple" id="true" value=1
                       v-model="addProperty['hasMultiple']"
                       style="cursor: pointer;">
                <label for="true" style="cursor: pointer; font-size: 0.8em;">Multiple</label>
            </div>

            <div class="col-md-6" style="text-align: left; margin-bottom: 15px;" v-if="showAdvance == true">
                <span style="font-size: 0.8em; color: #777777; margin-left: 10px; margin-right: 10px;">Require Date:        </span>
                <input type="radio" name="hasDate" id="false" value=1
                       v-model="addProperty['hasDate']"
                       style="cursor: pointer; font-size: 0.8em;">
                <label for="false" style="cursor: pointer; font-size: 0.8em; margin-right: 10px;">Yes</label>
                <input type="radio" name="hasDate" id="true" value=0
                       v-model="addProperty['hasDate']"
                       style="cursor: pointer;">
                <label for="true" style="cursor: pointer; font-size: 0.8em;">No</label>
            </div>

            <div class="col-md-6" style="text-align: left; margin-bottom: 15px;" v-if="showAdvance == true">
                <span style="font-size: 0.8em; color: #777777; margin-left: 10px; margin-right: 10px;">Viewable for all: </span>
                <input type="radio" name="allow" id="false" value=1
                       v-model="addProperty['allow']"
                       style="cursor: pointer; font-size: 0.8em;">
                <label for="false" style="cursor: pointer; font-size: 0.8em; margin-right: 10px;">Yes</label>
                <input type="radio" name="allow" id="true" value=0
                       v-model="addProperty['allow']"
                       style="cursor: pointer;">
                <label for="true" style="cursor: pointer; font-size: 0.8em;">No</label>
            </div>

            <div class="col-md-6" style="text-align: left; margin-bottom: 15px;" v-if="showAdvance == true">
                <span style="font-size: 0.8em; color: #777777; margin-left: 10px; margin-right: 10px;">Special Property</span>
                <input type="radio" name="isSpecial" id="false" value=1
                       v-model="addProperty['isSpecial']"
                       style="cursor: pointer; font-size: 0.8em;">
                <label for="false" style="cursor: pointer; font-size: 0.8em; margin-right: 10px;">Yes</label>
                <input type="radio" name="isSpecial" id="true" value=0
                       v-model="addProperty['isSpecial']"
                       style="cursor: pointer;">
                <label for="true" style="cursor: pointer; font-size: 0.8em;">No</label>
            </div>


        </div>
        <hr>
        <div class="control">
            <input type="button" class="eec_button" value="Add" style="margin-top: 5px; font-size: 80%;"
                   @click="onSubmit('addProperty')" :disabled="addProperty.errors.any()">
            <input type="button" class="eec_button" value="Cancel" style="margin-top: 5px; margin-left: 20px; font-size: 80%;"
                   @click="modalHide('modalAddProperty')">
        </div>

    </form>
</sweet-modal>

