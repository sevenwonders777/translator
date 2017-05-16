<template>
    <form @submit.prevent="submit" novalidate v-cloak autocomplete="off" class="form-horizontal">
        <div
            class="form-group"
            :class="{ 'has-error': errors['name'] }">
            <label class="control-label col-sm-3" for="name">Name</label>

            <div class="col-sm-8">
                <input
                    v-model="language.name"
                    type="text"
                    class="form-control"
                    id="name"
                    placeholder="Name...">

                <span class="help-block" v-for="error of errors['name']">
                    {{ error }}
                </span>
            </div>
        </div>

        <div
            class="form-group"
            :class="{ 'has-error': errors['priority'] }">
            <label class="control-label col-sm-3" for="priority">Priority</label>

            <div class="col-sm-8">
                <input
                    v-model="language.priority"
                    v-default-value="1"
                    type="text"
                    class="form-control"
                    id="priority"
                    placeholder="Priority...">

                <span class="help-block" v-for="error of errors['priority']">
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
            language: {
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

                formData.set('name', this.language.name);
                formData.set('priority', this.language.priority);

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