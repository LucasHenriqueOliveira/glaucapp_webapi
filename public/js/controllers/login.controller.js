(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$scope', 'Auth', 'User'];

    function LoginController($scope, Auth, User) {
        $scope.loginButtonText = "Entrar";
        $scope.resetPassword = false;
        $scope.loading = false;
        $scope.loading_reset = false;
        $scope.message = '';

        $scope.login = function(email, password) {
            $scope.loginButtonText = "Entrando";
            $scope.loading = true;
            $scope.message = '';

            var formData = {
                email: email,
                password: password
            };

            Auth.login(formData, function () {}, function (error) {
                // error
                $scope.loading = false;
            });
        };

        $scope.$on('error-login', function(event, args) {
            $scope.message = args.message;
            $scope.loading = false;
            $scope.loginButtonText = "Entrar";
            $scope.password = '';
        });

        $scope.esqueceuSenha = function() {
            $scope.resetPassword = true;
        };

        $scope.goLogin = function() {
            $scope.resetPassword = false;
        };

        $scope.reset = function(email) {
            $scope.loading_reset = true;

            User.reset({email: email},function (res) {
                if(res.error) {
                    $scope.message = res.message;
                    $scope.loading = false;
                    $scope.loading_reset = false;
                    $scope.resetPassword = false;
                    $scope.loginButtonText = "Entrar";
                    $scope.password = '';
                    return false;
                }
                toastr.success('Enviamos um email com informações da nova senha!', 'Esqueceu a senha', {timeOut: 3000});
                $scope.resetPassword = false;
                $scope.loading_reset = false;

            }, function(error) {
                $scope.message = 'Erro ao recuperar a senha do usuário!';
                $scope.loading = false;
                $scope.loading_reset = false;
                $scope.resetPassword = false;
                $scope.loginButtonText = "Entrar";
                $scope.password = '';
            });
        };
    }

})();