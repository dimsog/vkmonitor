<template>
    <div class="groups">
        <a
            v-for="group in groups"
            :key="group.id"
            :class="{active: activeGroup != null && activeGroup === group}"
            @click.prevent="onSelectGroup(group)"
            href="#"
            class="group-item"
        >
            <div class="group-item__avatar">
                <img :src="group.vkGroup.photo200" alt="">
            </div>
            <div class="group-item__name">
                {{ group.vkGroup.name }}
            </div>
        </a>
    </div>
</template>

<style lang="scss" scoped>
.groups {
    .group-item {
        display: flex;
        align-items: center;
        padding: 8px;
        border-radius: 5px;
        color: #000;
        text-decoration: none;
        margin-bottom: 2px;
        .group-item__avatar {
            padding-right: 8px;
            img {
                width: 48px;
                height: 48px;
                border-radius: 50%;
            }
        }
        &:hover {
            background: rgba(208,215,222,0.32);
        }
        &.active {
            background: #0969da;
            color: #fff;
        }
    }
}
</style>

<script>
export default {
    props: {
        groups: {
            type: Array,
            required: true
        }
    },
    emits: ['select'],
    data() {
        return {
            activeGroup: null
        }
    },
    methods: {
        onSelectGroup(group) {
            this.activeGroup = group;
            this.$emit('select', group);
        }
    }
}
</script>
