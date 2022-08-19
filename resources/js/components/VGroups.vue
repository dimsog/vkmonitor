<template>
    <div>
        <a v-for="group in groups" :key="group.id" href="#">
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
    }
}
</script>
