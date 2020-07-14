<template>
    <div class="form-group col-xs-12">
        <checkbox-grid
            :items="availableAbilities"
            :loading="loading"
            @item-checked="processChecked"
        ></checkbox-grid>
        <input type="hidden" name="abilities" :value="selectedAbilities" />
    </div>
</template>

<script>
    import CheckboxGrid from '../presenters/CheckboxGridComponent';
    export default {
        name: "RoleAbilitySelectContainer",
        components: {
            CheckboxGrid
        },
        data() {
            return {
                loading: false,
                selectedAbilities: [],
                availableAbilities: ''
            };
        },
        computed: {},
        methods: {
            ajaxGetAllAbilities() {
                let _this = this;
                this.loading = true;

                $.ajax({
                    url: '/abilities',
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log('Returned Data - ', data);
                        if(data['success']) {
                            _this.availableAbilities = data['abilities'];

                            for(let name in _this.availableAbilities) {
                                let title = _this.availableAbilities[name];
                                _this.availableAbilities[name] = {
                                    title: title,
                                    disabled: false,
                                    checked: false
                                }
                            }
                        }

                        _this.ajaxGetEnabledAbilities();
                    },
                    error: function (e) {
                        console.log('Error contacting server - ',e);
                        _this.loading = false;
                    }
                });
            },
            ajaxGetEnabledAbilities() {
                let _this = this;
                this.loading = true;
                let role = $("[name='name']").val();

                $.ajax({
                    url: '/abilities/'+role,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log('Enabled Returned Data - ', data);
                        if(data['success']) {
                            for(let idx in data['assigned']) {
                                let name = data['assigned'][idx]['name'];
                                if(name in _this.availableAbilities) {
                                    _this.availableAbilities[name].checked = true;
                                    _this.processChecked(_this.availableAbilities[name], name);
                                }
                            }
                        }

                        _this.loading = false;
                    },
                    error: function (e) {
                        console.log('Error contacting server - ',e);
                        _this.loading = false;
                    }
                });
            },
            processChecked(item, name) {
                console.log('Item Toggled - '+name, item);
                this.availableAbilities[name] = item;

                if(item.checked) {
                    this.selectedAbilities.push(name);
                }
                else {
                    for(let idx in this.selectedAbilities) {
                        if(this.selectedAbilities[idx] === name) {
                            this.selectedAbilities.splice(idx, 1);
                        }
                    }
                }

                console.log('Updated selectedAbilities', this.selectedAbilities);
            },
        },
        mounted() {
            this.ajaxGetAllAbilities();
            console.log('RoleAbilitySelectContainer mounted!');
        }
    }
</script>

<style scoped>

</style>
