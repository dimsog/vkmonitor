<template>
    <div>
        <h1>{{ group.vkGroup.name }}</h1>

        <div>
            <div v-for="diff in diffItems">
                <div>{{ diff.weekName }}, {{ diff.date }}</div>
                <div v-for="user in diff.users">
                    {{ user.vkUser.firstName }} {{ user.vkUser.lastName }}
                </div>
            </div>
        </div>
    </div>
</template>

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
        }
    },

    watch: {
        group() {
            this.fetchDiffItems();
        }
    }
}
</script>
