<template>
    <div class="app">
        <div class="header">
            <div>
                <button class="btn btn-vk" @click.prevent="onShowAddGroupModal">Добавить группу</button>
            </div>
            <div>
                <a @click.prevent="onShowSettings" href="#" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </a>
            </div>
        </div>
        <div class="content">
            <div class="sidebar">
                <v-groups :groups="groups" @select="onSelectGroup"></v-groups>
            </div>

            <div class="users">
                <v-diff-users v-if="activeGroup != null" :group="activeGroup"></v-diff-users>
            </div>

            <v-group-editor ref="groupEditor"></v-group-editor>
            <v-settings :show="showSettings"></v-settings>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.app {
    .header {
        display: flex;
        justify-content: space-between;
    }
    .content {
        display: flex;

        .sidebar {
            width: 400px;
            flex-grow: 1;
            padding: 1rem;
        }

        .users {
            width: 100%;
            padding: 1rem 0 1rem 1rem;
        }
    }
}
</style>

<script>
import VGroupEditor from "./VGroupEditor.vue";
import VGroups from "./VGroups.vue";
import VDiffUsers from "./VDiffUsers.vue";
import VSettings from "./VSettings.vue";
import GroupsService from "../api/GroupsService";

export default {
    components: {
        VGroupEditor,
        VGroups,
        VDiffUsers,
        VSettings
    },

    data() {
        return {
            vkGroupId: null,
            groups: [],
            activeGroup: null,
            showSettings: false
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
        },

        onShowSettings() {
            this.showSettings = true;
        }
    }
}
</script>
