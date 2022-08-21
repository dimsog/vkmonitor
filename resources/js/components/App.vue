<template>
    <div>
        <button class="btn btn-vk" @click.prevent="onShowAddGroupModal">Добавить группу</button>
        <v-group-editor ref="groupEditor"></v-group-editor>

        <v-groups :groups="groups" @select="onSelectGroup"></v-groups>
        <v-diff-users v-if="activeGroup != null" :group="activeGroup"></v-diff-users>
    </div>
</template>

<script>
import VGroupEditor from "./VGroupEditor.vue";
import VGroups from "./VGroups.vue";
import VDiffUsers from "./VDiffUsers.vue";
import GroupsService from "../api/GroupsService";

export default {
    components: {
        VGroupEditor,
        VGroups,
        VDiffUsers
    },

    data() {
        return {
            vkGroupId: null,
            groups: [],
            activeGroup: null
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
        },

        onSelectGroup(group) {
            this.activeGroup = group;
        }
    }
}
</script>
