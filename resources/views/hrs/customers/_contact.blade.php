<tab id="4" name="Contact">
    <div class="container fixed_content_container" style="padding-top: 20px; padding-bottom: 30px;">
        <div class="row">
            <div class="col-md-6" style="padding-top: 10px">
                <div class="control">
                    <eec-inputbox :value.sync="addFlexMessage['name']" placeholder="Title" @input="addFlexMessage['name'] = $event"
                                  type="string"
                    ></eec-inputbox>
                </div>
            </div>

            <div class="col-md-6" style="padding-top: 10px">
                <div class="control">
                    <eec-inputbox :value.sync="addFlexMessage['contents']" placeholder="Contents"
                                  @input="addFlexMessage['contents'] = ($event)" :option="flex.column[3].option"
                                  type="multiselect"
                                  allowfilter="true" filtertype="contains"></eec-inputbox>
                </div>
            </div>

            <div class="col-md-6" style="padding-top: 10px">
                <div class="control">
                    <eec-inputbox :value.sync="addFlexMessage['quickreply_id']" placeholder="Quick Reply"
                                  @input="addFlexMessage['quickreply_id'] = ($event)" :option="flex.column[4].option"
                                  type="select"
                                  allowfilter="true" filtertype="contains"></eec-inputbox>
                </div>
            </div>

            <div class="col-md-6" style="padding-top: 10px">
                <div class="control">
                    <eec-inputbox :value.sync="addFlexMessage['altTextSpecial']" placeholder="Alternate Text (Special)" @input="addFlexMessage['altTextSpecial'] = $event"
                                  type="string"
                    ></eec-inputbox>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" style="padding-top: 10px">
                <div class="control">
                    <eec-inputbox :value.sync="addFlexMessage['altText']" placeholder="Alternate Text" @input="addFlexMessage['altText'] = $event"
                                  type="textarea"
                    ></eec-inputbox>
                </div>
            </div>

        </div>

        <div class="col-md-12" style="text-align: center; margin-top:20px;">
            <button @click="onSubmit" class="eec_button" :disabled="addFlexMessage.errors.any()">Save</button>
        </div>
    </div>
</tab>
