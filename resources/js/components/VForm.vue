<template>
    <div>
        <form @submit.prevent="onSendVkGroup">
            <div class="input-group mb-3">
                <input v-model="vkGroupLink" type="text" class="form-control" placeholder="Ссылка на группу" required>
                <button class="btn btn-outline-secondary" type="submit">
                    Далее
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import VkGroupIdExtractor from "../helpers/VkGroupIdExtractor";

export default {
    data() {
        return {
            vkGroupLink: null
        }
    },
    methods: {
        onSendVkGroup() {
            const vkGroupId = VkGroupIdExtractor.extract(this.vkGroupLink);
            if (vkGroupId.length === 0) {
                return alert('Укажите ссылку на группу в правильном формате');
            }
            this.$emit('send', vkGroupId);
        }
    }
}
</script>
