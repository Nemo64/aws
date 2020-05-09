<?php

namespace AsyncAws\DynamoDbSession;


use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\DynamoDb\DynamoDbClient;

class LockingSessionConnection extends StandardSessionConnection
{
    public function __construct(DynamoDbClient $client, array $config = [])
    {
        parent::__construct($client, $config);
    }

    /**
     * {@inheritdoc}
     * Retries the request until the lock can be acquired
     */
    public function read($id)
    {
        // Create the params for the UpdateItem operation so that a lock can be
        // set and item returned (via ReturnValues) in a one, atomic operation.
        $params = [
            'TableName' => $this->getTableName(),
            'Key' => $this->formatKey($id),
            'Expected' => ['lock' => ['Exists' => false]],
            'AttributeUpdates' => ['lock' => ['Value' => ['N' => '1']]],
            'ReturnValues' => 'ALL_NEW',
        ];

        // Acquire the lock and fetch the item data.
        $timeout = time() + $this->getMaxLockWaitTime();
        while (true) {
            try {
                $item = [];
                $result = $this->client->updateItem($params);

                $item[$this->getHashKey()] = $result[$this->getHashKey()]->getS();
                $type = $this->getDataAttributeType();
                if ($type == 'binary') {
                    $item[$this->getDataAttribute()] = $result[$this->getDataAttribute()]->getB();
                } else {
                    $item[$this->getDataAttribute()] = $result[$this->getDataAttribute()]->getS();
                }
                $item[$this->getSessionLifetimeAttribute()] = $result[$this->getSessionLifetimeAttribute()]->getN();

                return $item;
            } catch (HttpException $e) {
                if ($e->getAwsCode() === 'ConditionalCheckFailedException' && time() < $timeout) {
                    usleep(rand($this->getMinLockRetryMicrotime(), $this->getMaxLockRetryMicrotime()));
                } else {
                    break;
                }
            }
        }

        return [];
    }
}
