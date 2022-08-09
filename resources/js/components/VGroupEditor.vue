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
                                    <input type="text" v-model="model.group_link" class="form-control">
                                </div>
                                <div class="mb-4">
                                    <button type="button" @click.prevent="onNextStepToVkCredentials" class="btn btn-vk">
                                        Дальше
                                    </button>
                                </div>
                            </div>
                            <div v-if="step === 2">
                                <div class="alert alert-info">
                                    Эти данные нужны для получения <strong>access token</strong>
                                </div>
                                <div class="mb-4">
                                    <label>Идентификатор приложения:</label>
                                    <input type="text" v-model="model.vk_client_id" class="form-control">
                                    <div>
                                        Вам нужно указать идентификатор вашего standalone-приложения.<br>
                                        Посмотреть мои приложения и создать новые вы можете на странице
                                        <a href="#">
                                            мои приложения в вк.
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-vk">
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
                                    <button type="button" class="btn btn-vk">
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
                                <button type="button" class="btn btn-vk">
                                    Проверить
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

export default {
    data() {
        return {
            modal: null,
            step: 1,
            model: null
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

        onNextStepToVkCredentials() {
            if (this.model.group_link === null || this.model.group_link.length === 0) {
                return alert('Укажите ссылку на группу');
            }
            this.step = 2;
        },

        _resetModel() {
            this.model = {
                group_link: null,
                vk_client_id: null,
                vk_client_secret: null,
                vk_access_token: null
            };
            this.step = 1;
        }
    }
}
</script>
