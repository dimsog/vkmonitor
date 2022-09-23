<template>
    <div class="app">
        <div class="content">
            <div class="sidebar">
                <div class="sidebar__toolbar">
                    <div>
                        <button class="btn btn-vk" @click.prevent="onShowAddGroupModal">Добавить группу</button>
                    </div>
                    <div class="d-flex">
                        <v-profile v-if="user != null" :user="user"></v-profile>
                        <a v-if="isShowSettings" @click.prevent="onShowSettings" href="#" class="btn btn-outline-secondary btn-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>
                    </div>
                </div>
                <v-groups :groups="groups" @select="onSelectGroup"></v-groups>
            </div>

            <div class="users">
                <v-diff-users v-if="activeGroup != null" :group="activeGroup" @delete="onDeleteGroup"></v-diff-users>
            </div>

            <v-group-editor ref="groupEditor"></v-group-editor>
            <v-settings v-if="isShowSettings" :show="showSettings" @hide="showSettings = false"></v-settings>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.app {
    height: 100%;
    .content {
        display: flex;
        height: 100%;

        .sidebar {
            width: 400px;
            height: 100%;
            flex-grow: 1;
            padding: 1rem;
            background: rgba(208,215,222,.32);

            .sidebar__toolbar {
                display: flex;
                justify-content: space-between;
                padding-bottom: .5rem;
            }
        }

        .users {
            width: 100%;
            height: 100%;
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
import VProfile from "./VProfile.vue";
import GroupsService from "../api/GroupsService";
import UserService from "../api/UserService";

export default {
    components: {
        VGroupEditor,
        VGroups,
        VDiffUsers,
        VSettings,
        VProfile
    },

    data() {
        return {
            user: null,
            vkGroupId: null,
            groups: [],
            activeGroup: null,
            showSettings: false
        }
    },

    created() {
        this.fetchGroups();
        this.fetchUser();
    },

    methods: {
        onShowAddGroupModal() {
            this.$refs.groupEditor.show();
        },

        async fetchGroups() {
            this.groups = await GroupsService.fetchGroups();
        },

        async fetchUser() {
            this.user = await UserService.fetch();
        },

        onSelectGroup(group) {
            this.activeGroup = group;
        },

        onShowSettings() {
            this.showSettings = true;
        },

        onDeleteGroup() {
            this.groups.splice(this.groups.indexOf(this.activeGroup), 1);
            this.activeGroup = null;
        }
    },

    computed: {
        isShowSettings() {
            return this.user !== null && this.user.isAdmin;
        }
    }
}
</script>
