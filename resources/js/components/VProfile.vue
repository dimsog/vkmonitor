<template>
    <div class="dropdown user-profile">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <img :src="user.avatar" class="user-profile__image" alt="">
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li>
                <a @click.prevent="onLogout()" class="dropdown-item" href="#">Выйти</a>
            </li>
        </ul>
    </div>
</template>

<style lang="scss" scoped>
.user-profile {
    button {
        background: transparent;
        border: none;
        outline: none !important;
        box-shadow: none !important;
        &::after {
            display: none;
        }
        .user-profile__image {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }
    }
}
</style>

<script>
import UserService from "../api/UserService";

export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },

    methods: {
        async onLogout() {
            await UserService.logout();
            location.reload();
        }
    }
}
</script>
