<tab :name="view + ' Properties'" key=0 id=0 selected=true>
    <div v-if="!reorder" style="padding-top: 10px;">
        <form method="POST" action="/projects"
              @submit.prevent="onSubmit('importGoogle')">

            <propertygrid ref="propertyGrid" :data.sync="propertyLists" :grouplists.sync="groupLists" :searchfield="searchfield"
                          @add="modalShow('modalAddProperty')" @reorder="reorder = true" @getdata="getData()"
                          style="margin-top: 20px; margin-bottom: 20px;"></propertygrid>

        </form>
    </div>


    <div v-if="reorder" style="padding-top: 20px;">
        <h4 class="section-content-title align-center mbr-fonts-style display-5">
            Reorder Property
        </h4>
        <hr>

        <preference-reorder @cancel="reorder = false"></preference-reorder>

    </div>
</tab>
