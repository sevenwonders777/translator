<template>
    <div class="panel-heading clearfix">
        Language - Edit
        
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
        
        created() {
            this.$http.get('/api/languages/' + this.languageId)
                .then(response => {
                    this.language = response.data;
                })
                .catch(response => {
                    let alert = {
                        show: true,
                        type: 'danger',
                        title: 'Error',
                        message: response.statusText
                    };

                    this.$broadcast('showAlert', alert);
                });
        },
        data() {
            return {
                languageId: this.$route.params.languageId,
                url: '/api/languages/',
                language: {},
            }
        },
        events: {
            'submitted'(language) {
                this.submit(language);
            },
        },
        methods: {
            submit(formData) {
                formData.set('_method', 'PUT');

                let url = this.url + this.languageId;

                this.$http.post(url, formData)
                    .then(response => {
                        let alert = {
                            show: true,
                            type: 'success',
                            title: 'Success',
                            message: 'Language successfully updated.'
                        };

                        this.$broadcast('showAlert', alert);
                    }).catch(response => {
                        let errors = response.body;

                        this.$broadcast('formErrors', errors);
                    });
            }
        }
    }
</script>
