<div class="row" id="pnTableSection">
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
