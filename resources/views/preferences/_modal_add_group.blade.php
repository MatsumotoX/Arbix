<sweet-modal ref="modalAddGroup">
    <h4 class="section-content-title align-center mbr-fonts-style display-5">
        Add Group
    </h4>
    <hr>

    <form method="POST" action="/projects"
        @submit.prevent="onSubmit('addGroup')"
        @keydown="addGroup.errors.clear('name')">
        <div class="row">
            <div class="control col-12">
                <eec-inputbox :value.sync="addGroup['name']" type="text" @input="addGroup['name'] = $event" placeholder="Group Name"
                              :options="null"></eec-inputbox>
                <span class="help is-danger" v-if="addGroup.errors.has('name')" v-text="addGroup.errors.get('name')"></span>
            </div>
        </div>
        <div class="control">
            <input type="button" value="Add" class="eec_button" style="margin-top: 5px; font-size: 80%;"
                    @click="onSubmit('addGroup')" :disabled="addGroup.errors.any()">
            <input type="button" class="eec_button" value="Cancel" style="margin-top: 25px; margin-left: 20px; font-size: 80%;"
                   @click="modalHide('modalAddGroup')">
        </div>

    </form>
</sweet-modal>

