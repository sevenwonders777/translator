<template>
    <div class="panel-heading clearfix">
        Page - Create
        
        <div class="text-center pull-right">
            <button class="btn btn-default" v-link="{ name: 'page' }">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Back
            </button>
        </div>
    </div>
    
    <div class="panel-body">
        <page-form :page="page"></page-form>
    </div>
    
</template>
        
<script>
    import PageForm from './Form.vue';
    
    export default {
        components:{
            'page-form': PageForm
        },
        
        data() {
            return {
                url: '/api/pages',
                page: {
                    title: '',
                }
            }
        },
        events: {
            'submitted'(page) {
                this.submit(page);
            },
        },
        methods: {
            submit(formData) {
                this.$http.post(this.url, formData)
                    .then(response => {
                        let alert = {
                            show: true,
                            type: 'success',
                            title: 'Success',
                            message: 'Page successfully created.'
                        };

                        this.$broadcast('showAlert', alert);

                        this.resetForm();
                        this.$route.router.go('/page');
                    }).catch(response => {
                        let errors = response.body;

                        this.$broadcast('formErrors', errors);
                    });
            },
            resetForm() {
                this.page = {
                    title: '',
                }
            }
        }
    }
</script>
