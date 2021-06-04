<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $menu = null;
        if (Auth::check()) {
            $menu = $this->menuUsuario(); 
            if (Auth::user()->admin == 1) {
                $menu = $this->menuAdmin();
            }
        }
        View::share('menu', $menu);
        return $next($request);
    }

    /**
     * Permite Obtener el menu del usuario
     *
     * @return void
     */
    public function menuUsuario()
    {
       // $orden = app($OrdenService)->find($id);

        return [
            // Inicio
            'Inicio' => [
                'submenu' => 0,
                'ruta' => route('home.user'),
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-home',
                'complementoruta' => '',
            ],
            // Fin inicio
            // Servicios
            'Servicios' => [
                'submenu' => 0,
                'ruta' => '',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-briefcase',
                'complementoruta' => '',
            ],
            // Fin Servicios
            // Añadir Saldo
            'Añadir Saldo' => [
                'submenu' => 0,
                'ruta' => '',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-plus-circle',
                'complementoruta' => '',
            ],
            // Fin añadir saldo
            // Red
            'Organización' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-users',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Arbol',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_type', 'tree'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Referidos Directos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_list_network', 'direct'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Referidos en Red',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_list_network', 'network'),
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Fin red
             // tickets
             'Tickets' => [
                'submenu' => 0,
                'ruta' => '',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-tag',
                'complementoruta' => '',
            ],
            // Fin tickets
            'Inverisones' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-users',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Activas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_type', 'tree'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Culminadas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('wallet.index'),
                        'complementoruta' => '',
                    ],
                ],
            ],
            // Red
            'Financiero' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-users',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Pagos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_type', 'tree'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Billetera',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('wallet.index'),
                        'complementoruta' => '',
                    ],
                    [
                        'name' => 'Referidos en Red',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_list_network', 'network'),
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Billetera
      
            // Fin billetera
            // Historial de Ordenes
            'Historial de Ordenes' => [
                'submenu' => 0,
                'ruta' => '',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-clipboard',
                'complementoruta' => '',
            ],
            // Fin historial de ordenes
        ];
    }

    /**
     * Permite Obtener el menu del admin
     *
     * @return void
     */
    public function menuAdmin()
    {
        return [
            // Inicio
            'Dashboard' => [
                'submenu' => 0,
                'ruta' => route('home'),
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-home',
                'complementoruta' => '',
            ],
            // Fin inicio
               // Ecommerce
               'Ecommerce' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-shopping-cart',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Grupos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('group.index'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Productos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('package.index'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Tienda',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('shop'),
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Fin Ecommerce
            // Informenes
            'Informenes' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-file-text',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Pedidos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => '',
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Comisiones',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => '',
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Liquidaciones',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => '',
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Fin Informenes
            // Red
            'Red' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-users',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Arbol',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_type', 'tree'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Referidos Directos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_list_network', 'direct'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Referidos en Red',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('genealogy_list_network', 'network'),
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Fin red
            // Liquidaciones
            'Liquidaciones' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'fa fa-list-alt',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'General Liquidaciones',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('settlement'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Liquidaciones Pendientes',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('settlement.pending'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Liquidaciones Pagadas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('settlement.history.status', 'Pagadas'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Liquidaciones Reservadas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('settlement.history.status', 'Reservadas'),
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Fin Liquidaciones
            // Usuarios
            'Usuarios' => [
                'submenu' => 0,
                'ruta' => route('users.list-user'),
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'fa fa-users',
                'complementoruta' => '',
            ],
            // Fin Usuarios
        ];
    }
}
