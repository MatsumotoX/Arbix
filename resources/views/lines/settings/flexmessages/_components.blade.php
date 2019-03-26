<tab name="Bubble" key=1 id=1>
    <allpurpose-grid name="Bubble" :data.sync="bubble.data" :columns.sync="bubble.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Bubble Style" key=2 id=2>
    <allpurpose-grid name="BubbleStyle" :data.sync="bubbleStyle.data" :columns.sync="bubbleStyle.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Box" key=3 id=3>
    <allpurpose-grid name="Box" :data.sync="box.data" :columns.sync="box.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Component" key=4 id=4>
    <linecomponent-grid name="Component" :data.sync="component.data" :columns.sync="component.column" :searchfield="searchfield" :allowadd="true"
                        :allowdelete="true" :allowedit="false" maindirectory="Line" subdirectory="Bot"
                        @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></linecomponent-grid>
</tab>
<tab name="Button" key=5 id=5>
    <allpurpose-grid name="Button" :data.sync="button.data" :columns.sync="button.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Icon" key=6 id=6>
    <allpurpose-grid name="Icon" :data.sync="icon.data" :columns.sync="icon.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Image" key=7 id=7>
    <allpurpose-grid name="Image" :data.sync="image.data" :columns.sync="image.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Separator" key=8 id=8>
    <allpurpose-grid name="Separator" :data.sync="separator.data" :columns.sync="separator.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Spacer" key=9 id=9>
    <allpurpose-grid name="Spacer" :data.sync="spacer.data" :columns.sync="spacer.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Text" key=10 id=10>
    <allpurpose-grid name="Text" :data.sync="text.data" :columns.sync="text.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Quick Reply" key=11 id=11>
    <linequickreply-grid name="QuickReply" :data.sync="quickReply.data" :columns.sync="quickReply.column" :searchfield="searchfield" :allowadd="true"
                         :allowdelete="true" :allowedit="false" maindirectory="Line" subdirectory="Bot"
                         @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></linequickreply-grid>
</tab>
<tab name="Quick Reply Button" key=12 id=12>
    <allpurpose-grid name="QuickReplyButton" :data.sync="quickReplyButton.data" :columns.sync="quickReplyButton.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
</tab>
<tab name="Action" key=13 id=13>
    <lineaction-grid name="Action" :data.sync="action.data" :columns.sync="action.column" :searchfield="searchfield" :allowadd="true"
                     :allowdelete="true" :allowedit="true" maindirectory="Line" subdirectory="Bot" editmode="Dialog"
                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></lineaction-grid>
</tab>
