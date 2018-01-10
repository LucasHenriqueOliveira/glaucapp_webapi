(function () {
    'use strict';

    angular
        .module('app')
        .factory('DataService', DataService);

    DataService.$inject = ['$http', '$q', 'App'];

    function DataService($http, $q, App) {

        return {
            getDashboard: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'dashboard',
                    params: params
                })
                .then(function(response) {

                    deferred.resolve(response.data);

                }, function(error) {
                    console.log(error);
                });

                return deferred.promise;
            },
            getCertidao: function(params) {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'certidao',
                    params: params
                })
                .then(function(response) {

                    deferred.resolve(response.data);

                }, function(error) {
                    toastr.error('Erro ao buscar as certidões e traslados', 'Certidão e Traslados', {timeOut: 3000});
                });

                return deferred.promise;
            },
            getCertidoes: function() {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'certidoes'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar as certidões e traslados', 'Certidão e Traslados', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getProcuracao: function(params) {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'procuracao',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar as procurações', 'Procuração', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getProcuracoes: function() {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'procuracoes'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar as procurações', 'Procuração', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getTestamento: function(params) {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'testamento',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar os testamentos', 'Testamento', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getTestamentos: function() {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'testamentos'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar os testamentos', 'Testamento', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            addMovimentacao: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'movimentar',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao movimentar', 'Movimentação', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getUsuarios: function() {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'usuarios'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar os usuários', 'Usuário', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getUsuario: function(params) {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'usuario',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar o usuário', 'Usuário', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            removeUsuario: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'DELETE',
                    url: App.api + 'usuario',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao remover o usuário', 'Usuário', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            editarUsuario: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'PUT',
                    url: App.api + 'usuario',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao alterar os dados do usuário', 'Usuário', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            addUsuario: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'usuario',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao cadastrar o usuário', 'Usuário', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            trocaSenha: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'troca-senha',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao trocar a senha do usuário', 'Usuário', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            relatorio: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'relatorio',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar o relatório', 'Relatório', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getDocumento: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'procuracao/documento',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar o documento', 'Documento', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getAgenda: function() {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'testamento/datas'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar a agenda', 'Testamento', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            setBloquearAgenda: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'testamento/bloquear-agenda',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao bloquear a agenda', 'Testamento', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getEstados: function() {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'estados'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar os estados', 'Estado', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getCidades: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'cidades',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar as cidades', 'Cidade', {timeOut: 3000});
                    });

                return deferred.promise;
            }
        }
    }
})();