<template>
    <div>
        <div class="modal fade" ref="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" :class="{'modal-lg': size === 'lg' }">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ title }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap';

export default {
    props: {
        title: {
            type: String
        },
        show: {
            type: Boolean,
            default: false
        },
        size: {
            type: String,
            default: 'default'
        }
    },
    emits: ['hide'],
    data() {
        return {
            modal: null
        }
    },

    mounted: function () {
        this.modal = new Modal(this.$refs.modal);
        this.$refs.modal.addEventListener('hidden.bs.modal', () => {
            this.$emit('hide')
        });
    },

    watch: {
        show(value) {
            if (value) {
                this.modal.show();
            } else {
                this.modal.hide();
            }
        }
    }
}
</script>
