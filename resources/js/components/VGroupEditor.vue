<template>
    <div>
        <div class="modal fade" ref="groupEditorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Добавление новой группы
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <template v-if="model != null">
                            <div v-if="step === 1">
                                <div class="mb-4">
                                    <label>Ссылка на группу:</label>
                                    <input type="text" v-model="model.vk_group_link" class="form-control">
                                </div>
                                <div class="mb-4">
                                    <button type="button" @click.prevent="onNextStepToVkCredentials" class="btn btn-vk">
                                        Дальше
                                    </button>
                                </div>
                            </div>
                            <div v-if="step === 2">
                                <div class="mb-4">
                                    <label>Идентификатор приложения:</label>
                                    <input type="text" v-model="model.vk_client_id" class="form-control">
                                    <div class="alert alert-info mt-4">
                                        Эти данные нужны для получения <strong>access token</strong>
                                    </div>
                                    <div>
                                        Вам нужно указать идентификатор вашего standalone-приложения.<br>
                                        Посмотреть мои приложения и создать новые вы можете на странице
                                        <a href="https://vk.com/apps?act=manage" target="_blank">
                                            мои приложения в вк.
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" @click.prevent="onNextStepToAccessTokenInfo" class="btn btn-vk">
                                        Дальше
                                    </button>
                                </div>
                            </div>
                            <div v-if="step === 3">
                                <strong>Получение access token</strong>
                                <div class="mb-4">
                                    После нажатия на кнопку ниже, откроется новая вкладка с запросом
                                    получения access_token, пожалуйста, введите его в поле на следующем шаге.
                                </div>
                                <div>
                                    <button type="button" @click.prevent="onNextStepGetAccessToken" class="btn btn-vk">
                                        Получить access_token
                                    </button>
                                </div>
                            </div>
                            <div v-if="step === 4">
                                <div class="mb-4">
                                    <label>Access token:</label>
                                    <input type="text" v-model="model.vk_access_token" class="form-control">
                                </div>
                                <div>
                                    <button type="button" @click.prevent="onNextStepCheckAccessToken" class="btn btn-vk">
                                        Проверить
                                    </button>
                                </div>
                            </div>
                            <div v-if="step === 5">
                                <div>
                                    <button type="button" @click.prevent="onAddGroup" class="btn btn-vk">
                                        Добавить группу
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap';
import AccessTokenService from "../api/AccessTokenService";
import VkGroupFetcher from "../api/VkGroupFetcher";
import GroupService from "../api/GroupService";

export default {
    data() {
        return {
            modal: null,
            step: 1,
            model: null,
            vkGroup: null
        }
    },

    mounted: function () {
        this.modal = new Modal(this.$refs.groupEditorModal);
        this._resetModel();
    },

    methods: {
        show() {
            this._resetModel();
            this.modal.show();
        },

        async onNextStepToVkCredentials() {
            if (this.model.vk_group_link === null || this.model.vk_group_link.length === 0) {
                return alert('Укажите ссылку на группу');
            }
            // проверка, что такая группа существует
            try {
                this.vkGroup = await VkGroupFetcher.fetch(this.model.vk_group_link);
                this.step = 2;
            } catch(e) {
                return alert(e.message);
            }
        },

        onNextStepToAccessTokenInfo() {
            this.step = 3;
        },

        onNextStepGetAccessToken() {
            this.step = 4;
            window.open(`https://oauth.vk.com/authorize?client_id=${this.model.vk_client_id}&redirect_uri=https%3A%2F%2Foauth.vk.com%2Fblank.html&display=page&scope=327680&response_type=token&v=5.101`);
        },

        async onAddGroup() {
            const response = await GroupService.add(
                this.vkGroup.id,
                this.model.vk_client_id,
                this.model.vk_access_token
            );
            if (response.success) {
                // @todo
            }
        },

        async onNextStepCheckAccessToken() {
            const result = await AccessTokenService.check(this.model.vk_access_token);
            if (!result) {
                return alert('Токен не работает');
            }
            this.step = 5;
        },

        _resetModel() {
            this.model = {
                vk_group_link: null,
                vk_group_id: null,
                vk_client_id: null,
                vk_access_token: null
            };
            this.step = 1;
        }
    }
}
</script>
