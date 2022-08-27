<template>
    <div>
        <div class="modal fade" ref="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
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
        }
    },
    data() {
        return {
            modal: null
        }
    },

    mounted: function () {
        this.modal = new Modal(this.$refs.modal);
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
