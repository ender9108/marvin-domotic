<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\MenuCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $menuCategories = [
            'Général' => [
                'enable' => true,
                'order' => 10,
                'menus' => [
                    [
                        'title' => 'Dashboard',
                        'path' => 'home.index',
                        'icon' => 'fab fa-uncharted fs-3',
                        'order' => 10,
                        'enable' => true,
                        'role' => 'ROLE_USER',
                        'activeToken' => 'HomeController',
                    ],
                ],
            ],
            'Domotique' => [
                'enable' => true,
                'order' => 20,
                'menus' => [
                    [
                        'title' => 'Pièces',
                        'path' => 'room.index',
                        'icon' => 'fas fa-home fs-3',
                        'order' => 10,
                        'enable' => true,
                        'role' => 'ROLE_USER',
                        'activeToken' => 'RoomController',
                    ], [
                        'title' => 'Mes modules',
                        'path' => 'module.index',
                        'icon' => 'fas fa-wifi fs-3',
                        'order' => 20,
                        'enable' => true,
                        'role' => 'ROLE_USER',
                        'activeToken' => 'ModuleController',
                    ], [
                        'title' => 'Protocols',
                        'path' => 'protocol.index',
                        'icon' => 'fas fa-signal fs-3',
                        'order' => 30,
                        'enable' => true,
                        'role' => 'ROLE_USER',
                        'activeToken' => 'ProtocolController',
                    ],
                ],
            ],
            'Administration' => [
                'enable' => true,
                'order' => 900,
                'menus' => [
                    [
                        'title' => 'Utilisateurs',
                        'path' => 'user.index',
                        'icon' => 'fas fa-users fs-3',
                        'order' => 10,
                        'enable' => true,
                        'role' => 'ROLE_ADMIN',
                        'activeToken' => 'SecurityController',
                    ], [
                        'title' => 'Paramètres',
                        'path' => 'parameter.index',
                        'icon' => 'fas fa-cogs fs-3',
                        'order' => 20,
                        'enable' => true,
                        'role' => 'ROLE_ADMIN',
                        'activeToken' => 'ParameterController',
                    ], [
                        'title' => 'Système',
                        'path' => 'system.index',
                        'icon' => 'fas fa-server fs-3',
                        'order' => 30,
                        'enable' => true,
                        'role' => 'ROLE_ADMIN',
                        'activeToken' => 'SystemController',
                    ],
                ],
            ],
        ];

        foreach ($menuCategories as $title => $category) {
            $menuCategory = new MenuCategory();
            $menuCategory
                ->setTitle($title)
                ->setIsEnable($category['enable'])
                ->setMenuCategoryOrder($category['order'])
            ;

            foreach ($category['menus'] as $menu) {
                $menuItem = new Menu();
                $menuItem
                    ->setTitle($menu['title'])
                    ->setPath($menu['path'])
                    ->setIsEnable($menu['enable'])
                    ->setIcon($menu['icon'])
                    ->setMenuOrder($menu['order'])
                    ->setRole($menu['role'])
                    ->setActiveToken($menu['activeToken'])
                ;
                $menuCategory->addMenu($menuItem);
            }

            $manager->persist($menuCategory);
        }

        $manager->flush();
    }
}
