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

            // Añadir Saldo
            'Ecommerce' => [
                'submenu' => 0,
                'ruta' => route('shop'),
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-shopping-cart',
                'complementoruta' => '',
            ],
            // Fin añadir saldo

            // Organización
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
            // Fin Organización
       
            //Inverisones
            'Inverisones' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-activity',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Activas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('inversiones.index', 1),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Culminadas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('inversiones.index', 2),
                        'complementoruta' => '',
                    ],
                ],
            ],
            // Fin Inverisones

            // Financiero
            'Financiero' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-dollar-sign',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Pagos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('payments.index'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Billetera',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('wallet.index'),
                        'complementoruta' => '',
                    ],
                    [
                        'name' => 'Historial de Ordenes',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('shop.orden.history'),
                        'complementoruta' => '',
                    ],
                ],
            ],
            // Fin Financiero

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
                        'name' => 'Paquetes',
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
            // Informes
            'Informes' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-file-text',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Pedidos',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('reports.pedidos'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Comisiones',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('reports.comision'),
                        'complementoruta' => ''
                    ],
                ],
            ],
            // Fin Informes
            //Inverisones
            'Inversiones' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-activity',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Activas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('inversiones.index', 1),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Culminadas',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('inversiones.index', 2),
                        'complementoruta' => '',
                    ],
                ],
            ],
            // Fin Inverisones
            // Organización
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
                        'name' => 'Usuarios',
                        'ruta' => route('users.list-user'),
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'icon' => 'fa fa-users',
                        'complementoruta' => '',
                    ],
                ],
            ],
            // Fin organizacion
 
            // Liquidaciones
            'Liquidaciones' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'fa fa-list-alt',
                'complementoruta' => '',
                'submenus' => [

                    [
                        'name' => 'Cierre Comisiones',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('commission_closing.index'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Generar Liquidaciones',
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
                    ]
                ],
            ],
            'Crons' => [
                'submenu' => 1,
                'ruta' => 'javascript:;',
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'fa fa-list-alt',
                'complementoruta' => '',
                'submenus' => [
                    [
                        'name' => 'Comisiones',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('pagarComisiones'),
                        'complementoruta' => ''
                    ],
                    [
                        'name' => 'Cambiar estatus inversion',
                        'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                        'ruta' => route('inversiones.checkStatus'),
                        'complementoruta' => ''
                    ]
                ]
            ],
            // Fin Liquidaciones

            //Billetera
            'Billetera' => [
                'submenu' => 0,
                'ruta' => route('wallet.index'),
                'blank'=> '', // si es para una pagina diferente del sistema solo coloquen _blank
                'icon' => 'feather icon-credit-card',
                'complementoruta' => '',
            ]
            //Fin Billetera

        ];
    }
}
