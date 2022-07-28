<template>
    <div>
        <v-form @send="onSendVkGroup"></v-form>
    </div>
</template>

<script>
import VForm from "./VForm.vue";
import VkGroupFetcher from "../api/VkGroupFetcher";

export default {
    components: {
        VForm
    },

    data() {
        return {
            vkGroupId: null
        }
    },

    methods: {
        async onSendVkGroup(vkGroupId) {
            const response = await VkGroupFetcher.send(vkGroupId);
            console.log(response);
        }
    }
}
</script>
