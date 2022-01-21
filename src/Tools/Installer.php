<?php
/**
 * There's an example installer here - https://github.com/pimcore/pimcore/blob/10.x/bundles/EcommerceFrameworkBundle/Tools/Installer.php#L210
 * The definitions to go along with this are here - https://github.com/pimcore/pimcore/tree/10.x/bundles/EcommerceFrameworkBundle/Resources/install/class_sources
 *
 **/


namespace Creode\AvatarBundle\Tools;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Exception;
use Pimcore\Db\ConnectionInterface;
use Pimcore\Extension\Bundle\Installer\AbstractInstaller;
use Pimcore\Extension\Bundle\Installer\Exception\InstallationException;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\ClassDefinition\CustomLayout;
use Pimcore\Model\DataObject\ClassDefinition\Service;
use Pimcore\Model\User\Role\Folder AS RoleFolder;
use Pimcore\Model\User\Permission\Definition;
use Pimcore\Model\User\UserRole;
use Pimcore\Model\User\Workspace\DataObject;

class Installer extends AbstractInstaller
{
    /**
     * @var ConnectionInterface
     */
    protected $db;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var string
     */
    private string $installSourcesPath;

    /**
     * See example installer in first comment for instructions on how to use this
     *
     * @var array
     */
    private array $tablesToInstall = [];

    /**
     * This is the structure: "class name => unique identifier"
     * There should be no spaces in either of these.
     * The unique identifier should match that in the 'id' attribute of the json export
     *
     * @var array
     */
    private array $classesToInstall = [
        'Avatar' => 'avatar',
    ];

    /**
     * Add the permissions below
     *
     * Note that these are added in a way that makes them available to roles/users,
     * it does not assign them to anything.
     *
     * If you want to add a permission to a workflow then you need to create a role
     * to attach it to
     *
     * @var array
     */
    private array $permissionsToInstall = [
        'avatar_approve_stage1',
        'avatar_approve_stage2',
        'avatar_approve_stage3',
        'avatar_approve_stage4',
        'avatar_approve_stage5',
        'avatar_approve_final',
        'avatar_admin',
        'avatar_publish',
        'avatar_crud',
    ];

    /**
     * Add the roles below
     *
     * The structure is 'foldername' => [ [role], [role], [role] ]
     *
     * The role arrays should contain the following fields for each role:
     *  [
     *       'name' => 'AVATAR_APPROVE_FINAL',
     *       'permissions' => 'avatar_approve_final', // comma separated, should match an item from $permissionsToInstall
     *       'classes' => 'avatar', // comma separated, should match an item from $classesToInstall (the unique identifier)
     *       'admin' => 0
     *  ]
     *
     * @var array
     */
    private array $rolesToInstall = [
        'Avatar' => [
            [
                'name' => 'AVATAR_APPROVE_STAGE1',
                'permissions' => 'avatar_approve_stage1',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_APPROVE_STAGE2',
                'permissions' => 'avatar_approve_stage2',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_APPROVE_STAGE3',
                'permissions' => 'avatar_approve_stage3',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_APPROVE_STAGE4',
                'permissions' => 'avatar_approve_stage4',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_APPROVE_STAGE5',
                'permissions' => 'avatar_approve_stage5',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_APPROVE_FINAL',
                'permissions' => 'avatar_approve_final',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_ADMIN',
                'permissions' => 'avatar_admin',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 1,
                            'unpublish' => 1,
                            'delete' => 1,
                            'rename' => 1,
                            'create' => 1,
                            'settings' => 1,
                            'versions' => 1,
                            'properties' => 1,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_PUBLISH',
                'permissions' => 'avatar_publish',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 1,
                            'unpublish' => 1,
                            'delete' => 0,
                            'rename' => 0,
                            'create' => 0,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ],
            [
                'name' => 'AVATAR_CRUD',
                'permissions' => 'avatar_crud',
                'classes' => 'avatar',
                'admin' => 0,
                'workspaces' => [
                    'objects' => [
                        [
                            'cPath' => '/Avatar',
                            'list' => 1,
                            'view' => 1,
                            'save' => 1,
                            'publish' => 0,
                            'unpublish' => 0,
                            'delete' => 1,
                            'rename' => 1,
                            'create' => 1,
                            'settings' => 0,
                            'versions' => 0,
                            'properties' => 0,
                        ]
                    ]
                ]
            ]
        ]
    ];

