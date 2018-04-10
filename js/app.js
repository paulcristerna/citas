// Aplicación de citas en linea.

var citasUAS = angular.module('citasUAS', ['ngRoute']);

// Configuración de rutas de la aplicación.

citasUAS.config(function($routeProvider) 
{
    $routeProvider
        .when('/', 
        {
            templateUrl : 'views/login.html',
            controller  : 'indexCtrl'
        })
        .when('/admin/usuarios', 
        {
            templateUrl : 'views/usuarios.html',
            controller  : 'usuariosCtrl'
        })
        .when('/admin/administradores', 
        {
            templateUrl : 'views/administradores.html',
            controller  : 'administradoresCtrl'
        })
        .when('/admin/citas', 
        {
            templateUrl : 'views/citasAdministrador.html',
            controller  : 'citasAdministradorCtrl'
        })
        .when('/citas', 
        {
            templateUrl : 'views/citasUsuario.html',
            controller  : 'citasUsuarioCtrl'
        });
});

// Filtros.

citasUAS.filter('getById', function() 
{
  return function(input, id) 
  {
    var i=0, len=input.length;
    for (; i<len; i++) 
    {
      if (+input[i].id == +id) 
      {
        return input[i];
      }
    }
    return null;
  }
});

citasUAS.filter('datetime', function($filter)
{
 return function(input)
 {
    if(input != null)
    { 
        var t = input.split(/[- :]/);
        var fecha = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

        var dd = fecha.getDate();
        var mm = fecha.getMonth()+1;
        var yyyy = fecha.getFullYear();
        var h = fecha.getHours();
        var m = fecha.getMinutes();
        var a = 'AM';

        if(dd<10) {dd='0'+dd;} 

        if(mm<10 || mm == 0) {mm='0'+mm;}

        if(h>12) {h = h - 12; a = 'PM';}

        if(m<10) {m='0'+m;}

        fecha = dd+'-'+mm+'-'+yyyy+' '+h+':'+m+' '+a;

        return fecha;
    }
    else
    {
        return ""; 
    }
 };
});

// Controlador de página de inicio.

citasUAS.controller('indexCtrl', function($scope, $http, $filter, $location) 
{
    // Agregar usuario.

    $scope.agregar = function(campos) 
    {
        if(campos.password != campos.repetirPassword)
        {
            $scope.alertaCuadro = true;
            $scope.alertaColor = "warning";
            $scope.alertaIcono = "alert";
            $scope.alertaTexto = "Las contraseñas no coinciden, favor de corregir.";
        }
        else
        {
            $http.post('api/agregarUsuario', campos).success(function()
            {
                $scope.alertaCuadro = true;
                $scope.alertaColor = "success";
                $scope.alertaIcono = "ok";
                $scope.alertaTexto = "Se guardo al usuario con exito en la base de datos, favor de iniciar sesión.";

                $scope.campos = {};            
            });
        }
    }

    // Login.

    $scope.login = function(campos)
    {
        $http.post("api/login", campos).success(function(data) 
        {
            if(data.length > 0)
            {
                $scope.session(data[0].id);
                
                switch(data[0].tipo) 
                {
                    case '1':
                        $location.path( "/citas" );
                    break;
                    case '2':
                        $location.path( "/admin/citas" );
                    break;
                }
            }
            else
            {
                $scope.alertaLoginCuadro = true;
                $scope.alertaLoginColor = "warning";
                $scope.alertaLoginIcono = "alert";
                $scope.alertaLoginTexto = "El usuario y/o la contraseña son incorrectas.";
            }
        });
    }

    // Crear sesión.

    $scope.session = function(id)
    {
        $http.post("php/login.php", { id: id })
        .success(function(data)
        { return 0; });
    }
});

// Controlador de usuarios.

citasUAS.controller('usuariosCtrl', function($scope, $http, $filter, $location) 
{
    // Listado de usuarios.

    $scope.listado = function()
    {
        $http.get("api/listadoUsuarios").success(function(data) 
        {
            $scope.usuarios = data;
        });        
    }

    $scope.listado();

    // Obtener usuario.

    $scope.obtener = function(id) 
    {
        $scope.iconoModal = "pencil";
        $scope.nombreModal = "Editar";

        $scope.master = {};
        $scope.master = angular.copy($scope.usuarios);

        $scope.campos = $filter('getById')($scope.master, id);
    }

    // Editar administrador.

    $scope.editar = function(campos)
    {
        $http.put('api/editarUsuario/'+campos.id, campos).success(function(data) 
        {
            $('#myModal').modal('hide');
            
            $scope.alertaCuadro = true;
            $scope.alertaColor = "success";
            $scope.alertaIcono = "ok";
            $scope.alertaTexto = "Se edito al usuario con exito en la base de datos.";
            
            $scope.listado(); 
        });
    }
});

// Controlador de administradores.

citasUAS.controller('administradoresCtrl', function($scope, $http, $filter) 
{
    // Listado de administradores.

    $scope.listado = function()
    {
        $http.get("api/listadoAdministradores").success(function(data) 
        {
            $scope.administradores = data;
        });        
    }

    $scope.listado();

    // Agregar administrador.

    $scope.agregar = function(campos) 
    {
        $http.post('api/agregarAdministrador', campos).success(function()
        {
            $('#myModal').modal('hide');

            $scope.alertaCuadro = true;
            $scope.alertaColor = "success";
            $scope.alertaIcono = "ok";
            $scope.alertaTexto = "Se guardo al administrador con exito en la base de datos.";

            $scope.listado();            
        });
    }

    // Obtener administrador.

    $scope.obtener = function(id) 
    {
        $scope.iconoModal = "pencil";
        $scope.nombreModal = "Editar";

        $scope.master = {};
        $scope.master = angular.copy($scope.administradores);

        $scope.campos = $filter('getById')($scope.master, id);
    }

    // Editar administrador.

    $scope.editar = function(campos)
    {
        $http.put('api/editarAdministrador/'+campos.id, campos).success(function(data) 
        {
            $('#myModal').modal('hide');
            
            $scope.alertaCuadro = true;
            $scope.alertaColor = "success";
            $scope.alertaIcono = "ok";
            $scope.alertaTexto = "Se edito al administrador con exito en la base de datos.";
            
            $scope.listado(); 
        });
    }

    // Limpiar campos.

    $scope.limpiar = function(id) 
    {
        $scope.campos = {};

        $scope.iconoModal = "plus";
        $scope.nombreModal = "Agregar";
    }
});

