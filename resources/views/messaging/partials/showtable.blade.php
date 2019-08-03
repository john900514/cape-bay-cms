<div class="row" id="pnTableSection">
    <div id="filterPanel">
        <!-- dropdown for message to send -->
        <div id="msgTemplateSelectSection" class="filter-section">
            <select v-model="msgTemplateSelect">
                <option value="0">Select a Message To Send</option>
                @foreach($note_templates as $note_idx => $note)
                    <option value="{!! $note_idx+1 !!}">{!! $note['name'] !!}</option>
                @endforeach
            </select>
        </div>

        <!-- column filter -->
        <div id="columnFilterSection" class="filter-section">
            <select v-model="columnSelect">
                <option value="0">Column Filters</option>
                @foreach($fields as $field_idx => $field_attr)
                    @if($field_idx != 'selected' && $field_idx != 'primary_location_name' && $field_idx != 'last_login_readable')
                    <option value="{!! $field_idx !!}">{!! $field_attr['label'] !!}</option>
                    @endif
                @endforeach
            </select>
            <input type="textbox" v-if="columnSelect !== '0'" v-model="columnSelectText"/>
        </div>

        <div id="actionCtrlPanel" class="boxed filter-section">
            <h2>Actions</h2>
            <div><p>@{{ users_selected }} users selected.</p></div>
            <div v-if="users_selected > 0"><button type="button" @click="confirmFireMessages()" class="red-type panel-button">Fire</button></div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="">
            <div class="overflow-hidden">
                <template>
                    <div id="usersInner">
                        <b-table id="myTable"
                                 class="my-table-scroll"
                                 selectable
                                 striped
                                 hover
                                 responsive
                                 selectedVariant="success"
                                 :selectMode="selectMode"
                                 class="my-table-scroll"
                                 :items="currentlyAvailableUsers"
                                 :per-page="perPage"
                                 :current-page="currentPage"
                                 :fields="fields">
                            <template slot="HEAD_selected" slot-scope="data">
                                <em @click="toggleSelectAll()">@{{ data.label }}</em>
                            </template>

                            <template slot="selected" slot-scope="data">
                                <b-form-checkbox v-model="data.item.selected" @change="selectUser(data.item.id, data.item.expo_push_token)" ></b-form-checkbox>
                                <!-- <span v-if="selectMember(data.uuid)">✔</span> -->
                            </template>
                        </b-table>
                    </div>

                    <div id="bottomCtrlPanel">
                        <p class="mt-3">Current Page: @{{ currentPage }}</p>
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="rows"
                            :per-page="perPage"
                            aria-controls="myTable"
                        ></b-pagination>
                    </div>
                </template>

            </div>
        </div>
    </div>
</div>
