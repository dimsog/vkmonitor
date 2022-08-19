<template>
    <div>
        <a v-for="group in groups" :key="group.id" @click.prevent="onSelectGroup(group)" href="#">
            {{ group.vkGroup.name }}
        </a>
    </div>
</template>

<script>
export default {
    props: {
        groups: {
            type: Array,
            required: true
        }
    },
    emits: ['select'],

    methods: {
        onSelectGroup(group) {
            this.$emit('select', group);
        }
    }
}
</script>