// Controlador de citas del administrador.

citasUAS.controller('citasAdministradorCtrl', function($scope, $http, $filter) 
{
    // Listado de citas.

    $scope.listado = function()
    {
        $http.get("api/listadoCitasAdministrador").success(function(data) 
        {
            $scope.citas = data;
        });        
    }

    $scope.listado();

    // Obtener cita.

    $scope.obtener = function(id) 
    {
        $scope.master = {};
        $scope.master = angular.copy($scope.citas);

        $scope.campos = $filter('getById')($scope.master, id);

        var t = $scope.campos.fecha.split(/[- :]/);
        var fecha = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

        var dd = fecha.getDate();
        var mm = fecha.getMonth()+1;
        var yyyy = fecha.getFullYear();
        var h = fecha.getHours();
        var m = fecha.getMinutes();
        var a = 'AM';

        if(dd<10) {dd='0'+dd;} 

        if(mm<10 || mm == 0) {mm='0'+mm;}

        if(h>12) {h = h - 12; a = 'PM';}

        fecha = dd+'-'+mm+'-'+yyyy;
        
        $scope.campos.fecha = fecha;
        $scope.campos.hora = h;
        $scope.campos.minutos = m;
        $scope.campos.horario = a;
        $scope.campos.confirmacion = "2";
    }

    // Editar cita.

    $scope.editar = function(campos)
    {
        campos.fecha = $("#dp1").val();

        $http.put('api/editarCitaAdministrador/'+campos.id, campos).success(function(data) 
        {
            $('#myModal').modal('hide');
            
            $scope.alertaCuadro = true;
            $scope.alertaColor = "success";
            $scope.alertaIcono = "ok";
            $scope.alertaTexto = "Se edito la cita con exito en la base de datos.";
            
            $scope.listado(); 
        });
    }
});

// Controlador de citas del usuario.

citasUAS.controller('citasUsuarioCtrl', function($scope, $http, $filter) 
{
    // Listado de citas.

    $scope.listado = function()
    {
        $http.get("php/listadoCitasUsuario.php")
        .then(function (data) 
        {
            $scope.citas = data.data.records;
        });        
    }

    $scope.listado();

    // Agregar cita.

    $scope.agregar = function(campos) 
    {
        /*
        $scope.campos.fecha = fecha;
        $scope.campos.hora = 9;
        $scope.campos.minutos = 0;
        $scope.campos.horario = 'AM';
        */

        $http.post("php/agregarCitaUsuario.php", campos).success(function(data)
        {
            $('#myModal').modal('hide');

            $scope.alertaCuadro = true;
            $scope.alertaColor = "success";
            $scope.alertaIcono = "ok";
            $scope.alertaTexto = "Se guardo la cita con exito en la base de datos.";

            $scope.listado();
        });     
    }

    // Obtener cita.

    $scope.obtener = function(id) 
    {
        $scope.iconoModal = "pencil";
        $scope.nombreModal = "Editar";
        
        $scope.master = {};
        $scope.master = angular.copy($scope.citas);

        $scope.campos = $filter('getById')($scope.master, id);
        
        var t = $scope.campos.fecha.split(/[- :]/);
        var fecha = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

        var dd = fecha.getDate();
        var mm = fecha.getMonth()+1;
        var yyyy = fecha.getFullYear();
        var h = fecha.getHours();
        var m = fecha.getMinutes();
        var a = 'AM';

        if(dd<10) {dd='0'+dd;} 

        if(mm<10 || mm == 0) {mm='0'+mm;}

        if(h>12) {h = h - 12; a = 'PM';}

        fecha = dd+'-'+mm+'-'+yyyy;
        
        $scope.campos.fecha = fecha;
        $scope.campos.hora = h;
        $scope.campos.minutos = m;
        $scope.campos.horario = a;
    }

    // Editar cita.

    $scope.editar = function(campos)
    {
        campos.fecha = $("#dp1").val();

        $http.put('api/editarCitaUsuario/'+campos.id, campos).success(function(data) 
        {
            $('#myModal').modal('hide');
            
            $scope.alertaCuadro = true;
            $scope.alertaColor = "success";
            $scope.alertaIcono = "ok";
            $scope.alertaTexto = "Se edito la cita con exito en la base de datos.";
            
            $scope.listado(); 
        });
    }

    // Limpiar campos.

    $scope.limpiar = function(id) 
    {
        $scope.iconoModal = "plus";
        $scope.nombreModal = "Agregar";

        var fecha = new Date();
        var dd = fecha.getDate();
        var mm = fecha.getMonth()+1; //January is 0!
        var yyyy = fecha.getFullYear();

        if(dd<10) { dd='0'+dd } 

        if(mm<10) { mm='0'+mm } 

        fecha = dd+'-'+mm+'-'+yyyy;
        
        $scope.campos = {};
        $scope.campos.fecha = fecha;
        $scope.campos.hora = 9;
        $scope.campos.minutos = 0;
        $scope.campos.horario = 'AM';
    }
});