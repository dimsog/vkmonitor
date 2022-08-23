<template>
    <div class="diff-items">
        <h1>{{ group.vkGroup.name }}</h1>

        <div>
            <div v-for="diff in diffItems" class="diff-item">
                <div class="diff-item__date">
                    <strong>{{ diff.weekName }}, {{ diff.date }}</strong>
                </div>
                <div class="diff-item__users">
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
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.diff-items {
    .diff-item {
        .diff-item__users {
            display: flex;
            flex-wrap: wrap;
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
        }
    }
}
</style>

<script>
import DiffUsersReaderService from "../api/DiffUsersReaderService";

export default {
    props: {
        group: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            diffItems: []
        }
    },

    mounted() {
        this.fetchDiffItems();
    },

    methods: {
        async fetchDiffItems() {
            this.diffItems = await DiffUsersReaderService.fetch(this.group.id);
        },

        getVkUserLink(vkUser) {
            if (vkUser.deactivated) {
                return '#';
            }
            return 'https://vk.com/' + vkUser.screenName;
        }
    },

    watch: {
        group() {
            this.fetchDiffItems();
        }
    }
}
</script>
