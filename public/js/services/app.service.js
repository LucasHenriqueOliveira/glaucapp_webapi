(function () {
    'use strict';

    angular
        .module('app')
        .factory('App', App);

    App.$inject = ['$localStorage'];

    function App($localStorage) {

        var currentCertidao = {};
        var currentProcuracao = {};
        var currentTestamento = {};
        var currentUsuario = {};

        function clearData() {
            $localStorage.destroy('token');
            $localStorage.destroy('user');
        }

        function setCurrentCertidao(certidao) {
            currentCertidao = certidao;
        }

        function getCurrentCertidao() {
            return currentCertidao;
        }

        function setCurrentProcuracao(procuracao) {
            currentProcuracao = procuracao;
        }

        function getCurrentProcuracao() {
            return currentProcuracao;
        }

        function setCurrentTestamento(testamento) {
            currentTestamento = testamento;
        }

        function getCurrentTestamento() {
            return currentTestamento;
        }

        function setCurrentUsuario(usuario) {
            currentUsuario = usuario;
        }

        function getCurrentUsuario() {
            return currentUsuario;
        }

        return {
            api: 'api/',
            user: false,
            token: null,
            clearData: clearData,
            setCurrentCertidao: setCurrentCertidao,
            getCurrentCertidao: getCurrentCertidao,
            setCurrentProcuracao: setCurrentProcuracao,
            getCurrentProcuracao: getCurrentProcuracao,
            setCurrentTestamento: setCurrentTestamento,
            getCurrentTestamento: getCurrentTestamento,
            setCurrentUsuario: setCurrentUsuario,
            getCurrentUsuario: getCurrentUsuario
        };

    }
})();