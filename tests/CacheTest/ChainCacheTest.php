<?php

namespace CacheTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\Cache\Simple\ChainCache;

class SimpleChainCacheTest extends TestCase
{
	public function testEmptySimpleChainCache()
	{
		$cache1 = new ArrayCache(0);
		$cache2 = new ArrayCache(2);

		$chainCache = new ChainCache([$cache1, $cache2]);

		$chainCache->set("key", "test");

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals("test", $cache2->get("key"));

		sleep(3);

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals(null, $cache2->get("key"));
	}

	public function testEmptyChainAdapter()
	{
		$adapter1 = new ArrayAdapter(0);
		$cache1 = new Psr16Cache($adapter1);

		$adapter2 = new ArrayAdapter(2);
		$cache2 = new Psr16Cache($adapter2);

		$chainCache = new Psr16Cache(new ChainAdapter([$adapter1, $adapter2]));

		$chainCache->set("key", "test");

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals("test", $cache2->get("key"));

		sleep(3);

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals(null, $cache2->get("key"));
	}

	public function testNonEmptySimpleChainCache()
	{
		$cache1 = new ArrayCache(0);
		$cache2 = new ArrayCache(2);

		$chainCache = new ChainCache([$cache1, $cache2]);

		$cache1->set("key", "test1");

		self::assertEquals("test1", $cache1->get("key"));
		self::assertEquals(null, $cache2->get("key"));

		$chainCache->set("key", "test");

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals("test", $cache2->get("key"));

		sleep(3);

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals(null, $cache2->get("key"));
	}

	public function testNonEmptyChainAdapter()
	{
		$adapter1 = new ArrayAdapter(0);
		$cache1 = new Psr16Cache($adapter1);

		$adapter2 = new ArrayAdapter(2);
		$cache2 = new Psr16Cache($adapter2);

		$cache1->set("key", "test1");

		self::assertEquals("test1", $cache1->get("key"));
		self::assertEquals(null, $cache2->get("key"));

		$chainCache = new Psr16Cache(new ChainAdapter([$adapter1, $adapter2]));

		$chainCache->set("key", "test");

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals("test", $cache2->get("key"));

		sleep(3);

		self::assertEquals("test", $cache1->get("key"));
		self::assertEquals(null, $cache2->get("key"));
	}
}
