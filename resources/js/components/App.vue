<template>
    <div>
        <button class="btn btn-vk" @click.prevent="onShowAddGroupModal">Добавить группу</button>
        <v-group-editor ref="groupEditor"></v-group-editor>
    </div>
</template>

<script>
import VGroupEditor from "./VGroupEditor.vue";

export default {
    components: {
        VGroupEditor
    },

    data() {
        return {
            vkGroupId: null,
        }
    },

    methods: {
        onShowAddGroupModal() {
            this.$refs.groupEditor.show();
        }
    }
}
</script>
