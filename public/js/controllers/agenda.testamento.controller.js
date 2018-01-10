(function () {
    'use strict';

    angular
        .module('app')
        .controller('AgendaTestamentoController', AgendaTestamentoController);

    AgendaTestamentoController.$inject = ['$scope', 'DataService'];

    function AgendaTestamentoController($scope, DataService) {
        $scope.datas = [];
        $scope.horas = [];
        $scope.arrHoras = [];

        var convertDateBr = function(date) {
            var dArr = date.split("-");
            return dArr[2]+ "/" +dArr[1]+ "/" +dArr[0];
        };

        var formatDate = function(date) {
            var dArr = date.split("/");
            return dArr[2]+ "-" +dArr[1]+ "-" +dArr[0];
        };

        var getAgenda = function(){
            DataService.getAgenda().then(function(response) {
                $scope.opcoes = response;

                for(var key in $scope.opcoes) {
                    var date = convertDateBr(key);
                    $scope.datas.push(date);
                }
            });
        };
        getAgenda();

        $scope.getTime = function(date) {
            $scope.arrHoras = [];
            $scope.horas = $scope.opcoes[formatDate(date)];
        };

        $scope.toggleSelection = function(hora) {
            var idx = $scope.arrHoras.indexOf(hora);

            if (idx > -1) {
                $scope.arrHoras.splice(idx, 1);
            } else {
                $scope.arrHoras.push(hora);
            }
        };

        $scope.submit = function(date) {
            var dataSend = {
                data: formatDate(date),
                horas: JSON.stringify($scope.arrHoras)
            };

            DataService.setBloquearAgenda(dataSend).then(function(response) {
                if(response.error) {
                    toastr.error(response.message, 'Testamento', {timeOut: 4000});
                } else {
                    toastr.success('Data/Hora bloqueado com sucesso!', 'Testamento', {timeOut: 3000});
                    $scope.formTestamento.$setPristine();
                    $scope.datas = [];
                    $scope.horas = [];
                    $scope.data = '';
                    getAgenda();
                }
            });
        };
    }

})();