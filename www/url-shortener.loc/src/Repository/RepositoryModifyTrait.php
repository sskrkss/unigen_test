<?php

namespace App\Repository;

trait RepositoryModifyTrait
{
    public function save(object $object): void
    {
        $realClass = $this->getEntityManager()->getClassMetadata(get_class($object))->getName();
        assert($this->getClassName() === $realClass);
        $this->getEntityManager()->persist($object);
    }

    public function remove(object $object): void
    {
        $realClass = $this->getEntityManager()->getClassMetadata(get_class($object))->getName();
        assert($this->getClassName() === $realClass);
        $this->getEntityManager()->remove($object);
    }

    public function saveAndCommit(object $object): void
    {
        $this->save($object);
        $this->commit();
    }

    public function removeAndCommit(object $object): void
    {
        $this->remove($object);
        $this->commit();
    }

    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }
}
