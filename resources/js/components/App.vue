<template>
    <div>
        <button class="btn btn-vk" @click.prevent="onShowAddGroupModal">Добавить группу</button>
        <v-group-editor ref="groupEditor"></v-group-editor>

        <v-groups :groups="groups"></v-groups>
    </div>
</template>

<script>
import VGroupEditor from "./VGroupEditor.vue";
import VGroups from "./VGroups.vue";
import GroupsService from "../api/GroupsService";

export default {
    components: {
        VGroupEditor,
        VGroups
    },

    data() {
        return {
            vkGroupId: null,
            groups: []
        }
    },

    created() {
        this.fetchGroups();
    },

    methods: {
        onShowAddGroupModal() {
            this.$refs.groupEditor.show();
        },

        async fetchGroups() {
            this.groups = await GroupsService.fetchGroups();
        }
    }
}
</script>
