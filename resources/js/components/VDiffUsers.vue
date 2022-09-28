<template>
    <div class="diff-items">
        <div v-if="isLoading" class="diff-items__loading">
            <div>
                <v-spinner></v-spinner>
            </div>
        </div>
        <template v-else>
            <div class="d-flex justify-between">
                <div class="w-100">
                    <h1>{{ group.vkGroup.name }}</h1>
                </div>
                <div>
                    <button @click.prevent="onDeleteGroup" type="button" class="btn btn-outline-secondary btn-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="4" y1="7" x2="20" y2="7"></line>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <div v-for="diff in diffItems" class="diff-item">
                    <div class="diff-item__date">
                        <strong>{{ diff.weekName }}, {{ getDMYDate(diff.date) }}</strong>
                    </div>
                    <div v-if="diff.users.length > 0" class="diff-item__users">
                        <a v-for="user in diff.users"
                           class="diff-item-user"
                           :class="{'diff-item-user--subscribed': user.subscribed}"
                           :href="getVkUserLink(user.vkUser)"
                           target="_blank"
                        >
                            <div class="diff-item-user__avatar">
                                <img :src="user.vkUser.photo200">
                            </div>
                            <div class="diff-item-user__name">
                                {{ user.vkUser.firstName }}<br> {{ user.vkUser.lastName }}
                            </div>
                        </a>
                    </div>
                    <div v-else class="diff-item__users diff-item__users--empty">
                        <span class="text-muted">нет пользователей</span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<style lang="scss" scoped>
.diff-items {
    height: 100%;
    .diff-items__loading {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
    }
    .diff-item {
        .diff-item__users {
            display: flex;
            flex-wrap: wrap;
            padding-bottom: 10px;
            .diff-item-user {
                display: block;
                width: 80px;
                text-align: center;
                color: #666;
                text-decoration: none;
                margin: 0 8px 8px 0;
                .diff-item-user__avatar {
                    img {
                        max-width: 100%;
                        max-height: 100%;
                        border-radius: 50%;
                        border: 3px solid #ffa0a0;
                    }
                }
                .diff-item-user__name {
                    font-size: .8rem;
                }
                &.diff-item-user--subscribed {
                    img {
                        border-color: #9be9a8;
                    }
                }
            }
            &.diff-item__users--empty {
                text-align: center;
            }
        }
    }
}
</style>

<script>
import moment from 'moment';
import DiffUsersReaderService from "../api/DiffUsersReaderService";
import GroupService from "../api/GroupService";
import VSpinner from './VSpinner.vue';

export default {
    components: {
        VSpinner
    },
    props: {
        group: {
            type: Object,
            required: true
        }
    },

    emits: ['delete'],

    data() {
        return {
            diffItems: [],
            isLoading: true
        }
    },

    mounted() {
        this.fetchDiffItems();
    },

    methods: {
        async fetchDiffItems() {
            this.isLoading = true;
            this.diffItems = await DiffUsersReaderService.fetch(this.group.id);
            this.isLoading = false;
        },

        onDeleteGroup() {
            if (confirm('Вы уверены?')) {
                GroupService
                    .delete(this.group.id)
                    .then(() => {
                        this.$emit('delete');
                    });
            }
        },

        getVkUserLink(vkUser) {
            if (vkUser.deactivated) {
                return '#';
            }
            return 'https://vk.com/' + vkUser.screenName;
        },

        getDMYDate(date) {
            return moment(date).format('DD.MM.YYYY');
        }
    },

    watch: {
        group() {
            this.fetchDiffItems();
        }
    }
}
</script>
