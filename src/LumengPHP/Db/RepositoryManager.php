<?php

namespace LumengPHP\Db;

/**
 * 仓库管理器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
final class RepositoryManager {

    /**
     * @var ConnectionManager
     */
    private $connManager;

    private function __construct(ConnectionManager $connManager) {
        $this->connManager = $connManager;
    }

    /**
     * 返回实体的仓库实例
     * @param string $entityName 实体名称
     * @return Repository The repository instance.
     */
    public function getRepository($entityName) {
        $connection = $this->connManager->getConnection();
        return new Repository($connection, $entityName);
    }

    /**
     * 创建并返回一个RepositoryManager实例
     * @param ConnectionManager $connManager
     * @return RepositoryManager
     */
    public static function create(ConnectionManager $connManager) {
        return new self($connManager);
    }

}
