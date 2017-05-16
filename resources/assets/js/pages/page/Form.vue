<template>
    <form @submit.prevent="submit" novalidate v-cloak autocomplete="off" class="form-horizontal">
        <input v-model="page.id" type="hidden" id="page-id">
        
        <div
            class="form-group"
            :class="{ 'has-error': errors['title'] }">
            <label class="control-label col-sm-3" for="title">Title</label>

            <div class="col-sm-8">
                <input
                    v-model="page.title"
                    type="text"
                    class="form-control"
                    id="title"
                    placeholder="Title...">

                <span class="help-block" v-for="error of errors['title']">
                    {{ error }}
                </span>
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-8">
                <button class="btn btn-primary">
                    <i class="glyphicon glyphicon-save"></i>
                    Save
                </button>
            </div>
        </div>
    </form>

    <notify :alert="alert"></notify>
</template>

<script>
    export default {
        props: {
            page: {
                type: Object,
                required: true
            },
        },
        created() {
        },
        data() {
            return {
                alert: {
                    show: false,
                    type: null,
                    title: null,
                    message: null,
                },
                errors: {}
            }
        },
        computed: {
        },
        methods: {
            submit() {
                let formData = new FormData();

                formData.set('title', this.page.title);

                this.$dispatch('submitted', formData);
            }
        },
        events: {
            'formErrors'(errors) {
                this.errors = errors;
            },
            'showAlert'(alert) {
                this.alert = alert;

                this.$broadcast('notify', this.alert);

                this.errors = {};
            },
        },
    }
</script>