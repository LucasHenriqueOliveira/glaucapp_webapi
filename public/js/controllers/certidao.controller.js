(function () {
    'use strict';

    angular
        .module('app')
        .controller('CertidaoController', CertidaoController);

    CertidaoController.$inject = ['$scope', '$location', 'App', 'DataService'];

    function CertidaoController($scope, $location, App, DataService) {

        var getCertidoes = function(){
            DataService.getCertidoes({id: App.user.id}).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Certidão', {timeOut: 4000});
                    $location.path('/dashboard');
                } else {
                    $scope.certidoes = response;

                    jQuery(document).ready(function () {
                        $('table.display').DataTable({
                            "aaSorting": []
                        });
                    });
                }
            });
        };
        getCertidoes();

        $scope.detalhesCertidao = function(certidao) {
            App.setCurrentCertidao(certidao);
            $location.path('/detalhes-certidao').search({id: certidao.pedido_id});
        };

        $scope.movimentar = function(certidao) {
            var descricao = '';

            switch (certidao.status) {
                case 'Aguardando':
                    descricao = "Iniciar Análise";
                    break;
                case 'Em análise':
                    descricao = "Documento pronto";
                    break;
                case 'Pronto':
                    descricao = "Realizar a entrega";
                    break;
            }
            var movimentacao = {
                descricao: descricao,
                pedido_id: certidao.pedido_id
            };

            DataService.addMovimentacao(movimentacao).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Movimentação', {timeOut: 4000});
                } else {
                    toastr.success('Movimentação realizada com sucesso!', 'Movimentação', {timeOut: 3000});
                    $scope.detalhesCertidao(response);
                }
            });

        };
    }

})();