    /**
     * This is the structure: "custom definition name => unique identifier"
     * The unique identifier should match the name of the layout in the workflow (config.yaml)
     *
     * @var array
     */
    private array $customLayoutDefinitionsToInstall = [
        'Avatar Stage 1' => ['id' => 'AvatarStage1', 'class' => 'Avatar'],
        'Avatar Stage 2' => ['id' => 'AvatarStage2', 'class' => 'Avatar'],
        'Avatar Stage 3' => ['id' => 'AvatarStage3', 'class' => 'Avatar'],
        'Avatar Stage 4' => ['id' => 'AvatarStage4', 'class' => 'Avatar'],
        'Avatar Stage 5' => ['id' => 'AvatarStage5', 'class' => 'Avatar'],
        'Avatar Completed' => ['id' => 'AvatarCompleted', 'class' => 'Avatar'],
        'Avatar Review' => ['id' => 'AvatarReview', 'class' => 'Avatar'],
    ];

    /**
     *
     */
    public function __construct(
        ConnectionInterface $connection
    ) {
        $this->installSourcesPath = __DIR__ . '/../Resources/install';

        $this->db = $connection;

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        if ($this->db instanceof Connection) {
            $this->schema = $this->db->getSchemaManager()->createSchema();
        }

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function install()
    {
        $this->installClasses();
        $this->installCustomLayoutDefinitions();
        $this->installPermissions();
        $this->installRoles();

        parent::install();
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall()
    {
        $this->uninstallRoles();
        $this->uninstallPermissions();
    }

    /**
     * {@inheritdoc}
     */
    public function isInstalled(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeInstalled(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeUninstalled(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function needsReloadAfterInstall(): bool
    {
        return true;
    }


    /**
     * @return array
     */
    private function getClassesToInstall(): array
    {
        $result = [];
        foreach (array_keys($this->classesToInstall) as $className) {
            $filename = sprintf('class_%s_export.json', $className);
            $path = $this->installSourcesPath . '/class_sources/' . $filename;
            $path = realpath($path);

            if (false === $path || !is_file($path)) {
                throw new InstallationException(sprintf(
                    'Class export for class "%s" was expected in "%s" but file does not exist',
                    $className,
                    $path
                ));
            }

            $result[$className] = $path;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function installClasses()
    {
        $classes = $this->getClassesToInstall();

        $mapping = $this->classesToInstall;

        foreach ($classes as $key => $path) {
            $class = ClassDefinition::getByName($key);

            if ($class) {
                $this->output->write(sprintf(
                    '     <comment>WARNING:</comment> Skipping class "%s" as it already exists',
                    $key
                ));

                continue;
            }

            $class = new ClassDefinition();

            $classId = $mapping[$key];

            $class->setName($key);
            $class->setId($classId);

            $data = file_get_contents($path);
            $success = Service::importClassDefinitionFromJson($class, $data, false, true);

            if (!$success) {
                throw new InstallationException(sprintf(
                    'Failed to create class "%s"',
                    $key
                ));
            }
        }
    }

    /**
     * @return array
     */
    private function getCustomLayoutDefinitionsToInstall(): array
    {
        $result = [];
        foreach (array_keys($this->customLayoutDefinitionsToInstall) as $className) {
            $filename = sprintf('custom_definition_%s_export.json', $className);
            $path = $this->installSourcesPath . '/layout_sources/' . $filename;
            $path = realpath($path);

            if (false === $path || !is_file($path)) {
                throw new InstallationException(sprintf(
                    'Custom Definition export for "%s" was expected in "%s" but file does not exist',
                    $className,
                    $path
                ));
            }

            $result[$className] = $path;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function installCustomLayoutDefinitions()
    {
        $customLayoutDefinitions = $this->getCustomLayoutDefinitionsToInstall();

        $mapping = $this->customLayoutDefinitionsToInstall;

        foreach ($customLayoutDefinitions as $customLayoutName => $path) {
            $customLayout = CustomLayout::getByName($customLayoutName);

            if ($customLayout) {
                $this->output->write(sprintf(
                    '     <comment>WARNING:</comment> Skipping custom layout "%s" as it already exists',
                    $customLayoutName
                ));

                continue;
            }

            $className = $mapping[$customLayoutName]['class'];
            $customLayoutDefinitionId = $mapping[$customLayoutName]['id'];

            // check that the related class exists
            $class = ClassDefinition::getByName($className);
            if ($class) {
                $customLayout = CustomLayout::create(
                    [
                        'id' => $customLayoutDefinitionId,
                        'classId' => $class->getId(),
                        'name' => $customLayoutName,
                    ]
                );
            }

            $json = file_get_contents($path);
            $importData = json_decode($json, true);

            try {
                /** @noinspection PhpInternalEntityUsedInspection */
                $layout = Service::generateLayoutTreeFromArray($importData['layoutDefinitions'], true);
                $customLayout->setLayoutDefinitions($layout);
                $customLayout->setDescription($importData['description']);
                $customLayout->save();
            } catch (\Exception $e) {
                throw new InstallationException(sprintf(
                    'Failed to create custom layout "%s"',
                    $customLayoutDefinitionId
                ));
            }
        }
    }

    private function installPermissions()
    {
        foreach ($this->permissionsToInstall as $permission) {
            $definition = Definition::getByKey($permission);

            if ($definition) {
                $this->output->write(sprintf(
                    '     <comment>WARNING:</comment> Skipping permission "%s" as it already exists',
                    $permission
                ));

                continue;
            }

            try {

                Definition::create($permission);
            } catch (\Throwable $e) {
                throw new InstallationException(sprintf(
                    'Failed to create permission "%s": %s',
                    $permission, $e->getMessage()
                ));
            }
        }
    }

    private function uninstallPermissions()
    {
        foreach ($this->permissionsToInstall as $permission) {
            $this->db->executeQuery('DELETE FROM users_permission_definitions WHERE `key` = :key', [
                'key' => $permission,
            ]);
        }
    }

    private function installRoles()
    {
        foreach ($this->rolesToInstall as $roleFolderName => $roles) {
            $folderDefinition = RoleFolder::getByName($roleFolderName);

            // create the folder if it doesn't exist
            if ($folderDefinition) {
                $this->output->write(sprintf(
                    '     <comment>WARNING:</comment> Skipping role folder "%s" as it already exists',
                    $roleFolderName
                ));
            } else {
                // this is what we're creating
                $roleFolder = [
                    'parentId' => 0,
                    'type' => 'rolefolder',
                    'name' => $roleFolderName,
                    'active' => 1,
                    'admin' => 0
                ];

                try {
                    $folderDefinition = RoleFolder::create($roleFolder);
                } catch (\Throwable $e) {
                    throw new InstallationException(sprintf(
                        'Failed to create role folder "%s": %s',
                        $roleFolderName, $e->getMessage()
                    ));
                }
            }

            // add each role in this folder
            foreach ($roles as $role) {
                $definition = UserRole::getByName($role['name']);

                if ($definition) {
                    $this->output->write(sprintf(
                        '     <comment>WARNING:</comment> Skipping role "%s" as it already exists',
                        $role['name']
                    ));

                    continue;
                }

                // flesh it out
                $roleDefaults = [
                    'parentId' => $folderDefinition->getId(),
                    'type' => 'role',
                    'active' => 1
                ];

                $workspaces = $role['workspaces'];
                unset($role['workspaces']);

                $roleFull = array_merge($roleDefaults, $role);

                try {
                    $userRole = UserRole::create($roleFull);

                    if (isset($workspaces['objects'])) {
                        $workspaceObjects = [];

                        foreach ($workspaces['objects'] as $objectValues) {
                            $objectProperties = new DataObject();
                            $objectProperties->setValues($objectValues);
                            $workspaceObjects[] = $objectProperties;
                        }

                        $userRole->setWorkspacesObject($workspaceObjects);
                    }

                    // save workspaces
                    $userRole->save();

                } catch (\Throwable $e) {
                    throw new InstallationException(sprintf(
                        'Failed to create permission "%s": %s',
                        $roleFull['name'], $e->getMessage()
                    ));
                }
            }
        }
    }

    private function uninstallRoles()
    {
        foreach ($this->rolesToInstall as $roleFolderName => $roles) {
            // remove folder, this will automatically remove the roles too
            $folderDefinition = RoleFolder::getByName($roleFolderName);
            $folderDefinition?->delete();
        }
    }
}
