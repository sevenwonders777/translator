<template>
    <div class="panel-heading clearfix">
        Language - Create
        
        <div class="text-center pull-right">
            <button class="btn btn-default" v-link="{ name: 'language' }">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Back
            </button>
        </div>
    </div>
    
    <div class="panel-body">
        <language-form :language="language"></language-form>
    </div>
    
</template>
        
<script>
    import LanguageForm from './Form.vue';
    
    export default {
        components:{
            'language-form': LanguageForm
        },
        
        data() {
            return {
                url: '/api/languages',
                language: {
                    name: '',
                    priority: '1',
                }
            }
        },
        ready() {
            //window.$('#priority').val(1);
        },
        events: {
            'submitted'(language) {
                this.submit(language);
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
                            message: 'Language successfully created.'
                        };

                        this.$broadcast('showAlert', alert);

                        this.resetForm();
                        
                        this.$route.router.go('/language');
                    }).catch(response => {
                        let errors = response.body;

                        this.$broadcast('formErrors', errors);
                    });
            },
            resetForm() {
                this.language = {
                    name: '',
                    priority: '1',
                }
            }
        }
    }
</script>
