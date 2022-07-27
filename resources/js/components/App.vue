<template>
    <div>
        <v-form @send="onSendVkGroup"></v-form>
    </div>
</template>

<script>
import VForm from "./VForm.vue";

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
        onSendVkGroup(vkGroupId) {

        }
    }
}
</script>
