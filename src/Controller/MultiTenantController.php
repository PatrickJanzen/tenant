<?php

namespace App\Controller;

use App\Entity\Main\TenantDbConfig;
use Hakam\MultiTenancyBundle\Services\DbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Hakam\MultiTenancyBundle\Event\SwitchDbEvent;
use Hakam\MultiTenancyBundle\Doctrine\ORM\TenantEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tenant\TestEntity;
use App\Entity\Main\MainEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MultiTenantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $mainEntityManager,
        private TenantEntityManager $tenantEntityManager,
        private EventDispatcherInterface $dispatcher,
        private DbService $dbService,
    ) {
    }

    /**
     * Create two new tenant configs in the main database.
     * Generate new databases for each tenant.
     * Update the schema on the new databases.
     */
    #[Route('/build_db', name: 'app_build_db')]
    public function buildDb()
    {
        $tenants = [];

        // Create a new TenantDBConfig
        // Currently the new database should have the same username and password   as the main user  , cuz we are using the same user for all databases.
        // Multi users will be added in the future.
        $tenantDb = new TenantDbConfig();
        $tenantDb
            ->setDbName('liveTenantDb1' . time())
            ->setDbUserName('root') // the same db user as main db
            ->setDbPassword('root') // the same db password as main db
        ;
        $this->mainEntityManager->persist($tenantDb);
        $tenants[] = $tenantDb;

        // Create a new TenantDBConfig
        $tenantDb = new TenantDbConfig();
        $tenantDb
            ->setDbName('liveTenantDb2' . time())
            ->setDbUserName('root') // the same db user as main db
            ->setDbPassword('root') // the same db password as main db
        ;
        $tenants[] = $tenantDb;
        $this->mainEntityManager->persist($tenantDb);

        // Persist the new configurations to the main database.
        $this->mainEntityManager->flush();

        // For each of the new tenants, create a new database and set it's schema
        foreach ($tenants as $tenantDb) {
            $this->dbService->createDatabase($tenantDb->getDbName());
            $this->dbService->createSchemaInDb($tenantDb->getId());
        }

        return new JsonResponse();
    }

    /**
     * An example of how to switch and update tenant databases
     */
    #[Route('/test_db', name: 'app_test_db')]
    public function testDb(EntityManagerInterface $entityManager)
    {

        $tenantDbConfigs = $this->mainEntityManager->getRepository(TenantDbConfig::class)->findAll();

        foreach ($tenantDbConfigs as $tenantDbConfig) {
            // Dispatch an event with the index ID for the entity that contains the tenant database connection details.
            $switchEvent = new SwitchDbEvent($tenantDbConfig->getId());
            $this->dispatcher->dispatch($switchEvent);

            $tenantEntity1 = new TestEntity();
            $tenantEntity1->setName($tenantDbConfig->getDbName());

            $this->tenantEntityManager->persist($tenantEntity1);
            $this->tenantEntityManager->flush();
        }

        // Add a new entity to the main database.
        $mainLog = new MainEntity();
        $mainLog->setName('mainTtest');
        $this->mainEntityManager->persist($mainLog);
        $this->mainEntityManager->flush();

        return new JsonResponse();
    }
}
