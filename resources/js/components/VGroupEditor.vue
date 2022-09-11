<template>
    <v-modal title="Добавление новой группы" :show="_show" @hide="hide()">
        <template v-if="model != null">
                <form @submit.prevent="onAddGroup">
                    <div class="mb-4">
                        <label>Ссылка на группу:</label>
                        <input type="text" v-model="model.vk_group_link" class="form-control">
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-vk">
                            Добавить группу
                        </button>
                    </div>
                </form>
            </template>
    </v-modal>
</template>

<script>
import VModal from "./VModal.vue";
import GroupService from "../api/GroupService";

export default {
    components: {
        VModal
    },
    data() {
        return {
            _show: false,
            model: null
        }
    },

    mounted: function () {
        this._resetModel();
    },

    methods: {
        show() {
            this._resetModel();
            this._show = true;
        },

        hide() {
            this._resetModel();
            this._show = false;
        },

        async onAddGroup() {
            try {
                await GroupService.add(
                    this.model.vk_group_link
                );
            } catch (e) {
                alert(e.message);
            }
        },

        _resetModel() {
            this.model = {
                vk_group_link: null
            };
        }
    }
}
</script>
