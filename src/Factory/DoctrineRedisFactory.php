<?php

/*
 * This file is part of php-cache\adapter-bundle package.
 *
 * (c) 2015-2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\AdapterBundle\Factory;

use Cache\Adapter\Doctrine\DoctrineCachePool;
use Doctrine\Common\Cache\RedisCache;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DoctrineRedisFactory implements AdapterFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createAdapter(array $options = [])
    {
        if (!class_exists('Cache\Adapter\Doctrine\DoctrineCachePool')) {
            throw new \LogicException('You must install the "cache/doctrine-adapter" package to use the "doctrine_redis" provider.');
        }

        $config = $this->configureOptions($options);
        $redis = new \Redis();
        $redis->connect($config['host'], $config['port']);

        $client = new RedisCache();
        $client->setRedis($redis);

        return new DoctrineCachePool($client);
    }


    /**
     * @param array $options
     *
     * @return array
     */
    private function configureOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'host' => '127.0.0.1',
            'port' => '6379',
        ]);

        $resolver->setAllowedTypes('host', ['string']);
        $resolver->setAllowedTypes('port', ['string', 'int']);

        return $resolver->resolve($options);
    }
}
