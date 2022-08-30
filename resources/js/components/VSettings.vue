<template>
    <v-modal title="Настройки" :show="showModal" size="lg">
        <form v-if="model != null" @submit.prevent="onStoreSettings">
            <div class="mb-4">
                <strong>Access token:</strong>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Client id</th>
                            <th>Access token</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="token in model.tokens">
                            <td>
                                <input type="text" v-model="token.client_id" class="form-control">
                            </td>
                            <td>
                                <input type="text" v-model="token.access_token" class="form-control">
                            </td>
                            <td>
                                <button @click.prevent="onOpenTokenLink(token)" type="button" class="btn btn-secondary">
                                    Получить токен
                                </button>
                            </td>
                            <td>
                                <button @click="onDeleteToken(token)" type="submit" class="btn btn-danger">
                                    <img src="/public/assets/images/icons/x.svg" alt="">
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <button @click.prevent="onAddToken" type="button" class="btn btn-secondary">
                                    Добавить
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div>
                <button class="btn btn-primary" type="submit">
                    Сохранить
                </button>
            </div>
        </form>
    </v-modal>
</template>

<script>
import VModal from "./VModal.vue";
import SettingsService from "../api/SettingsService";

export default {
    props: {
        show: {
            type: Boolean,
            default: false
        }
    },
    components: {
        VModal
    },
    data() {
        return {
            model: null,
            showModal: false
        }
    },
    mounted() {
        this.fetchSettings();
    },
    methods: {
        fetchSettings() {
            SettingsService
                .fetch()
                .then(settings => this.model = settings);
        },

        onAddToken() {
            this.model.tokens.push({
                id: 0,
                client_id: null,
                access_token: null
            })
        },

        onOpenTokenLink(token) {
            if (token.client_id === null || token.client_id.length === 0) {
                return alert('Укажите client id');
            }
            window.open(`https://oauth.vk.com/authorize?client_id=${token.client_id}&redirect_uri=https%3A%2F%2Foauth.vk.com%2Fblank.html&display=page&scope=327680&response_type=token&v=5.101`);
        },

        onDeleteToken(token) {
            this.model.tokens.splice(this.model.tokens.indexOf(token), 1);
        },

        onStoreSettings() {
            SettingsService
                .store(this.model)
                .then(() => {
                    this.showModal = false;
                });
        }
    },

    watch: {
        show(value) {
            this.showModal = value;
        }
    }
}
</script>
