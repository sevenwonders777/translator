<template>
    <div class="panel-heading clearfix">
        Page - Edit
        
        <div class="text-center pull-right">
            <button class="btn btn-default" v-link="{ name: 'page' }">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Back
            </button>
        </div>
    </div>
    
    <div class="panel-body">
        <page-form :page="page"></page-form>
        
        <h4>
            Phrases
            
            <button class="btn btn-default" @click="addRow()">
                <i class="glyphicon glyphicon-plus"></i>
                Add
            </button>
        </h4>        
        <div class="table-responsive">
            <table id="phrase-table" class="table table-bordered table-hover" style="min-width:717px;">
                <colgroup>
                    <col width="70"/>
                    <col v-for="lng in page.languages"/>
                </colgroup>
                <thead>
                    <tr>
                        <td></td>
                        <td v-for="lng in page.languages">
                            {{ lng.name }}
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(key, phrase) in page.phrases" class="old" data-hash="{{ key }}">
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-danger btn-xs btn-remove"
                                title="Delete">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-success btn-xs btn-save"
                                title="Save"
                                style="display:none;">
                                <i class="glyphicon glyphicon-check"></i>
                            </button>
                        </td>
                        <td v-for="lng in page.languages" class="td-word" data-language-code="{{ lng.code }}">{{ phrase[lng.code] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="hidden">
        <table>
            <tr id="row-template" class="new">
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-danger btn-xs btn-remove"
                        title="Delete">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-success btn-xs btn-save"
                        title="Save"
                        disabled="disabled">
                        <i class="glyphicon glyphicon-check"></i>
                    </button>
                </td>
                <td v-for="lng in page.languages" class="td-word" data-language-code="{{ lng.code }}">
                    <input type="text" class="input-word" value="" style="width:100%;"/>
                </td>
            </tr>    
        </table>
    </div>
</template>

<script> 
    import PageForm from './Form.vue';
    
    export default {
        components:{
            'page-form': PageForm
        },
        
        created() {
            this.$http.get('/api/pages/' + this.pageId)
                .then(response => {
                    this.page = response.data;
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
                pageId: this.$route.params.pageId,
                url: '/api/pages/',
                page: {},
            }
        },
        events: {
            'submitted'(page) {
                this.submit(page);
            },
        },
        methods: {
            submit(formData) {
                formData.set('_method', 'PUT');

                let url = this.url + this.pageId;

                this.$http.post(url, formData)
                    .then(response => {
                        let alert = {
                            show: true,
                            type: 'success',
                            title: 'Success',
                            message: 'Page successfully updated.'
                        };

                        this.$broadcast('showAlert', alert);
                    }).catch(response => {
                        let errors = response.body;

                        this.$broadcast('formErrors', errors);
                    });
            },
            
            addRow() {
                var row = window.$('#row-template').clone();
                row.removeAttr('id');
                
                var hash = stringGen(20);
                row.attr('data-hash', hash);
                window.$('input.input-word', row).each(function() {
                    var obj = window.$(this);
                    var name = 'word' + '[' + hash + ']' + '[' + obj.parent().data('language-code') + ']';
                    obj.attr('name', name);                    
                });
                
                window.$('#phrase-table tbody').append(row);    
            },
        }
    }
    
    
    window.$(document).ready(function() {
        window.$(document).on('click', '#phrase-table tbody tr button.btn-remove', function() {
            var row = window.$(this).parents('tr');
            row.remove();
            
            if(row.hasClass('old')) {
                var url = '/api/pages/remove-row/' + window.$('#page-id').val();
                $.ajax({
                    type: "POST",
                    headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
                    url: url,
                    data: {'hash': row.data('hash')} ,
                    success: function(res){
                        
                    }
                });    
            }
        });
        
        window.$(document).on('click', '#phrase-table tbody tr button.btn-save', function() {
            var btn = window.$(this);
            var row = window.$(this).parents('tr');
            //alert(window.$('input', row).serialize());            
            
            var url = '/api/pages/save-row/' + window.$('#page-id').val();
            $.ajax({
                type: "POST",
                headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
                url: url,
                data: window.$('input', row).serialize(),
                success: function(res){
                    //btn.remove();
                    btn.hide();
                    
                    //remove input tags
                    window.$('input.input-word', row).each(function() {
                        var input = window.$(this);
                        var td = input.parent();
                        td.html(input.val());
                    });
                    
                    row.removeClass('new')
                        .removeClass('editing')
                        .addClass('old');
                }
            });
        });
        
        window.$(document).on('change', '#phrase-table tbody tr input.input-word', function() {    
            var row = window.$(this).parents('tr');
            var canSave = false;
            window.$('input.input-word', row).each(function() {
                if(window.$(this).val() != '') {
                    canSave = true;
                    return;
                }
            });
            
            if(canSave) {
                window.$('.btn-save', row).removeAttr('disabled');   
            } else {
                window.$('.btn-save', row).attr('disabled', true);
            }
        });
        
        window.$(document).on('click', '#phrase-table tbody tr.old', function() {  
            var row = window.$(this);
            if(row.hasClass('editing')) {
                return;
            }
            
            row.addClass('editing');
            window.$('.btn-save', row).show();
            window.$('td.td-word', row).each(function() {
                var obj = window.$(this);
                var oldValue = obj.html();
                var name = 'word' + '[' + row.data('hash') + ']' + '[' + obj.data('language-code') + ']';
                var html = '<input type="text" class="input-word" value="' + oldValue + '" name="' + name + '" style="width:100%;">';
                obj.html(html);    
            });
        });
    });
    
    function stringGen(len)
    {
        var text = "";
        var charset = "abcdefghijklmnopqrstuvwxyz0123456789";       
        for( var i=0; i < len; i++ )
            text += charset.charAt(Math.floor(Math.random() * charset.length));
        return text;
    }
</script>